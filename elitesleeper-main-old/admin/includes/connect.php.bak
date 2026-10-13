<?php 
if(!isset($_SESSION["ADMIN_NAME"]) && !isset($_SESSION["ADMIN_ID"])){
		$_SESSION["login"]='false';
}
$adminobj = new DbConnection('live');//constructor to connect database accept argument biz/live bydefult localhost
if(!$adminobj->connect()){
	die("sorry could not connect to database");
}
?> 
