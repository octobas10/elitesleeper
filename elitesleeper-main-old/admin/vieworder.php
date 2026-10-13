<?php 
/**
 * @author        Anil Kumar
 * @created       17/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
date_default_timezone_set('Asia/calcutta');
include("class/order.php");
include("class/country.php");

?>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" >
   <tr>
      <td align="left" colspan="0" class="text_heading">View Order</td>
   </tr>
   <tr>
      <td align="left" colspan="2" style="padding-top:10px;">         
            <table width="50%" border="0" align="left" cellpadding="0" cellspacing="5">
				<tr>
					<td>

						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="viewdetail">
							<?php
							$obj=new order();
							$obj2= new country();

							$data=$obj->fetchAll("where elitesleeper_customer.id=".$_GET['id']."");
							//print_r($data);exit;
							$row=mysqli_fetch_assoc($data);
							
						 	$firstname = ucfirst(stripslashes($row['first_name']));
							$lastname = ucfirst(stripslashes($row['last_name']));
							$bill_country = ucfirst(stripslashes($row['bill_country']));
							$phone= ucfirst(stripslashes($row['bill_phone']));
							$password= $row['password'];
							$creation_date = ucfirst(stripslashes($row['creation_date']));
							$email = stripslashes($row['email_id']);

							$bill_state = stripslashes($obj2->fetchState($row['bill_state']));

							$bill_city = stripslashes($row['bill_city']);
							$bill_street = stripslashes($row['bill_street']);
							$bill_zip = stripslashes($row['bill_zip']);
							$ship_street = stripslashes($row['ship_street']);
							$ship_city = stripslashes($row['ship_city']);
							
							$ship_country = stripslashes($row['ship_country']);
							$ship_state = stripslashes($obj2->fetchState($row['ship_state']));
							$ship_zip = stripslashes($row['ship_zip']);
							$status = stripslashes($row['status']);
							
							$payment_date = stripslashes($row['payment_date']);
							$transaction_id = stripslashes($row['transaction_id']);
							$payment_amount = stripslashes($row['payment_amount']);
							$payment_nature = stripslashes($row['payment_nature']);


						 	//$obj->select($view_view);
					?>

					
					 <tr>
						<th valign="top" >First name:</th>
						<td  align="left" valign="top"><?=$firstname?></td>
                  	</tr>
					<tr >
						<th valign="top" >Last name:</th>
						<td  valign='top' ><?=$lastname?></td>
					</tr>
                    <tr>
						<th valign="top"  >Email:</th>
						<td  align="left" valign="top" ><?=$email?></td>
                  	</tr>
					
					<tr >
					  <th valign="top" >Password:</th>
						<td valign='top'  ><?=$password?></td>
					</tr>
                   
				
					<tr >
						<th valign="top" >Order date:</th>
						<td  valign='top' ><?=$creation_date?></td>
					</tr>
					<tr >
						<th valign="top" >Phone:</th>
						<td  valign='top' ><?=$phone?></td>
					</tr>
					<tr >
						<th valign="top" colspan="2" align="center">Billing  Address</th>
						
					</tr>
					
					 <tr >
						<th valign="top" >Country:</th>
						<td  valign='top' ><?php echo $bill_country==1?'United State':'Canada';?></td>
					</tr>

					<tr >
						<th valign="top" >State:</th>
						<td  valign='top' ><?=$bill_state?></td>
					</tr>
					<tr >
						<th valign="top" >City:</th>
						<td  valign='top' ><?=$bill_city?></td>
					</tr>
					<tr >
						<th valign="top" >Street:</th>
						<td  valign='top' ><?=$bill_street?></td>
					</tr>
					<tr >
						<th valign="top" >Zip:</th>
						<td  valign='top' ><?=$bill_zip?></td>
					</tr>
					
					<tr >
						<th valign="top" colspan="2" align="center">Shiping  Address</th>
						
					</tr>
					
					<tr >
						<th valign="top" >Ship country:</th>
						<td  valign='top' ><?php echo $ship_country==1?'United State':'Canada';?></td>
					</tr>
					<tr >
						<th valign="top" >Ship state:</th>
						<td  valign='top' ><?=$ship_state?></td>
					</tr>
					<tr >
						<th valign="top" >Ship city:</th>
						<td  valign='top' ><?=$ship_city?></td>
					</tr>
					<tr>
						<th valign="top" >Ship street:</th>
						<td  valign='top' ><?=$ship_street?></td>
					</tr>
					<tr >
						<th valign="top" >Ship zip:</th>
						<td  valign='top' ><?=$ship_zip?></td>
					</tr>
					<tr >
						<th valign="top" colspan="2" align="center">Payment Details</th>
						
					</tr>
					<tr >
						<th valign="top" >Payment Date:</th>
						<td  valign='top' ><?=$payment_date?></td>
					</tr>
					<tr >
						<th valign="top" >Transaction Id:</th>
						<td  valign='top' ><?=$transaction_id?></td>
					</tr>
					<tr >
						<th valign="top" >Payment Amount:</th>
						<td  valign='top' ><?=$payment_amount?></td>
					</tr>
					<tr >
						<th valign="top" >Payment Type:</th>
						<td  valign='top' ><?=$payment_nature?></td>
					</tr>

					<tr >
						<th valign="top" >Status:</th>
						<td  valign='top' ><?php if($status==1)echo "<font color='green'>Complete";else echo"<font color='red'> Pending";?></td>
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

