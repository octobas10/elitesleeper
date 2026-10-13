<?php
include("class/order.php");
include("class/paging.php");
$orderby = " order by elitesleeper_customer.id desc";
if(isset($_POST['delete'])){
	
	$userObj = new order();
	if($userObj->removeOrder()){
		$msg = "<font class='msg_success'>Customer deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>Customer Could not deleted ";
	}
	unset($userObj);
}

if(isset($_REQUEST['del']) && isset($_REQUEST['id'])){	
	$userObj = new order();
	if($userObj->trash($_REQUEST['id'])){
		$msg = "<font class='msg_success'>Customer deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>Customer Could not deleted ";
	}
	unset($userObj);
}

//query for update status of the user.
if(isset($_GET['id']) && isset($_GET['status'])){
	$userObj = new order();
	
	$data = array();
	if(trim(addslashes($_GET['status']))=='1')
		$data['status'] =  0;
	else
		$data['status'] =  1;
	if($userObj->updateOrder($data,'id',$_GET['id']))
	{
		$msg = "<font class='msg_success'>Status updated successfully";
	}
	unset($userObj);
}	
//$orderby = " order by elitesleeper_payment.payment_id desc";
if(isset($_GET['column']) && isset($_GET['sort'])){		
	$column = trim(addslashes($_GET['column']));
	$sort = trim(addslashes($_GET['sort']));
	if($column=="full_name")
		$orderby = " order by elitesleeper_customer.first_name $sort";
	if($column=="date")
		$orderby = " order by elitesleeper_customer.creation_date $sort";
	if($column=="email")
		$orderby = " order by elitesleeper_customer.email_id $sort";
	if($column=="status")
		$orderby = " order by elitesleeper_customer.status $sort";
}
$condition = "";
if(isset($_REQUEST['sname']))
{	
	$sname=$_REQUEST['sname'];
	//$search=explode(" ",$_REQUEST['sname']);
	/* if(!empty($search[1]))
	{
		$last=$search[1];
	}
	else
	{
		$last=$search[0];
	} */
	//$condition = " where elitesleeper_customer.first_name like '%$search[0]%' or elitesleeper_customer.last_name like '%$last%' or elitesleeper_customer.email_id like '%$last%' ";
	$condition = " where elitesleeper_customer.first_name like '%$sname%' or elitesleeper_customer.last_name like '%$sname%' or elitesleeper_customer.email_id like '%$sname%' ";
}
$userObj = new order();
$num_rows = $userObj->totalNumberOfRows($condition);


/*
$query ="SELECT * FROM tbl_user WHERE 1=1 ";	
$result = mysqli_query($GLOBALS['conn'],$query);
$num_rows = mysqli_num_rows($result);
*/	
$rows_per_page = isset($_GET['limit'])?$_GET['limit']:LIMIT;
$lastpage= ceil($num_rows/$rows_per_page); 
 
$pageno = $_REQUEST['pageno'];
$pageno = (int)$pageno;   
if ($pageno < 1)           //to check if page no is not set.
{
  $pageno = 1;
}  
			
$limit = 'LIMIT ' .($pageno -1) * $rows_per_page .',' .$rows_per_page; //Query for limit the page.
			
//   $sql = "$query"." "." $limit";		//Execute the query varible $query and $limit.
//display user record.. 
if(!empty($_SESSION['msg'])){
	$msg=$_SESSION['msg'];
	unset($_SESSION['msg']);
}
	
?>
<script>

jQuery(document).ready(function() {  
    
	jQuery("#manageorder_o").change(function(){
		var limit=jQuery("#manageorder_o option:selected").val();
		var hrefstr = "home.php?mode=manageorder&limit="+limit;
		window.location.href = hrefstr;
	});
});
</script>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Manage Order</td>
   </tr>
   <tr>
	   <td>
		   <form name="searchform" action="" method="POST">
			   <table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding:20px 0 0 0;">
				   <tr>
				   <td  class="iner_heading" align="right">
				   <span>Customer Name</span>
				   <input type="text" name="sname" value="<?=$sname?>">
				   <input type="submit" name="searchuser" value="Search User" class="default_btn" onclick="return searchCheck();">
				   </td></tr>
			   </table>
		   </form>
	   </td>
   </tr>
   <tr>
   <td align="left" style="padding-top:10px;">
        <form name='manageorder' method='post' action="">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr><td colspan="8" class="done_mess"><?=$msg?><?php $msg="";?></td></tr>
				<?php 
					if($num_rows>0){
					?>
					<tr>
					     <td class="num_record" ><input type="submit" class="default_btn" name="delete" onclick="return confirmdelete('')" value="Delete"></td>
				         <td class="num_record" align="right">
								No. Of Records:
							<select id="manageorder_o">
							<option value="50" <?php if(isset($_GET['limit'])&&($_GET['limit']==50)){ echo 'selected';} ?>>50</option>
						<option value="100"  <?php if(isset($_GET['limit'])&&($_GET['limit']==100)){ echo 'selected';} ?>>100</option>
						<option value="200" <?php if(isset($_GET['limit'])&&($_GET['limit']==200)){ echo 'selected';} ?>>200</option>
						<option value="500" <?php if(isset($_GET['limit'])&&($_GET['limit']==500)){ echo 'selected';} ?>>500</option>
							</select>
						</td>
				   </tr><?php
					}?>
				<td align="center" colspan="8" >
                <table width="100%"  border="0" align="left" class="alt_table" cellpadding="0" cellspacing="1" class='admin-table'>
					
					<tr>
						<th width="2%" align="left">
						<input type="checkbox" name="select" value="all" onclick="checkAll(this.form, this.checked);" /></th>
						<th  width='15%' align="left">
						Affiliate Name
						</th>
						<th  width='15%' align="left">
						Customer name
						 <?php 
						if(($_GET['sort']=='desc')&&($_GET['column']=='full_name')){
							
							?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=full_name&sname=<?=$sname?>">
							<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<?php }
						  elseif(($_GET['sort']=='asc')&&($_GET['column']=='full_name')){
						?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=full_name&sname=<?=$sname?>">
							<img class="arrow" src='images/down_arrow.png' border='0'></a>
							
						<?php }else{
							?>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=full_name&sname=<?=$sname?>">
						<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=full_name&sname=<?=$sname?>">
						<img class="arrow" src='images/down_arrow.png' border='0'></a>
						<?php 
						}
						?>
						</th>
						<th  width='16%' align="left">
						Email
						 <?php 
						if(($_GET['sort']=='desc')&&($_GET['column']=='email')){
							
							?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=email&sname=<?=$sname?>">
							<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<?php }
						  elseif(($_GET['sort']=='asc')&&($_GET['column']=='email')){
						?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=email&sname=<?=$sname?>">
							<img class="arrow" src='images/down_arrow.png' border='0'></a>
							
						<?php }else{
							?>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=email&sname=<?=$sname?>">
						<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=email&sname=<?=$sname?>">
						<img class="arrow" src='images/down_arrow.png' border='0'></a>
						<?php 
						}
						?>
						</th>
						<th  width='10%' align="left">
						Date
						 <?php 
						if(($_GET['sort']=='desc')&&($_GET['column']=='date')){
							
							?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=date&sname=<?=$sname?>">
							<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<?php }
						  elseif(($_GET['sort']=='asc')&&($_GET['column']=='date')){
						?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=date&sname=<?=$sname?>">
							<img class="arrow" src='images/down_arrow.png' border='0'></a>
							
						<?php }else{
							?>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=date&sname=<?=$sname?>">
						<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=date&sname=<?=$sname?>">
						<img class="arrow" src='images/down_arrow.png' border='0'></a>
						<?php 
						}
						?>
						</th>
						<th  width='10%' align="left">
						Phone
						</th>
						<th  width='10%' align="left">
						Transaction id
						</th>
						<th  width='3%' align="right">					
						Payment Amount 
						</th>
						
						<th  width='10%' align="center">
						Status
						 <?php 
						if(($_GET['sort']=='desc')&&($_GET['column']=='status')){
							
							?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=status&sname=<?=$sname?>">
							<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<?php }
						  elseif(($_GET['sort']=='asc')&&($_GET['column']=='status')){
						?>
							<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&column=status&sname=<?=$sname?>">
							<img class="arrow" src='images/down_arrow.png' border='0'></a>
							
						<?php }else{
							?>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=asc&column=status&sname=<?=$sname?>">
						<img class="arrow" src='images/up_arrow.png' border='0'></a>
						<a  href="home.php?mode=manageorder&pageno=<?=$pageno?>&sort=desc&status=email&sname=<?=$sname?>">
						<img class="arrow" src='images/down_arrow.png' border='0'></a>
						<?php 
						}
						?>
						</th>
						<th  width='12%' align="center">
						Action
						</th>
					</tr>
					<?php		
					$userResult = $userObj->fetchAll($condition,$orderby,$limit);
					//echo '<pre>';print_r(mysqli_fetch_assoc($userResult)); die;
					if(mysqli_num_rows($userResult))
					{
					while($userRecord = mysqli_fetch_assoc($userResult)){
            		//$validity_upto = $userObj->subscriptionValidDate($userRecord['id']);
					//$date_test = explode('-',$date);

					
					//$x=@mktime(0, 0, 0, $date_test[1]  , $date_test[2]-7, $date_test[0]);
					//$final_date = date('Y-m-d',$x);
					?>
					
					<tr>
					    <td align='left'>
						<input type="checkbox" name="orders[]" value="<?=$userRecord['id']?>">
						</td>
					   <?php
					   
							if($userRecord['affiliate_id'] != 0){
								$con = mysqli_connect("localhost","brain-dba","aiNooYiec4ohx7ar","db_elitecashmachine");
							
								$query = "SELECT * FROM `emate_affiliate` where `id`='".$userRecord['affiliate_id']."'";
								$run_query = mysqli_query($con,$query);
								$result = mysqli_fetch_assoc($run_query); 

						?>
						<td align='left'><?php echo ucfirst(stripslashes($result['fname']))." ".ucfirst(stripslashes($result['lname']));?></td>
						<?php } else{?>
						<td align='left'>N/A</td>
						<?php } ?>
						<td align="left" ><?php echo ucfirst(stripslashes($userRecord['first_name']))." ".stripslashes($userRecord['last_name']);?></td>
						<td align='left'><?php echo wordwrap(stripslashes($userRecord['email_id']),30,"<br/>",true);?></td>
						<td align='left'><?php  if($userRecord['payment_date']){ echo date('M d Y',strtotime($userRecord['payment_date']));}else{ echo date('M d Y',strtotime($userRecord['creation_date']));}?></td>
						<td align='left'><?php echo stripslashes($userRecord['bill_phone']);?></td>
						<td align='center' ><?=$userRecord['transaction_id'];?></td>
						<td align='right' ><?php if($userRecord['payment_amount']!=''){echo '$';}?><?= $userRecord['payment_amount'];?></td>
						<td align='center'>
						<?php
							if($userRecord['status']=='1'){
								$statustext="<font class='msg_success'>Complete"; 
								$statusvalue='1'; 
							}	
							else {
								$statustext="<font class='msg_unsuccess'>Pending";
								$statusvalue='0'; 
							}
						?>
						<a href="home.php?mode=manageorder&id=<?=$userRecord['id']?>&status=<?=$statusvalue?>&pageno=<?=$pageno?>" style="color:red;"><?=$statustext?></a>
						</td>
						<td align='center'> 
						<span><a class="link" href="home.php?mode=vieworder&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>"><img src="images/view.png" border="0" alt="edit" title="view"></a></span> 
						&nbsp;<span><a class="link" href="home.php?mode=editorder&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>"><img src="images/edit.png" border="0"  alt="edit" title="Edit"></a></span>
						&nbsp;<span><a href="home.php?mode=manageorder&del=del&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>" border='0' title='delete'><img src="images/delete.png" border="0"  alt="delete" title="Delete" onClick='javascript:return confirmdelete("home.php?mode=manageorder&del=del&id=<?php echo $userRecord['id'];?>")'></a></span>
						</td>
					</tr>
					<?php  
					    }
					}
						
					if($num_rows == 0)
					{ 
			       ?>
					<tr class="pagination">
					    <td colspan="9"><?="<font  align='centre' class='footer_pagingtext' >No Record Found !</font><br>"; ?>
					    </td>
					</tr>
					<?php
					}
					else
					{			
					?> 
					<tr class="pagination">
				        <td colspan="9" style="border:none;" align="center">
						<?php
								$pagingObj = new paging();
								echo $pagingObj->paginglink($lastpage,"paging_link",$pageno,"pagescrolling",true,true)
						?>
		                </td>
				    </tr>
					<?php
					}
					?>
		
		        </table>
               </td>
           </table>
       </form>
   </td>
</tr>
</table>
<script language="javascript">
function chkempty()
{
	var form = document.forms['manageorder'];   
	for(var i=0;i<form.elements.length;i++)
	{
		if(form.elements[i].type=="checkbox")
		{
			if(form.elements[i].checked==true)
				return true;
		}
	}
	alert("Please select at least one user");
	return false;
}
function checkAll(check, total){		
	for( var i=0; i<check.elements.length; i++ ){
			if( check.elements[i].name == "orders[]")
					check.elements[i].checked = total;
			
	}
}
function confirmdelete(string){
	
		
	
		var where_to = confirm("Confirm want to delete these users ?");
		if (where_to==true)
		{
		return true;
		}
		return false;
	
	
}
</script>

<script language="javascript">
function searchCheck(){
	
	var form = document.forms['searchform'];
	if(form.sname.value=="")
	{
		//alert("please enter a search data");
		//return false;
		return true;
	}
	return true;
}

function pagescrolling(pageno)
{	
	var hrefstr = "home.php?mode=manageorder&pageno="+pageno;
	var sname="<?php echo $sname;?>";
	if(sname!=""){
		hrefstr+="&sname="+sname;
	}
	var limit="<?php echo $_GET['limit'];?>";
	if(limit!=""){
		hrefstr+="&limit="+limit;
	}
	window.location.href = hrefstr;
}

</script>