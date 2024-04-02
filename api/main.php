<?php
require_once("basic.php");
require_once("server/tencent.php");
require_once("server/ali.php");
require_once("server/cf.php");

//查询服务商列表
function getServerList($offset = 0, $limit = 10)
{
    $sql = "SELECT * FROM list_server LIMIT " . $offset . "," . $limit;
    return executeQuery($sql);
}
//检查与服务商的连接
function checkServerStatus($server, $secretid, $secretkey)
{
    switch ($server) {
        case "腾讯云":
            $res = tencent\checkServerStatus($secretid, $secretkey);
            break;
        case "阿里云":
            $client = ali\api::Init($secretid, $secretkey,);
            $res = ali\api::DescribeDomains($client);
            break;
        case "Cloudflare":
            $res = cf\Checktoken($secretid);
            break;
    }
    return $res;
}
//编辑服务商
function editServer($id, $servername, $server, $secretid, $secretkey)
{
    $sql = "UPDATE `list_server` SET `servername` = '" . $servername . "', `server` = '" . $server . "', `secretid` = '" . $secretid . "', `secretkey` = '" . $secretkey . "' WHERE `list_server`.`id` = " . $id;
    return executeQuery($sql);
}

//删除服务商
function delServer($id){
    $sql = "DELETE FROM list_server WHERE `list_server`.`id` = ". $id ;
    return executeQuery($sql);
}

//启用/禁用服务商
function ableServer($id,$now_able) {
    if ($now_able == "1"){
        $sql = "UPDATE `list_server` SET `enable` = '0' WHERE `list_server`.`id` = ".$id;
    }elseif( $now_able == "0"){
        $sql = "UPDATE `list_server` SET `enable` = '1' WHERE `list_server`.`id` = ".$id;
    }
    return executeQuery($sql);
}