<?php
include("basic.php");


// 设置返回类型为JSON
header('Content-Type: application/json');


// 获取从AJAX传来的POST数据
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = md5(isset($_POST['password']) ? $_POST['password'] : '');
$usercode = isset($_POST['code']) ? $_POST['code'] : '';

//验证码检测
if (checkCaptcha($usercode)) {

    //调用数据库
    $login = executeQuery("SELECT * FROM `admin_user` WHERE `username` LIKE '" . $username . "' AND `password` LIKE '" . $password . "'");

    // 登录验证逻辑
    if (sizeof($login) > 0) {
        $last_login_time = time();
        //登录时间存入数据库
        executeQuery("UPDATE `admin_user` SET `last_login_time` = '" . $last_login_time . "' WHERE `admin_user`.`username` = '" . $username . "'");
        //设置jwt
        $jwt = NewJwt($username,  $last_login_time);
        // 登录成功
        setcookie("Authorization", $jwt, $last_login_time + 21600, "/");
        $response = array('success' => true, 'message' => '登录成功！');
    } else {
        // 登录失败
        $response = array('success' => false, 'message' => '用户名或密码错误。');
    }
} else {
    //验证码错误
    $response = array('success' => false, 'message' => '验证码错误');
}

// 输出JSON格式的响应
echo json_encode($response);
