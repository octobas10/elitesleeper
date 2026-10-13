<?php
include("class/dbClass.php");
include("includes/connect.php");
include("class/user.php");

$id=$_REQUEST['id'];
$email=$_REQUEST['p'];

$obj=new user();
$result_final = $obj->email_exists($id,$email);
echo $result_final;
//echo "<font color='red'>".$result_final."</font>";

?>
