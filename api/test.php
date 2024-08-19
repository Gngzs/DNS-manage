<?php

use function cf\Checktoken;
use OTPHP\TOTP;
use Clock\SystemClock;

require_once("../common/basic.php");
require_once("main.php");
require_once "./server/cf.php";
require_once "../common/totp.php";
require_once "../common/clock.php";
/*
$sql = "SELECT * FROM list_domain";
$sqlres = executeQuery($sql);

print_r($sqlres);
*/

/*
if (ymtotp::VerifyCode(614678)) {
    echo '验证码有效，认证成功！';
} else {
    echo '验证码无效，认证失败！';
}
*/
//print_r(ymtotp::VerifyCode(12255)."/n".time());

print_r(ymtotp::CreatQR("test","GYUFYUBYFTD"));


/*
function base32_decode($base32) {
    $base32 = strtoupper($base32);
    $base32_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $binary = '';

    for ($i = 0; $i < strlen($base32); $i++) {
        $binary .= str_pad(decbin(strpos($base32_chars, $base32[$i])), 5, '0', STR_PAD_LEFT);
    }

    // 将二进制字符串转换为字节
    $bytes = '';
    for ($i = 0; $i < strlen($binary); $i += 8) {
        $bytes .= pack('H*', dechex(bindec(substr($binary, $i, 8))));
    }

    return $bytes;
}

function hmac_sha1($key, $data) {
    return hash_hmac('sha1', $data, $key, true);
}

function generateOTP($secret, $timeSlice) {
    // 1. 计算时间戳
    $time = floor(time() / $timeSlice);
    
    // 2. 对时间戳进行 HMAC-SHA1 计算
    $key = base32_decode($secret);
    $timeData = pack('N*', $time);
    $hmac = hmac_sha1($key, $timeData);

    // 3. 获取 HMAC 的最后一个字节
    $offset = ord($hmac[19]) & 0x0F;

    // 4. 提取动态截断的 HMAC 值
    $binary = ((ord($hmac[$offset]) & 0x7F) << 24) |
              ((ord($hmac[$offset + 1]) & 0xFF) << 16) |
              ((ord($hmac[$offset + 2]) & 0xFF) << 8) |
              (ord($hmac[$offset + 3]) & 0xFF);

    // 5. 计算 OTP
    $otp = $binary % 1000000; // 6 位 OTP
    return str_pad($otp, 6, '0', STR_PAD_LEFT);
}

echo generateOTP("guangnian",30);

$clock = new SystemClock();
$otp = TOTP::generate($clock);
echo "The OTP secret is: {$otp->getSecret()}\n";
*/