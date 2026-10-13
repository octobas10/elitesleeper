<?php
/**
 * @author        Anil Kumar
 * @created       17/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
include("class/newsletter.php");
include("class/paging.php");
if(isset($_GET['view'])){
	$userObj = new pages();
	$sql=$userObj->bannerlistSelectById($_GET['id']);
	$dt=mysqli_fetch_array($sql);
}
	
			
?>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="center" class="text_heading">Preview </td> <td align="center" class="text_heading"><a href="javascript:history.go(-1)" style="color:red;font-size:14px;text-decoration:underline;">Back</a></td>
   </tr>
   <tr>
	   <td>
		 
	   </td>
   </tr>
   <tr>
		<td align="left" style="padding-top:10px;">
			<form name='pagemanage' method='post' action="">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
					<tr>
						<td align="center" colspan="2" >
							<form name="frm1" method="post" action="" onsubmit="return validateForm()" enctype="multipart/form-data"  >

							<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
							<tr><td class="text_heading" colspan="2">&nbsp;</td></tr>
							<tr>
							<td align="center" style="padding-right:349px">Subject : <?php echo $dt['subject']?>
							 <!--<a href=''>Click here</a>-->
							</td>
							</tr>
							<tr>
							<td align="center" style="padding-right:349px">Description : <?php echo $dt['description']?>
							 <!--<a href=''>Click here</a>-->
							</td>
							</tr>
							<tr>
							<td>&nbsp;</td><td>&nbsp;</td>
							</tr>

							</table>
							</form>
						</td>
					</tr>
				</table>
			</form>
		</td>
    </tr>
</table>
