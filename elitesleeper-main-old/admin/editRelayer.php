<?php
include("class/relayer.php");

if(isset($_GET['edit'])){
	$userObj = new relayer();
	$sql=$userObj->relayerSelectById($_GET['id']);
	$dt=mysqli_fetch_array($sql);
}

if(isset($_POST['submit_edit'])){
	unset($_POST['submit_edit']);
	$data=$_POST;
	$data_obj = new relayer();
	if(!empty($data)) $data_obj->relayerUpdate($data,$_GET['id']);
  // $_SESSION['msg']="<font class='msg_success'>Record has been updated successfully.</font>";
   header('location: home.php?mode=emailrelayeradmin&msg=edit');
}

?>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Edit Newsletter</td>
   </tr>
   <tr>
	   <td>

	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;">

            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
				<!--<tr><td style="color:red;"><?=$msg?><?php $msg="";?></td></tr>-->
				<tr>
                <td align="center" colspan="2" >
<form id='edituser' name="frm1" method="post" action="" onsubmit="return validateForm()" enctype="multipart/form-data">

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
<tr><td class="text_heading" colspan="2">&nbsp;</td></tr>
<tr>
<td><label>Relayer:</label></td><td align="left"><?php echo $dt['relayer']?></td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td><label>From Email:</label></td><td align="left"><input id="fromemail" class="textbox" type="text" name="fromemail" size="103" value="<?php echo htmlspecialchars($dt['fromemail'])?>"></td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td><label>Reply Email:</label></td><td align="left"><input id="replyemail" class="textbox" type="text" name="replyemail" size="103" value="<?php echo htmlspecialchars($dt['replyemail'])?>"></td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td><label>Return Email:</label></td><td align="left"><input id="returnemail" class="textbox" type="text" name="returnemail" size="103" value="<?php echo htmlspecialchars($dt['returnemail'])?>"></td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td><label>Send (Every N days):</label></td><td align="left">
	<select id="sendday" name="sendday">
		<?php for($i=1;$i<=100;$i++){ ?><option <?php if($i==$dt['sendday']) echo "selected='selected'"; echo ">$i</option>";}?>
	</select></td>
<!--<input id="sendday" class="textbox" type="text" name="sendday" size="103" value="<?php echo $dt['sendday']?>"></td>-->
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td><label>Send Delay (sec/email):</label></td><td align="left"><input id="delay" class="textbox" type="text" name="delay" size="103" value="<?php echo $dt['delay']?>"></td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td><td><input type="submit" class="default_btn" name="submit_edit" value="Update" ></td>
</tr>
</table>
</form>
</td>
</table>
</td>
</tr>
</table>
<script language="javascript">
function validateForm(){
	var x=document.forms["frm1"]["subject"].value;
	if (x==null || x==""){
		alert("First subject must be filled out");
		return false;
	}

	var x=document.forms["frm1"]["details"].value;
	if (x==null || x==""){
		alert("First detail must be filled out");
		return false;
	}
}
</script>
