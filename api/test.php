<?php
require_once("basic.php");
require_once("main.php");
/*
$sql = "SELECT * FROM list_domain";
$sqlres = executeQuery($sql);

print_r($sqlres);
*/
print_r(getDnsRecordsNumber("yinmai.asia","腾讯云测试","腾讯云"));