<?php
include("class/dbClass.php");
include("includes/connect.php");
include("class/country.php");


$id=$_REQUEST['p'];

$obj=new country();
$result_final = $obj->fetchRow($id);
echo $result_final;
//echo "<font color='red'>".$result_final."</font>";

?>
