<?php
require_once("../common/basic.php");
require_once("../common/totp.php");


// 设置返回类型为JSON
header('Content-Type: application/json');

// 获取从AJAX传来的POST数据
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = md5(isset($_POST['password']) ? $_POST['password'] : '');
$totpcode = isset($_POST['totpcode']) ? $_POST['totpcode'] : '';

$login = executeQuery("SELECT * FROM `admin_user` WHERE BINARY `username` = '" . $username . "' AND BINARY `password` = '" . $password . "'");
if (sizeof($login) > 0){
    $totp=executeQuery("SELECT * FROM `admin_user` WHERE BINARY `username`='$username'");
    if(ymtotp::VerifyCode($totp[0]["2fakey"],$totpcode)){
        $last_login_time = time();
        //登录时间存入数据库
        executeQuery("UPDATE `admin_user` SET `last_login_time` = '" . $last_login_time . "' WHERE `admin_user`.`username` = '" . $username . "'");
        //设置jwt
        $jwt = NewJwt($username,  $last_login_time);
        // 登录成功
        setcookie("Authorization", $jwt, $last_login_time + 21600, "/");
        setcookie("username", $username, $last_login_time + 21600, "/");
        $response = array('success' => true, 'message' => '登录成功！');
    }else{
        $response = array('success'=> false,'message'=> '验证码错误');
    }
}else{
    $response = array('success'=> false,'message'=> '账号或密码错误');
}

echo json_encode($response);