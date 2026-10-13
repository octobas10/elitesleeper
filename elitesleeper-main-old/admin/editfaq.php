<?php
include("class/faq.php");
include("class/country.php");
$id = $_REQUEST['id'];

if(isset($_POST['submit'])){	
	$data = array();
	@extract($_POST);
	$data['ques'] = addslashes(trim($ques));
	$data['ans'] = addslashes(trim($ans));
	$data['create_date'] = trim($create_date);	
	$data['status'] = addslashes(trim($status));
	
	$userObj  = new Faq();
	if($userObj->updateFaq($data,'id',$id))
	{
		$msg = "Updated Successfully";
		header("location:home.php?mode=managefaq");			
	}
	else
	{
		$msg = "Sorry! could not updated successfully";
	}
 } else {
	$userObj = new Faq();
	$userResult = $userObj->fetchAll(" WHERE id='$id'");
	
	if(mysqli_num_rows($userResult)){	
		//@extract(mysqli_fetch_assoc($userResult));
		
		$row=mysqli_fetch_array($userResult);
		$ques = $row['ques'];
		$ans = $row['ans'];								
		$status = stripslashes($row['status']);		
		$create_date = $row['create_date'];
	}
}
?>
<head>
<script language= "javascript">
function check(){ 
		var chk=0;
		if(document.getElementById('ques').value=="")
		{
			document.getElementById("div_ques").style.display="block";
			document.getElementById('div_ques').innerHTML="<font color='red' size='2'> Please enter the  question </font>";
			document.getElementById("ques").focus();
			chk++;
		}
		else
		{				
			document.getElementById("div_ques").style.display="none";

		}
		if(document.getElementById('ans').value=="")
		{
			document.getElementById("div_ans").style.display="block";
			document.getElementById('div_ans').innerHTML="<font color='red' size='2'> Please enter the  answer </font>";
			document.getElementById("ans").focus();
			chk++;
		}
		else
		{				
			document.getElementById("div_ans").style.display="none";
		}
		if(document.getElementById('create_date').value=="")
		{
			document.getElementById("div_create_date").style.display="block";
			document.getElementById('div_create_date').innerHTML="<font color='red' size='2'> Please enter the  publish date  </font>";
			document.getElementById("create_date").focus();
			chk++;
		}
		else
		{				
			document.getElementById("div_create_date").style.display="none";

		}
		if(chk>0){
			return false;
		}
		else{
			return true;
		}
}
</script>

<script type="text/javascript" src="js/valid.js"></script>


</head>
<form name='edituser' id='edituser' action="<?php $_SERVER['PHP_SELF']?>" method='post' onSubmit="return check();">
	
	<input type="hidden" name="id" value="<?=$id?>" />
	<input type="hidden" name="chk" id="chk" value="1" />
	
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="padding-bottom:54px;" class="admin-table">
    <tr><td colspan="4"class="text_heading">
	Edit FAQ
			</td></tr>
    <tr><td style="color:red;"><?php echo $msg;$msg="";?></td></tr>
    <tr><td align="left" colspan="2" style="padding-top:10px; padding-left:20px" class='admin_text'>
	<table border='0' width='100%'>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr>
				<td>Question</td>
				<td>
				<input class='textbox' type='text' id='ques' name='ques' value="<?=$ques?>" size="70"><div id="div_ques"></div></td>
			</tr>
			<tr>
				<td>Answer</td>
				<td><textarea id='ans' name='ans' cols="60" rows="12"> <?=$ans?></textarea><div id="div_ans"></div></td>
			</tr>
			<tr>
				<td>Publish Date</td>
				<td>
				<table>
					<tr>
						<td><input class='textbox' type='text' id='create_date' name='create_date' value="<?=$create_date?>" onclick="javascript:this.value=''"></td>
						<td><img src="cal/calender_ico.gif" alt="" width="20" height="23" border="0"  onclick="showCalendarControl(document.getElementById('create_date'));" style="cursor:pointer;"  /></td>
					</tr>
				</table>
				<div id="div_creation_date"></div></td>
			</tr>
			<tr>
				<td>Status</td>
				<td><input class='textbox' type='radio' name='status' value="0" <?php if($status=='0')echo "checked"?>>UnPublished
					<input class='textbox' type='radio' name='status' value="1" <?php if($status=='1') echo "checked"?>>Published		  </td>
			</tr>
			<tr>				
				<td>&nbsp;</td>
				<td><input type='submit' value='Submit' name='submit' id="submit_button"></td>
			</tr>			
		</table>
	
    </td>
    </tr>
    </table>
</form>
<script type="text/javascript" src="cal/calendarcontrol.js"></script>
<link type="text/css" href="cal/CalendarControl.css" rel="stylesheet" />		
