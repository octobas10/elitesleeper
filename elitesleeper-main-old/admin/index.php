<?php
//error_reporting(1);
//ini_set('display_errors', 1);  
ob_start();
session_start();

include("config.php");
include("class/dbClass.php");
include("includes/connect.php");
include("class/admin.php");

$title='Elitesleeper | Admin';
//include("class/adminclass.php");
$error='';
if(isset($_POST['btnsub'])){
	$mUser_id = $_POST['txtUserName'];
	$mPassword = $_POST['txtPassword'];
	//-->create my error object! 
	if(isset($mUser_id) && isset($mPassword)){
		if($mUser_id=="" && $mPassword==""){
			$error = "Please enter Admin ID and Password";
		}
		if($error==""){
			$mUser_id = htmlspecialchars($mUser_id);
			$mPassword = htmlspecialchars($mPassword);
			$adminObj=new admin();
			// Call the registerUser() method, passing in the required variables
			if($adminObj->loginUser($mUser_id, $mPassword)){
			echo "<script language=javascript>window.location.href='home.php'</script>";
			}
			else{
				$error="sorry wrong username or password";
			}
		}
		
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?= $title?></title>
<link href="css/justin_ryan.css" rel="stylesheet" type="text/css" />

</head>

<!--<body onload="MM_preloadImages('../images/join-the-tribe-button-hover.gif')" style="background-color:#FFFFFF;">-->
<table width="1000" height="393" border="0" align="center" cellpadding="0" cellspacing="0">
 
  
  <tr>
    <td align="center" class="login-back">	
	<table>
	<tr>
    <td align="center" valign="top" style="font-size: 32px;font-weight: 500;letter-spacing: 1.5px; color: #fff; font-family: Verdana,Arial,Helvetica,sans-serif;">
	EliteSleeper
	</td>
	
  </tr>
  <tr>
    <td align="center" valign="top">
	 &nbsp;
	</td>
	
  </tr>
	</table>
	
	<table>
	<tr>
    <td>
    	<form method="POST" action="index.php">
  <table border="0" cellpadding="5" cellspacing="0" class="tab_bor login_sec">
    
     <?php if($error!=""){?>
     <tr>
      <td colspan="2" style="color:#e65656; font-style:normal; font-size:15px; padding-bottom: 10px;"><?php echo $error;$error="";?></td>
     </tr><?php }?>
    <tr>
      <td width="100%" align="left" style="color:#FFFFFF; font-size:13px;">Admin ID&nbsp;:&nbsp;&nbsp;</td>
	</tr>
	<tr>
      <td width="100%" align="left" valign="middle"><INPUT type="text" class="text_box" name="txtUserName"></td>
    </tr>
    <tr>
      <td width="100%" align="left" style="color:#FFFFFF; font-size:13px;">Password&nbsp;:&nbsp;&nbsp;</td>
	</tr>
	<tr>
      <td width="100%" align="left" valign="middle"><INPUT type="password" class="text_box" name="txtPassword"></td>
    </tr>
   
    <tr>
      <td colspan="2" height="10"></td>
    </tr>
    <tr>
      <td width="100%" align="left"><input type="submit" name="btnsub" class="" value="Submit">
      </td>
    </tr>
    </table>
	
 </form>
  </td>
  </tr>
 </table>
 
 
 
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?php include('includes/footer.php')?>
						
</table>


</body>
</html>