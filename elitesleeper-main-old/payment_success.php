<?php
session_start();
if(isset($_REQUEST['flag']) && $_REQUEST['flag']=="true" && isset($_REQUEST['customer_id'])){	
	
		$id = $_REQUEST['customer_id'];
		$_SESSION['cid'] = $id;
		$_SESSION['payment_success']='true';
		echo "<script language='javascript'>window.location='http://www.elitesleeper.com/index.php?mode=thankyou'</script>";
}	
	else{
		echo "<script language='javascript'>alert('Sorry! Your Payment is unsuccessfull');window.location='APIError.php';</script>";
	}

?>