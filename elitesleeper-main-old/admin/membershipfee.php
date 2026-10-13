<?php
include("class/membershipfee.php");
if(isset($_POST['submitfee']) && isset($_POST['mcharge']))
{
	$data = array();
	$feeObj = new membershipfee();
	$data['fee'] = trim($_POST['mcharge']);
	if($feeObj->updateRecord($data,'id','1'))	
		$msg = "Membership charge updated successfully";	
	else
		$msg = "Membership charge not updated";
	unset($data);
	unset($feeObj);
}
?>

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
		<td align="left" class="text_heading">Manage Membership Fees</td>
   </tr>
   <tr>
	   <td>
		   <form name="membershipfee" action="" method="POST">
			   <table cellpadding="0" cellspacing="5" border="0" width="80%" style="padding:20px 0 0 0;">
				   <tr><td><label for="Fees">Membership Charge</label></td>
				   <td><input type="text" name="mcharge" value="<?=$mcharge?>">
				   <input type="submit" name="submitfee" value="Set Membership Charge" onclick="javascript:return formCheck()">
				   </td></tr>
			   </table>
		   </form>
	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;">        
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
			<tr><td style="color:red;"><?=$msg?><?php $msg="";?></td></tr>
			<tr>
			<td align="center" colspan="2" >
			<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class='admin-table'>
				<tr>					
				<th  width='100%' align="left">
				Membership Charge
				</th>
				</tr>
				<?php			
				$feeObj = new membershipfee();
				$feeResult = $feeObj->fetchAll();
				if(mysqli_num_rows($feeResult))
				{
					while($feeRecord = mysqli_fetch_assoc($feeResult))
					{
						 
					?>
					<tr >					
					<td align="left"><?php echo "$".$feeRecord['fee'];?></td>
					</tr>
					<?php
					}
				}
				
		?>
		</table>
      </td>
	  </tr>
	</table>
</td>
</tr>
</table>

<script language="javascript">
function formCheck(){
	var form = document.forms['membershipfee'];
	if(form.mcharge.value=="")
	{
		alert("Please enter a fees");
		return false;
	}

	if(form.mcharge.value!="")
	{
		if(!/^[0-9]+([\.]?[0-9]+){0,1}$/.test(form.mcharge.value))
		{
			alert("Please enter only float data");
			return false;
		}
	}

	return true;

}

</script>