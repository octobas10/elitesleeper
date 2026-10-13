<?php 
/**
 * @author        Anil Kumar
 * @created       18/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
date_default_timezone_set('Asia/calcutta');
include("class/payment.php");
?>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" >
   <tr>
      <td align="left" colspan="0" class="text_heading">User Detail</td>
   </tr>
   <tr>
      <td align="left" colspan="2" style="padding-top:10px;">         
            <table width="50%" border="0" align="left" cellpadding="0" cellspacing="5">
				<tr>
					<td>

						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="viewdetail">
							<?php
							$obj=new payment();
					
							$data=$obj->fetchAllWithParent(" where p.id=".$_REQUEST['id']."");
						 	
							$row=mysqli_fetch_assoc($data);
						 	$fullname = ucfirst(stripslashes($row['username']));
							$t_id = ucfirst(stripslashes($row['transaction_id']));
							$p_date= ucfirst(stripslashes($row['payment_date']));
							$card_ex_date = ucfirst(stripslashes($row['expiration_date']));
							$status = ucfirst(stripslashes($row['status']));
							$s_price = ucfirst(stripslashes($row['subscription_price']));
							
						 	//$obj->select($view_view);
					?>

					
					 <tr>
						<th valign="top" >Name:</th>
						<td  align="left" valign="top"><?=$fullname?></td>
                  	</tr>
                    <tr>
						<th valign="top"  >Transaction Id:</th>
						<td  align="left" valign="top" ><?=$t_id?></td>
                  	</tr>
					
					<tr >
					  <th valign="top" >Payment Date:</th>
						<td valign='top'  ><?=$p_date?></td>
					</tr>
                    <tr >
						<th valign="top" >Card Expiry Date:</th>
						<td  valign='top' ><?=$card_ex_date?></td>
					</tr>
					<tr >
						<th valign="top" >Subscription Price:</th>
						<td  valign='top' ><?=$s_price?></td>
					</tr>
					<tr >
						<th valign="top" >Status:</th>
						<td  valign='top' ><?=$status?></td>
					</tr>
					
					
					<tr >
						<th valign="top" align="left"></th>
						<td  valign='top'align="left" >
						<input type="button" onclick="javascript:history.back();" name="" value="  Back  ">
						</td>
					</tr>
					
                  	</table>

					</td>
				</tr>
        
                

</table>
</form>
</td>
</tr>
</table>

