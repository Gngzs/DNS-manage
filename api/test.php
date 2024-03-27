<?php
require_once("basic.php");
require_once("main.php");

echo $_SERVER['DOCUMENT_ROOT'];
echo json_encode(getServerList());