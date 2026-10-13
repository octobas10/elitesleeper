<?php 

include("admin/class/newsletter.php");
include("admin/class/dbClass.php");
include("admin/includes/connect.php");
include("admin/config.php");
/*--------------------------The USERS INFO Comming From other site --------------------------------------*/
$firstname=$_REQUEST['firstname'];
$email=$_REQUEST['email'];
if(isset($firstname)) {
if(!empty($email)){
	
if($firstname && $email!='0' ){

$obj_coregeusers= new pages();

$coreuserdata = array();
    @extract($_REQUEST);
	$coreuserdata['name']=$firstname;
	$coreuserdata['lastname']=$lastname;
	$coreuserdata['address']=$address;
	$coreuserdata['email']=$email;
	$coreuserdata['city']=$city;
	$coreuserdata['state']=$state;
	$coreuserdata['zip']=$zip;
	$coreuserdata['country']=$country;
	$coreuserdata['workphone']=$workphone;
	$coreuserdata['cellprefix']=$cellprefix;
	$coreuserdata['dob']=$dob;
	$coreuserdata['gender']=$gender;
	$coreuserdata['ip']=$ip;

 $sql_erev=mysqli_query($GLOBALS['conn'],"select email from elitesleeper_coreg_users where email='".$email."'");
		$result=mysqli_num_rows($sql_erev);
	
		if($result!='1'){
$coregusers=$obj_coregeusers->coregusersinsert($coreuserdata);
echo "Accepted";
	}else{
	echo "Rejected";
	}
	}
 }  else { 
	 echo "Rejected";
	} }
	/*--------------------------The USERS INFO Comming From other site --------------------------------------*/
?>







