<?php
/**
 * @author        Anil Kumar
 * @created       17/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
include("class/paging.php");
include("class/newsletter.php");
$id=$_REQUEST['id'];
$coregObj = new pages();
$data=$coregObj->selectCoregView($id);
$userRecord = mysqli_fetch_assoc($data);
?>

<style>
table.data-detail tr td{   border: 1px solid #CCCCCC;
    border-collapse: collapse;
    padding: 5px; text-transform:capitalize;}
	h2.heading{ color:#000; border-bottom:1px solid #ccc; padding:0 0 5px 0; width:98%; margin:0 0 20px 0;}
</style>
<h2 style="padding-bottom: 20px;" class="heading">Coreg User Detail<a class="default_btn" style="float: right" href="home.php?mode=coregusers">< Back</a></h2>
<table width="60%" class="data-detail new_date_sty" border="0" align="center" class="" cellpadding="0" cellspacing="0">
		<tr>
		 <td align="left" valign="top" class="">First Name</td><td><?=$userRecord['name']?></td> </tr>
		
		<tr>
	   <td align="left" valign="top" class="">Last Name</td><td><?=$userRecord['lastname']?></td> </tr>
	   
	   <tr>
	   <td align="left" valign="top" class="">Email</td><td><?=$userRecord['email']?></td> </tr>
	   
	   <tr>
	   <td align="left" valign="top" class="">Address</td><td><?=$userRecord['address']?></td> </tr>
	   
	   <tr>
	   <td align="left" valign="top" class="">City</td><td><?=$userRecord['city']?></td> </tr>
	   
	   <tr>
	   <td align="left" valign="top" class="">State</td><td><?=$userRecord['state']?></td> </tr>
	   
	   <tr>
	   <td align="left" valign="top" class="">Zip Code</td><td><?=$userRecord['zip']?></td> </tr>
	   
	   <tr>
	   <td align="left" valign="top" class="">Country</td><td><?=$userRecord['country']?></td> </tr>
	   
	   <tr>
	    <td align="left" valign="top" class="">Work Phone</td><td><?=$userRecord['workphone']?></td> </tr>
		
		<tr>
	   <td align="left" valign="top" class="">Home Phone</td><td><?=$userRecord['cellprefix']?></td> </tr>
	   
	   <tr>
	   <td align="left" valign="top" class="">Date of Birth</td><td><?php
	   echo date('m/d/Y',strtotime($userRecord['dob']));
	   ?></td> </tr>
	   
	   
	   
	   <tr>
	    <td align="left" valign="top" class="">Gender</td><td><?php if($userRecord['gender']=='M'){ echo "Male";} else { echo "Female";}?></td> </tr>
		
		<tr>
		 <td align="left" valign="top" class="">Ip Address</td><td><?=$userRecord['ip']?></td> </tr>
		 
		 <tr>
		  <td align="left" valign="top" class="">Lead Date</td><td><?php
		    echo date('m/d/Y',strtotime($userRecord['enterdate']));
		  ?></td> </tr>
 
	
</td></tr></table>

	
