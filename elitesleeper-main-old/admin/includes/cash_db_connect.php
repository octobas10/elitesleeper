<?php 
session_start();
if(!isset($_SESSION["ADMIN_NAME"]) && !isset($_SESSION["ADMIN_ID"])){
		$_SESSION["login"]='false';
}
define('DBHOST', 'localhost'); 
define('DBUSER', 'brain-www');
define('DBPASS', 'wA76mEyUwr');
define('DBNAME', 'db_elitecashmachine');
$conn = mysqli_query($GLOBALS['conn'],DBHOST, DBUSER, DBPASS) or die("Could not connect: " . mysql_error());
mysql_selectdb(DBNAME, $conn) or die ("Cannot select your database : " . mysql_error());
define("LEAD_EMAIL","test@test.co.uk");


class UsersuprissionCash {

	var $dbUserTable = 'emate_supression';


	function supressionusers($site_id) {
	
        $result=mysqli_query($GLOBALS['conn'],"SELECT * FROM $this->dbUserTable WHERE site_id=".$site_id);

         return $result;
	
	} 


}

?> 
