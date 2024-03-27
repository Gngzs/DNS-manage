<?php

require_once("basic.php");
require_once("main.php");


// 设置返回类型为JSON
header('Content-Type: application/json');


if (isset($_POST['action'])) {
    if (isset($_COOKIE['Authorization'])) {
        $checklogin = validateJWT($_COOKIE['Authorization']);
        if ($checklogin) {
            $message = "";
            $action = $_POST['action'];
            switch ($action) {
                case 'getServerList':
                    $res = getServerList($_POST['offset'],$_POST['limit']);
                    $message = array("total"=>count($res),"rows"=>$res);
                    break;
                case "checkServerStatus":
                    $message=checkServerStatus($_POST["server"],$_POST["secretid"],$_POST["secretkey"]);
                    break;
            }
            echo json_encode($message);
        } else {
            echo json_encode(array('status' => 'error', 'message' => '登录失效，请重新登录'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => '登录失效，请重新登录'));
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'newCaptcha':
            $code = generateCaptchaImage();
    }
}
