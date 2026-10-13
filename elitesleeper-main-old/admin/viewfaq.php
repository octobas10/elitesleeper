<?php 
/**
 * @description   This used to manage product like title,price,shipping charge
 * @author        Anil Kumar
 * @created       17/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
date_default_timezone_set('Asia/calcutta');
include("class/faq.php");
?>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" >
   <tr>
      <td align="left" colspan="0" class="text_heading">FAQ Detail</td>
   </tr>
   <tr>
      <td align="left" colspan="2" style="padding-top:10px;">         
            <table width="90%" border="0" align="left" cellpadding="0" cellspacing="5">
				<tr>
					<td>

						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="viewdetail">
							<?php
							$obj = new Faq();
						
							$data=$obj->fetchRow("where id=".$_GET['id']."");
							$row=mysqli_fetch_array($data);
														
						 	$ques = ucfirst(stripslashes($row['ques']));
							$ans = ucfirst(stripslashes($row['ans']));
							$create_date = $row['create_date'];
							$status = $row['status'];
					?>
					 <tr>
						<th valign="top" width='100px' align='right'>Question :</th>
						<td  align="left" valign="top"><?=$ques?></td>
                  	</tr>
					<tr >
						<th valign="top" align='right'>Answer :</th>
						<td  valign='top' ><?=$ans?></td>
					</tr>
                    
					<tr >
						<th valign="top" align='right'>Publish date :</th>
						<td  valign='top' ><?=$create_date?></td>
					</tr>
					
					<tr >
						<th valign="top" align='right'>Status :</th>
						<td  valign='top' ><?php echo $status==1 ? "Published" : "Unpublished";?></td>
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

