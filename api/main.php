<?php
require_once("basic.php");
require_once("server/tencent.php");
require_once("server/ali.php");

//查询服务商列表
function getServerList($offset=0,$limit=10) {
    $sql="SELECT * FROM list_server LIMIT ".$offset.",".$limit;
    return executeQuery($sql);
}
//检查与服务商的连接
function checkServerStatus($server,$secretid,$secretkey){
    switch($server){
        case "腾讯云":
            $res=tencent\checkServerStatus($secretid,$secretkey);
            break;
        case "阿里云":
            $client=ali\api::Init($secretid,$secretkey,);
            $res=ali\api::DescribeDomains($client);
            break;
    }
    return $res;
}
