<?php

require_once("../common/basic.php");
require_once("main.php");
require_once("../common/totp.php");
require_once("setting.php");


// 设置返回类型为JSON
header('Content-Type: application/json');
//session_start();


if (isset($_POST['action'])) {
    if (xxs($_POST)) {
        echo json_encode(array('status' => 'xxs', 'message' => '请勿包含<, >, &, \, /, (, ), {, }等特殊符号'));
    } else {
        if (isset($_COOKIE['Authorization'])) {
            $checklogin = validateJWT($_COOKIE['Authorization']);
            if ($checklogin) {
                $message = "";
                $action = $_POST['action'];
                switch ($action) {
                    case 'getServerList':
                        $res = getServerList($_POST['offset'], $_POST['limit']);
                        $message = array("total" => $res[1][0]['COUNT(*)'], "rows" => $res[0]);
                        break;
                    case "checkServerStatus":
                        $message = checkServerStatus($_POST["server"], $_POST["secretid"], $_POST["secretkey"]);
                        break;
                    case "edit_server":
                        $message = editServer($_POST['sql_id'], $_POST['servername'], $_POST['server'], $_POST['secretid'], $_POST['secretkey']);
                        break;
                    case 'del_server':
                        $message = delServer($_POST['sql_id']);
                        break;
                    case 'able_server':
                        $message = ableServer($_POST['sql_id'], $_POST['now_able']);
                        break;
                    case 'add_server':
                        $message = addServer($_POST['name'], $_POST['provider'], $_POST['secretid'], $_POST['secretkey']);
                        break;
                    case "getDomainList":
                        $res = getDomainList($_POST['offset'], $_POST['limit']);
                        $message = array("total" => $res[1][0]['COUNT(*)'], "rows" => $res[0]);
                        break;
                    case "getDomainDnsList":
                        $res = getDomainDnsList($_POST['domain'], $_POST['location'], $_POST['servername'], $_POST['offset'], $_POST['limit'], $_POST['zoneid']);
                        $message = $res;
                        break;
                    case "getServerName":
                        $message = getServerName();
                        break;
                    case "add_domain":
                        $message = addDomain($_POST['domain'], $_POST['serverid'], $_POST['zoneid']);
                        break;
                    case "del_domain":
                        $message = delDomain($_POST['sql_id']);
                        break;
                    case "updateRecord":
                        $message = updateRecord(
                            $_POST['domain'],
                            $_POST['hostRecord'],
                            $_POST['recordType'],
                            $_POST['lineType'],
                            $_POST['recordValue'],
                            $_POST['weight'],
                            $_POST['ttl'],
                            $_POST['remark'],
                            $_POST['status'],
                            $_POST['proxy'],
                            $_POST['recordid'],
                            $_POST['zoneid']
                        );
                        break;
                    case "creatRecord":
                        $message = creatRecord($_POST['domain'], $_POST['hostRecord'], $_POST['recordType'], $_POST['lineType'], $_POST['recordValue'], $_POST['weight'], $_POST['ttl'], $_POST['remark'], $_POST['status'], $_POST['proxy'], $_POST['zoneid']);
                        break;
                    case "deletRecord":
                        $message = deletRecord($_POST['domain'], $_POST['recordid'], $_POST['zoneid']);
                        break;
                    case "index":
                        $message = index();
                        break;
                    case "newTotp":
                        $message = webset::newTotp($_POST['username'], $_POST['code']);
                        break;
                    case 'offTotp':
                        $message = webset::offTotp($_POST['username']);
                        break;
                    case "webConfig":
                        $message = webset::getWebConfig($_POST['username']);
                        break;
                    case 'updateAdminPassword':
                        $message = webset::updateAdminPassword($_POST['oUsername'], $_POST['oPassword'], $_POST['nPassword'], $_POST['nUsername']);
                        break;
                }
                echo json_encode($message);
            } else {
                echo json_encode(array('status' => 'logout', 'message' => '登录失效，请重新登录'));
            }
        } else {
            echo json_encode(array('status' => 'logout', 'message' => '登录失效，请重新登录'));
        }
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'newCaptcha':
            $code = generateCaptchaImage();
            break;
        case 'newTotpQrcode':
            $secret = ymtotp::CreatSecret();
            //setcookie("totpSecret", $secret, time() + 600, "/");
            $_SESSION['totpSecret'] = $secret;
            $qrcode = ymtotp::CreatQR($_GET['username'], $secret);
            break;
    }
}
