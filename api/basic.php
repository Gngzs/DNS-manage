<?php
include("config.php");

session_start();
function base64url_decode($data)
{
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

//生成JWT
function NewJwt($name, $nowtime)
{
    $header = array(
        "alg" => "HS256",
        "typ" => "JWT"
    );
    $payload = array(
        "iat" => $nowtime,
        "exp" => $nowtime + 21600,
        "iss" => "DNS_manege",
        "una" => $name
    );
    global $key;
    $base64header = base64url_encode(json_encode($header));
    $base64payload = base64url_encode(json_encode($payload));
    $signature = hash_hmac("sha256", $base64header . "." . $base64payload, $key, true);
    return $base64header . "." . $base64payload . "." . base64url_encode($signature);
}

//校验以及解析jwt
function validateJWT($jwt)
{
    global $key;
    // Split the JWT into its parts
    $parts = explode('.', $jwt);
    if (count($parts) == 3) {
        $encodedHeader = $parts[0];
        $encodedPayload = $parts[1];
        $signatureProvided = $parts[2];

        // Create the Base64 URL encoded header and payload
        $headerAndPayload = $encodedHeader . '.' . $encodedPayload;

        // Calculate the signature based on header and payload using the secret
        $signatureCalculated = base64url_encode(hash_hmac('sha256', $headerAndPayload, $key, true));

        // Check if the provided signature matches the calculated signature
        if ($signatureProvided == $signatureCalculated) {
            // Decode the payload
            $payload = json_decode(base64url_decode($encodedPayload), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                // Return the payload as an array if everything is ok
                if ($payload['exp'] - time() > 0) {
                    return $payload;
                } else {
                    return false; //登录过期
                }
            } else {
                return false; //json格式错误
            }
        }
    }
    return false; //jwt校验失败
}

//数据库统一接口
function executeQuery($sql)
{
    global $sqlhost, $sqlname, $sqlpassword, $sqlport, $sqluser;
    // 创建连接
    $conn = new mysqli($sqlhost . ":" . $sqlport, $sqluser, $sqlpassword, $sqlname);

    // 检查连接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 准备SQL语句
    $stmt = $conn->prepare($sql);

    /*
    // 参数不为空时绑定参数
    if ($params) {
        $types = str_repeat('s', count($params)); // 参数类型字符串，这里默认使用字符串类型's'
        $stmt->bind_param($types, ...$params);
    }
    */

    // 执行查询
    $stmt->execute();

    // 获取结果
    $result = $stmt->get_result();
    $data = [];
    if ($result) {
        // 获取所有结果
        $data = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // 若执行非查询操作，返回受影响的行数
        $data = $stmt->affected_rows;
    }

    // 关闭语句和连接
    $stmt->close();
    $conn->close();

    // 返回结果
    return $data;
}

//发送邮件（待编写）


//生成验证码
function generateCaptchaImage($width = 120, $height = 40, $length = 4)
{
    // 生成验证码字符串
    $possibleCharacters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxy';
    $captcha = '';
    $max = strlen($possibleCharacters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $possibleCharacters[random_int(0, $max)];
    }

    // 创建一个图像对象
    $image = imagecreatetruecolor($width, $height);

    // 分配颜色
    $backgroundColor = imagecolorallocate($image, random_int(200, 255), random_int(200, 255), random_int(200, 255)); // 浅色背景
    $textColor = imagecolorallocate($image, random_int(0, 120), random_int(0, 120), random_int(0, 120)); // 深色文字

    // 填充背景颜色
    imagefill($image, 0, 0, $backgroundColor);

    // 添加随机线条
    for ($i = 0; $i < 5; $i++) {
        $lineColor = imagecolorallocate($image, random_int(0, 255), random_int(0, 255), random_int(0, 255));
        imageline($image, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $lineColor);
    }

    // 添加噪点
    for ($i = 0; $i < 1000; $i++) {
        $dotColor = imagecolorallocate($image, random_int(0, 255), random_int(0, 255), random_int(0, 255));
        imagesetpixel($image, random_int(0, $width), random_int(0, $height), $dotColor);
    }

    // 设置文字字体路径
    $fontPath = $_SERVER['DOCUMENT_ROOT'] . '/fonts/imageCaptchaFont.ttf'; // 确保这个路径指向了一个有效的.ttf字体文件

    //设置字体大小
    $fontSize = $height * 0.5;

    // 将验证码字符串画到图像上
    for ($i = 0; $i < $length; $i++) {
        $letterSpace = ($width / $length);
        $initial = $letterSpace * $i + ($letterSpace / 2) - $fontSize / 3;
        $angle = random_int(-15, 15);
        imagettftext($image, $fontSize, $angle, $initial, $height * 0.7, $textColor, $fontPath, $captcha[$i]);
    }

    // 存储验证码字符串到会话变量中
    $_SESSION['captcha'] = strtolower($captcha);

    // 输出图像到浏览器
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);

    return $captcha;
}

//校验验证码
function checkCaptcha($userInput)
{
    // 检查会话中是否存在验证码
    if (!isset($_SESSION['captcha'])) {
        return false;
    }

    // 比较用户输入的验证码和会话中的验证码
    if (strtolower($userInput) === $_SESSION['captcha']) {
        // 清除会话中的验证码
        unset($_SESSION['captcha']);
        return true;
    } else {
        return false;
    }
}

//curl请求
function CurlRequest($url, $method = 'GET', $data = null, $headers = null) {
    // 初始化cURL会话
    $curl = curl_init();

    // 设置cURL选项
    curl_setopt($curl, CURLOPT_URL, $url); // 设置请求的URL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // 启用时会将服务器服务器返回的“Location:”放在header中递归的返回给服务器

    // 根据请求方法设置特定的cURL选项
    switch (strtoupper($method)) {
        case 'POST': // POST请求
            curl_setopt($curl, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // 设置POST请求的数据
            }
            break;
        case 'PUT': // PUT请求
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // 设置PUT请求的数据
            }
            break;
        case 'DELETE': // DELETE请求
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
        // 可以继续添加其他请求方法
        default: // 默认GET请求
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data)); // 将GET参数添加到URL
                curl_setopt($curl, CURLOPT_URL, $url);
            }
            break;
    }

    // 设置Header信息
    if (!is_null($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    // 执行cURL请求
    $response = curl_exec($curl);

    // 检查是否有错误发生
    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        // 可以选择记录错误信息或者抛出异常等
        curl_close($curl);
        return $error_msg;
    }

    // 关闭cURL会话
    curl_close($curl);

    // 返回结果
    return $response;
}
