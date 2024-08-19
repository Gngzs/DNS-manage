<?php

require_once "../common/basic.php";
require_once "../common/totp.php";

//网站设置
class webset
{
    //开启二步验证
    public static function newTotp($username, $code)
    {
        $secret = $_SESSION['totpSecret'];
        if (ymtotp::VerifyCode($secret, $code)) {
            $sql = "UPDATE `admin_user` SET `2fa`=1 ,`2fakey`='$secret' WHERE `admin_user`.`username`='$username'";
            executeQuery($sql);
            return ["status" => "success", "message" => "绑定成功"];
        } else {
            return ["status" => "error", "message" => "验证码错误"];
        }
    }
    //关闭二步验证
    public static function offTotp($username)
    {
        $sql = "UPDATE `admin_user` SET `2fa`=0 WHERE BINARY `admin_user`.`username`='$username'";
        executeQuery($sql);
        return ["status" => "success", "message" => "关闭成功"];
    }

    //获取网站设置
    public static function getWebConfig($username)
    {
        $sql1 = "SELECT * FROM `admin_user` WHERE  BINARY `username`='$username'";
        $res1 = executeQuery($sql1);
        return ["status" => "success", "message" => ["totp" => $res1[0]['2fa']]];
    }

    //修改管理员密码
    public static function updateAdminPassword($oUsername, $oPassword, $nPassword, $nUsername = null)
    {
        if(!webset::validatePassword($nPassword)){
            return ['status'=> 'error','message'=> '密码不少于6位且至少包含数字、英文、特殊符号其中的两种'];
        }
        $md5o = md5($oPassword);
        $md5n = md5($nPassword);
        $login = executeQuery("SELECT * FROM `admin_user` WHERE BINARY `username` = '$oUsername' AND BINARY `password` = '$md5o'");
        if ($login > 0) {
            if ($nUsername == null) {
                $sql1 = "UPDATE `admin_user` SET `password`='$md5n' WHERE BINARY `username`='$oUsername'";
                executeQuery($sql1);
            } else {
                $sql2 = "UPDATE `admin_user` SET `password`='$md5n',`username`='$nUsername' WHERE BINARY `username`='$oUsername'";
                executeQuery($sql2);
            }
            setcookie("Authorization", '', time() - 3600, '/');
            return ["status"=> "success", "message"=> "修改成功，请重新登录"];
        }
        return ["status"=> "error", "message"=> "原密码错误"];
    }

    //验证密码规则
    private static function validatePassword($str) {
        $minLength = 6;
        $hasNumber = preg_match('/\d/', $str);
        $hasLetter = preg_match('/[a-zA-Z]/', $str);
        $hasSpecial = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $str);
    
        $conditions = ($hasNumber ? 1 : 0) + ($hasLetter ? 1 : 0) + ($hasSpecial ? 1 : 0);
    
        return strlen($str) >= $minLength && $conditions >= 2;
    }
}
