<?php
include("class/admin.php");
if(!isset($_SESSION["ADMIN_ID"])){
	echo "<script language='javascript'>window.parent.location= 'index.php'</script>";
}
if(isset($_POST['update'])){
	$oldpassword =$_POST['oldpassword'];
	$password = trim($_POST['password']);
	$adminObj = new admin();
	
	if($adminObj->passwordValid($oldpassword)>0)
	{
		if($adminObj->setPassword($password))
			$msg = "Password updated successfully";
		else
			$msg = "Sorry password could not updated successfully";
	}
	else
	{
		$msg="Old password does not match";
	}

}
?>


<form method="POST" action="home.php?mode=changepassword" onsubmit="return validate();">
	<table width="61%" border="0" cellpadding="5" cellspacing="0" class="tab_bor tab_pass"  style="background-color:#FFFFFF">
	<?php if(isset($msg)){ 
						if($msg!=''){?>
				<tr>
					<td style="color:#FF0000;">&nbsp;</td>
				    <td style="color:#FF0000;"><?php echo $msg;?></td>
				</tr><?php }} ?>
     <tr>
	 <tr>
		<td class="text_heading" colspan="2">
		<strong>Change Password</strong>
		</td>
	</tr>
      <td width="40%" align="right" style="color:#000000;"><strong>Old Password&nbsp;:&nbsp;&nbsp;</strong></td>
      <td width="40%" align="left" valign="middle"><INPUT type="password" class="text_box" name="oldpassword" id="oldpassword" value="">
	   <div id="oldpass_div" style="display:none;"><font color="red">Please enter the old password</font></div></td>
    </tr>
	
	 <tr>
      <td width="40%" align="right" style="color:#000000;"><strong>New Password&nbsp;:&nbsp;&nbsp;</strong></td>
      <td width="40%" align="left" valign="middle"><INPUT type="password" class="text_box" name="password" id="password">
	   <div id="pass_div" style="display:none;"><font color="red">Please enter the password</font></div></td>
    </tr>
	
	
      <td width="40%" align="right" style="color:#000000;"><strong>Confirm Password&nbsp;:&nbsp;&nbsp;</strong></td>
      <td width="60%" align="left" valign="middle"><INPUT type="password" class="text_box" name="repassword" id="repassword">
	   <div id="repass_div" style="display:none;"><font color="red">Please enter the password</font></div></td>
    </tr>
   
    <tr>
      <td colspan="2" height="10"></td>
    </tr>
    <tr>
      <td width="40%">&nbsp;</td>
      <td width="60%" align="left"><input type="submit" id="update" name="update" class="default_btn" value="Update"> </td>
    </tr>
    </table>
</form>

</td>


		
<script language="javascript" type="text/javascript">
	function validate(){
		var check=0;
		if(document.getElementById('oldpassword').value==''){
				document.getElementById('oldpass_div').innerHTML="<font color=red>Please enter the old password.</font>";
				document.getElementById('oldpass_div').style.display='block';
				check=1;
			}else{
			document.getElementById('oldpass_div').innerHTML="";
			}
		if(document.getElementById('password').value==''){
				document.getElementById('pass_div').innerHTML="<font color=red>Please enter the new password.</font>";
				document.getElementById('pass_div').style.display='block';
				check=1;
			}else{
			document.getElementById('pass_div').innerHTML="";
			}
			if(document.getElementById('password').value!=''){
				if(document.getElementById('repassword').value==''){
					document.getElementById('repass_div').innerHTML="<font color=red>Please enter the confirm password .</font>";
					document.getElementById('repass_div').style.display='block';
					check=1;
			 	}
				else if(document.getElementById('password').value!=document.getElementById('repassword').value){
					document.getElementById('repass_div').innerHTML="<font color=red>Password does't match.</font>";
					document.getElementById('repass_div').style.display='block';
					check=1;
				}else{
					document.getElementById('repass_div').innerHTML="";
				}
				
			}
			if(check==1){
					return false;
			}
			return true;
	}
</script>
