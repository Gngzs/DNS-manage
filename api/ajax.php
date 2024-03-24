<?php

include("basic.php");


// 设置返回类型为JSON
header('Content-Type: application/json');


if (isset($_POST['action'])) {
    if (isset($_COOKIE['Authorization'])) {
        $checklogin = validateJWT($_COOKIE['Authorization']);
        if ($checklogin) {
            $message = "";
            $action = $_POST['action'];
            switch ($action) {
            }
            echo json_encode((array('status' => 'success', 'message' => $message)));
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
