<?php
include("class/pages.php");
include("class/paging.php");

if(isset($_GET['id']) && isset($_GET['status'])){
	$userObj = new pages();
	
	$data = array();
	if(trim(addslashes($_GET['status']))=='1')
		$data['status'] =  0;
	else
		$data['status'] =  1;
	if($userObj->updateTable($data,'id',$_GET['id']))
	{
		$msg = "<font class='msg_success'>Status updated successfully";
	}
	unset($userObj);
}

if(isset($_POST['delete'])){
	$userObj = new pages();
	if($userObj->removePage()){
		$msg = "<font class='msg_success'>Page deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>Page Could not deleted ";
	}
	unset($userObj);
}
$condition = "";
if(isset($_REQUEST['del']) && isset($_REQUEST['id'])){	
	$userObj = new pages();
	if($userObj->trash($_REQUEST['id'])){
		$msg = "<font class='msg_success'>Page deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>Page Could not deleted ";
	}
	unset($userObj);
}
if(isset($_GET['column']) && isset($_GET['sort'])){		
	$column = trim(addslashes($_GET['column']));
	$sort = trim(addslashes($_GET['sort']));
	if($column=="full_name")
		$orderby = " order by title $sort";
	if($column=="date")
		$orderby = " order by creation_date $sort";
}
$condition = "";
$userObj = new pages();
$num_rows = $userObj->totalNumberOfRows($condition);


/*
$query ="SELECT * FROM tbl_user WHERE 1=1 ";	
$result = mysqli_query($GLOBALS['conn'],$query);
$num_rows = mysqli_num_rows($result);
*/	
$rows_per_page = 10;
 
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
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Manage Page</td>
   </tr>
   <tr>
	   <td>
		 
	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;">
         <form name='pagemanage' method='post' action="">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
				<tr><td style="color:red;" class="done_mess" colspan="5"><?=$msg?><?php $msg="";?></td></tr>
				<tr>
                <td align="center" colspan="2" >
                <table width="100%"  border="0" align="left" class="alt_table" cellpadding="0" cellspacing="1" class='admin-table'>
					<tr>
					<th  width='15%' align="left">
                    Title
					</th>
					<th  width='30%' align="left">
					Heading
					</th>
					<th  width='25%' align="left">
					Meta_keyword
					</th>
					
					<th  width='15%' align="left">
					Status 
					</th>
					<th  width='15%' align="left">
					Action
					</th>
					</tr>
					<?php		
					
					
					$userResult = $userObj->fetchAll($condition,$orderby,$limit);
					
					if(mysqli_num_rows($userResult))
					{
					while($userRecord = mysqli_fetch_assoc($userResult)){
					$heading = $userObj->newsubstr(stripslashes($userRecord['heading']) , 15 ,3);
					$meta_keywords = $userObj->newsubstr(stripslashes($userRecord['meta_keywords']) , 10 ,3);
            		
					?>
					
					<tr >
					
					<td align="left" ><?php echo ucfirst(stripslashes($userRecord['title']))." ".stripslashes($userRecord['last_name']);?></td>
					<td align='left' ><?php echo stripslashes($heading);?></td>
				
					<td align='left' ><?php echo stripslashes($meta_keywords);?></td>

					<td align='left' ><?php
							if($userRecord['status']=='1'){
								$statustext="<font class='msg_success'>Active"; 
								$statusvalue='1'; 
							}	
							else {
								$statustext="<font class='msg_unsuccess'>Inactive";
								$statusvalue='0'; 
							}
					?>
					<a href="home.php?mode=pagemanage&id=<?=$userRecord['id']?>&status=<?=$statusvalue?>&pageno=<?=$pageno?>" style="color:red;"><?=$statustext?></a></td>

					<td align='left'> 
				 
					<span><a class="link" href="home.php?mode=editpages&id=<?=$userRecord['id']?>&pageno=<?=$pageno?>"><img src="images/edit.png" border="0"  alt="edit" title="Edit"></a></span>
					&nbsp;<span></span>
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
		<?php
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
	var form = document.forms['pagemanage'];   
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
	var hrefstr = "home.php?mode=pagemanage&pageno="+pageno;
	var sname="<?php echo $sname;?>";
	if(sname!=""){
		hrefstr+="&sname="+sname;
	}
	window.location.href = hrefstr;
}

</script>