<?php
/**
 * @description   This used to manage product like title,price,shipping charge
 * @author        Anil Kumar
 * @created       18/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
	include("class/product.php");
	include("class/paging.php");
    // code delete product
	if(isset($_POST['delete'])){
		$userObj = new product();
		if($userObj->removeProduct()){
			$msg = "Product deleted successfully";
		}
		else
		{
			$msg = "Product Could not deleted ";
		}
		unset($userObj);
	}
	$condition = "";
	if(isset($_REQUEST['del']) && isset($_REQUEST['id'])){	
		$userObj = new product();
		if($userObj->trash($_REQUEST['id'])){
			$msg = "Product deleted successfully";
		}
		else
		{
			$msg = "Product Could not deleted ";
		}
		unset($userObj);
	}
	// end 
	
	// for sort order
	if(isset($_GET['column']) && isset($_GET['sort'])){		
		$column = trim(addslashes($_GET['column']));
		$sort = trim(addslashes($_GET['sort']));
		if($column=="full_name")
			$orderby = " order by title $sort";
		if($column=="date")
			$orderby = " order by creation_date $sort";
	}
	// end
	$condition = "";
	$userObj = new product();
	$num_rows = $userObj->totalNumberOfRows($condition);  // total number of records.
	$rows_per_page = isset($_GET['limit'])?$_GET['limit']:LIMIT;
	$lastpage= ceil($num_rows/$rows_per_page); 
	 
	$pageno = $_REQUEST['pageno'];
	$pageno = (int)$pageno;   
	if ($pageno < 1)           //to check if page no is not set.
	{
	  $pageno = 1;
	}  
	$limit = 'LIMIT ' .($pageno -1) * $rows_per_page .',' .$rows_per_page;  //Query for limit the page.
	
	// for set edit or add product message.
	if(!empty($_SESSION['msg'])){
		$msg=$_SESSION['msg'];
		
	}
	unset($_SESSION['msg']);
	
	// end
?>
<script>
jQuery(document).ready(function() {  
	jQuery("#manageproduct_o").change(function(){
		var limit=jQuery("#manageproduct_o option:selected").val();
		var hrefstr = "home.php?mode=manageproduct&limit="+limit;
		window.location.href = hrefstr;
	});
});
</script>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
	   <td align="left" class="text_heading">Manage Cost</td>
	</tr>
	<tr>
		<td>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding-top:10px;">
			<form name='manageproduct' method='post' action="">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr><td style="color:red;" class="done_mess" colspan="4"><?=$msg?><?php $msg="";?></td></tr>
					<?php 
					if($num_rows>0){
					?>
					<tr>
						 <td class="num_record" ></td>
						 <td class="num_record" align="right">
								No. Of Records:
							<select id="manageproduct_o">
							<option value="50" selected="">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
							<option value="500">500</option>
							</select>
						</td>
				    </tr>
					<?php
					}?>
				    <tr>
						<td align="center" colspan="2" >
							<table width="100%"  border="0" align="left" class="alt_table" cellpadding="0" cellspacing="1" class='admin-table'>
								<tr>
									<th  width='30%' align="left">
									Product Title<a  href="home.php?mode=manageproduct&pageno=<?=$pageno?>&sort=asc&column=full_name">
									<img class="arrow" src='images/up_arrow.png' border='0'></a>
									<a  href="home.php?mode=manageproduct&pageno=<?=$pageno?>&sort=desc&column=full_name">
									<img class="arrow" src='images/down_arrow.png' border='0'></a>
									<th  width='25%' align="left">
									Price
									</th>
									<th  width='25%' align="left">
									Shiping Charge
									</th>
									<th  width='20%' align="left">
									Action
									</th>
								</tr>
								<?php		
								$userResult = $userObj->fetchAll($condition,$orderby,$limit);
								
								if(mysqli_num_rows($userResult))
								{
								while($userRecord = mysqli_fetch_assoc($userResult)){
								
								?>
								
								<tr >
									<td align="left" ><?php echo ucfirst(stripslashes($userRecord['title']))." ".stripslashes($userRecord['last_name']);?></td>
									<td align='left' ><?php echo stripslashes($userRecord['price']);?></td>
									<td align='left' ><?php echo stripslashes($userRecord['shipping_charge']);?></td>
									<td align='left'> 
								 	<span><a class="link" href="home.php?mode=editproduct&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>"><img src="images/edit.png" border="0"  alt="edit" title="Edit"></a></span>
									<!--&nbsp;<span><a href="home.php?mode=manageproduct&del=delete&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>" border='0' title='delete'><img src="images/delete.png" border="0"  alt="delete" title="Delete" onClick='javascript:return confirmdelete("home.php?mode=manageproducts&del=delete&id=<?php echo $userRecord['id'];?>")'></a></span>-->
									</td>
								</tr>
								<?php  
								    }
								}
								if($num_rows == 0)
								{ 
								?>
								<tr>
								    <td colspan="8"><?="<font  align='centre' class='footer_pagingtext' >No Record Found !</font><br>"; ?>
								    </td>
								</tr>
								<?php
								}
								?>
							</table>
						</td>
                    </tr>
				</table>
			</form>
		</td>
	</tr>
	
</table>
<script language="javascript">
function chkempty()
{
	var form = document.forms['manageproduct'];   
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
			if( check.elements[i].name == "users[]")
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
		alert("please enter a search data");
		return false;
	}
	return true;
}

function pagescrolling(pageno)
{	
	var hrefstr = "home.php?mode=manageproduct&pageno="+pageno;
	var sname="<?php echo $sname;?>";
	if(sname!=""){
		hrefstr+="&sname="+sname;
	}
	window.location.href = hrefstr;
}

</script>