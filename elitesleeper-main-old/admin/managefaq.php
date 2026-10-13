<?php

/**
 * @author        Anil Kumar
 * @created       17/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
include("class/faq.php");
include("class/paging.php");

if(isset($_POST['delete'])){
	$userObj = new Faq();
	if($userObj->removeFaq()){
		$msg = "<font class='msg_success'>FAQ deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>FAQ Could not deleted ";
	}
	unset($userObj);
}

if(isset($_REQUEST['del']) && isset($_REQUEST['id'])){	
	$userObj = new Faq();
	if($userObj->trash($_REQUEST['id'])){
		$msg = "<font class='msg_success'>FAQ deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>FAQ Could not deleted ";
	}
	unset($userObj);
}

//query for update status of the user.
if(isset($_GET['id']) && isset($_GET['status'])){
	$userObj = new Faq();
	
	$data = array();
	if(trim(addslashes($_GET['status']))=='1')
		$data['status'] =  0;
	else
		$data['status'] =  1;
	if($userObj->updateFaq($data,'id',$_GET['id']))
	{
		$msg = "<font class='msg_success'>Status updated successfully";
	}
	unset($userObj);
}
	
if(isset($_GET['column']) && isset($_GET['sort'])){		
	$column = trim(addslashes($_GET['column']));
	$sort = trim(addslashes($_GET['sort']));
	if($column=="full_name")
		$orderby = " order by ques $sort";
	if($column=="date")
		$orderby = " order by create_date $sort";
}
$condition = "";
if(isset($_REQUEST['sname']))
{

	$sname = trim(addslashes($_REQUEST['sname']));
	$condition = " where ques like '%$sname%' ";
}
$userObj = new Faq();
$num_rows = $userObj->totalNumberOfRows($condition);

$rows_per_page = 10;
 
$lastpage= ceil($num_rows/$rows_per_page); 
 
$pageno = $_REQUEST['pageno'];
$pageno = (int)$pageno;   
if ($pageno < 1)           //to check if page no is not set.
{
  $pageno = 1;
}  
			
$limit = 'LIMIT ' .($pageno -1) * $rows_per_page .',' .$rows_per_page; //Query for limit the page.
	
?>

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Manage FAQ</td>
   </tr>
   <tr>
   <td align="right" ><a href="home.php?mode=addfaq">Add FAQ</a></td>
   </tr>
   <tr>
	   <td>
		   <form name="searchform" action="" method="POST">
			   <table cellpadding="0" cellspacing="5" border="0" width="150%" style="padding:20px 0 0 0;">
				   <tr><td><label for="Sname">Question :</label></td>
				   <td><input type="text" name="sname" value="<?=$sname?>">
				   <input type="submit" name="searchuser" value="Search FAQ" onclick="return searchCheck();">
				   </td></tr>
			   </table>
		   </form>
	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;" width="100">
         <form name='manageuser' method='post' action="">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
				<tr><td><?=$msg?><?php $msg="";?></td></tr>
				<tr>
                <td align="center" colspan="2">
                <table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class='admin-table'>
					<tr>
					<th align="left" style="height: 21px; width: 10%">
					<input type="checkbox" name="select" value="all" onclick="checkAll(this.form, this.checked);" />Select</th>
					<th align="left" style="height: 21px; width: 20%">
                    Question<a  href="home.php?mode=managefaq&pageno=<?=$pageno?>&sort=asc&column=full_name">
					<img src='images/up_arrow.png' border='0'></a>
					<a  href="home.php?mode=managefaq&pageno=<?=$pageno?>&sort=desc&column=full_name">
					<img src='images/down_arrow.png' border='0'></a>
					</th>
					<th align="left" style="height: 21px; width: 25%">
					Answer
					</th>
					<th align="left" style="height: 21px; width: 20%">					
					Publish Date<a  href="home.php?mode=managefaq&pageno=<?=$pageno?>&sort=asc&column=date">
					<img src='images/up_arrow.png' border='0'></a>
					<a  href="home.php?mode=managefaq&pageno=<?=$pageno?>&sort=desc&column=date">
					<img src='images/down_arrow.png' border='0'></a>
					
					<th align="left" style="height: 21px; width: 10%">
					Status
					</th>
					<th  width='20%' align="left" style="height: 20px">
					Action
					</th>
					</tr>
					<?php					
					
					$userResult = $userObj->fetchAll($condition,$orderby,$limit);
					
					if(mysqli_num_rows($userResult))
					{
					while($userRecord = mysqli_fetch_assoc($userResult)){
					$faq_ans = $userObj->newsubstr(stripslashes($userRecord['ans']) , 5 ,3);
					$faq_ques = $userObj->newsubstr(stripslashes($userRecord['ques']) , 5 ,3);
            		?>
				<tr >
					<td align='left' style="width: 3%">
					<input type="checkbox" name="users[]" value="<?=$userRecord['id']?>">
					</td>
					<td align='left' style="width: 25%" ><?php echo $faq_ques;?></td>
					<td align='left' width="70"><?php echo $faq_ans;?></td>
					<td align='left' style="width: 15%" ><?php echo date("M d Y",strtotime($userRecord['create_date']));?></td>
					
					<td align='left' style="width: 9%">
					<?php
							if($userRecord['status']=='1'){
								$statustext="<font class='msg_success'>Published"; 
								$statusvalue='1'; 
							}	
							else {
								$statustext="<font class='msg_unsuccess'>UnPublished";
								$statusvalue='0'; 
							}
					?>
					<a href="home.php?mode=managefaq&id=<?=$userRecord['id']?>&status=<?=$statusvalue?>&pageno=<?=$pageno?>"><?=$statustext?></a>
					</td>
					<td align='left'> 
					<span><a class="link" href="home.php?mode=viewfaq&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>"><img src="images/view.png" border="0" alt="edit" title="view"></a></span> 
					&nbsp;<span><a class="link" href="home.php?mode=editfaq&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>"><img src="images/edit.png" border="0"  alt="edit" title="Edit"></a></span>
					&nbsp;<span><a href="home.php?mode=managefaq&del=del&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>" border='0' title='delete'><img src="images/delete.png" border="0"  alt="delete" title="Delete" onClick='javascript:return confirmdelete("home.php?mode=managefaq&del=del&id=<?php echo $userRecord['id'];?>")'></a></span>
					</td>
					</tr>
					<?php  }
					}
					
				if($num_rows == 0)
 				{ 
			?>
  		<tr><td colspan="8"><?="<font  align='centre' class='footer_pagingtext' >No Record Found !</font><br>"; ?>
		</td></tr>
		<?php
  		}

		
		else
		{			
		?> 
		<tr>
	  <td colspan="8" style="border:none;" align="left">
	<?php
			$pagingObj = new paging();
			echo $pagingObj->paginglink($lastpage,"paging_link",$pageno,"pagescrolling",false,false)
	?>

		
		</td>
	  </tr><?php
		}
		if($num_rows>0){
		?>
		<tr><td colspan="8" align="center"><input type="submit" name="delete" onclick="return confirmdelete('')" value="Delete"></td></tr><?php
		}?>
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
	var form = document.forms['manageuser'];   
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
	var hrefstr = "home.php?mode=managefaq&pageno="+pageno;
	var sname="<?php echo $sname;?>";
	if(sname!=""){
		hrefstr+="&sname="+sname;
	}
	window.location.href = hrefstr;
}

</script>