<?php 
if(!isset($_SESSION["ADMIN_NAME"]) && !isset($_SESSION["ADMIN_ID"])){
		$_SESSION["login"]='false';
}

$connString = "live";
if($_SERVER['HTTP_HOST'] == 'localhost'){
	$connString = "localhost";
}

function pr($data=array()){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

$adminobj = new DbConnection($connString);//constructor to connect database accept argument biz/live bydefult localhost
if(!$adminobj->connect()){
	die("sorry could not connect to database");
}
?> 
