<?php 
include("class/order.php");
include("class/paging.php");
include("class/newsletter.php");
           $coregObj = new pages();
		   $suprission_status='1';

			//$userResult = mysqli_query($GLOBALS['conn'],"select * from erev_coreg_users where suprission_status='0'");
					


					

$orderby = "order by id desc ";
if(isset($_GET['column']) && isset($_GET['sort'])){		
	$column = trim(addslashes($_GET['column']));
	$sort = trim(addslashes($_GET['sort']));
	if($column=="name")
		$orderby = "order by name $sort ";
	if($column=="email")
		$orderby = " order by email $sort ";
	if($column=="date")
		$orderby = " order by date $sort ";
	
}
/*
$query ="SELECT * FROM tbl_user WHERE 1=1 ";	
$result = mysqli_query($GLOBALS['conn'],$query);
$num_rows = mysqli_num_rows($result);
*/	
$rows_per_page = isset($_GET['limit'])?$_GET['limit']:LIMIT;
  //Query for limit the page.
			$i=1;

			$userResult= $coregObj->selectCoreg($limit,$suprission_status);

					$num_value=mysqli_num_rows($userResult);
					$num_rows = mysqli_num_rows($userResult);
$lastpage= ceil($num_rows/$rows_per_page); 
 
$pageno = $_REQUEST['pageno'];
$pageno = (int)$pageno;   
if ($pageno < 1)           //to check if page no is not set.
{
  $pageno = 1;
}  
			
$limit = 'LIMIT ' .($pageno -1) * $rows_per_page .',' .$rows_per_page;

$tiles=$coregObj->selectCoreg($limit,$suprission_status,$condition,$orderby);


?>

<script>

jQuery(document).ready(function() {  
    
	jQuery("#inactiveuser_o").change(function(){
		var limit=jQuery("#inactiveuser_o option:selected").val();
		var hrefstr = "home.php?mode=inactiveuser&limit="+limit;
		window.location.href = hrefstr;
	});
});
</script>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Suprission list of Users</td>
   </tr>
   <tr>
	   <td>
		 
	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;">
         <form name='pagemanage' method='post' action="">
		 
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr><td colspan="8" class="done_mess"><?=$msg?><?php $msg="";?></td></tr>
				<?php 
				if($num_rows>0){
				?>
				<tr>
					 <td class="num_record" ></td>
					 <td class="num_record" align="right">
							No. Of Records:
						<select id="inactiveuser_o">
						<option value="50" <?php if(isset($_GET['limit'])&&($_GET['limit']==50)){ echo 'selected';} ?>>50</option>
						<option value="100"  <?php if(isset($_GET['limit'])&&($_GET['limit']==100)){ echo 'selected';} ?>>100</option>
						<option value="200" <?php if(isset($_GET['limit'])&&($_GET['limit']==200)){ echo 'selected';} ?>>200</option>
						<option value="500" <?php if(isset($_GET['limit'])&&($_GET['limit']==500)){ echo 'selected';} ?>>500</option>
						</select>
					</td>
			   </tr><?php
				}?>
				<tr>
                <td align="center" colspan="2" >
                <table width="100%"  border="0" align="left" class="alt_table" cellpadding="0" cellspacing="1" class='admin-table'>
					<tr>
					
					<th  width='6%' align="left">
					Sr. No.
					</th>
						
					<th  width='15%' align="left">
					Name
					 <?php 
					if(($_GET['sort']=='desc')&&($_GET['column']=='name')){
						
						?>
						<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=asc&column=name">
						<img class="arrow" src='images/up_arrow.png' border='0'></a>
					<?php }
					  elseif(($_GET['sort']=='asc')&&($_GET['column']=='name')){
					?>
						<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=desc&column=name">
						<img class="arrow" src='images/down_arrow.png' border='0'></a>
						
					<?php }else{
						?>
					<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=asc&column=name">
					<img class="arrow" src='images/up_arrow.png' border='0'></a>
					<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=desc&column=name">
					<img class="arrow" src='images/down_arrow.png' border='0'></a>
					<?php 
					}
					?>
							
					</th>

					<th  width='15%' align="left">
					Email ID
					 <?php 
					if(($_GET['sort']=='desc')&&($_GET['column']=='email')){
						
						?>
						<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=asc&column=email">
						<img class="arrow" src='images/up_arrow.png' border='0'></a>
					<?php }
					  elseif(($_GET['sort']=='asc')&&($_GET['column']=='email')){
					?>
						<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=desc&column=email">
						<img class="arrow" src='images/down_arrow.png' border='0'></a>
						
					<?php }else{
						?>
					<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=asc&column=email">
					<img class="arrow" src='images/up_arrow.png' border='0'></a>
					<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=desc&column=email">
					<img class="arrow" src='images/down_arrow.png' border='0'></a>
					<?php 
					}
					?>
					</th>
					
					<th  width='15%' align="left">
					Date
					 <?php 
					if(($_GET['sort']=='desc')&&($_GET['column']=='date')){
						
						?>
						<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=asc&column=date">
						<img class="arrow" src='images/up_arrow.png' border='0'></a>
					<?php }
					  elseif(($_GET['sort']=='asc')&&($_GET['column']=='date')){
					?>
						<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=desc&column=date">
						<img class="arrow" src='images/down_arrow.png' border='0'></a>
						
					<?php }else{
						?>
					<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=asc&column=date">
					<img class="arrow" src='images/up_arrow.png' border='0'></a>
					<a  href="home.php?mode=inactiveuser&pageno=<?=$pageno?>&sort=desc&column=date">
					<img class="arrow" src='images/down_arrow.png' border='0'></a>
					<?php 
					}
					?>
					</th>

					
					</tr>
					<?php	
					
				
					
					while($userRecord = mysqli_fetch_array($tiles)){
					if($num_value>=$i){
					?>
					
					<tr>
					
				
					<td align='left'  ><?=$i?></td>
					<td align='left'  ><?=$userRecord['name']?></td>

					<td align='left' ><?=$userRecord['email']?></td>

					<td align='left' ><?php
					echo date('M d Y',strtotime($userRecord['date']));
					?></td>

					
					</tr>
					<?php
					
					$i++;}}
					
					
				if($num_value == 0)
 				{ 
			?>
  		<tr><td colspan="8"><?="<font  align='centre' class='footer_pagingtext' >No Record Found !</font><br>"; ?>
		</td></tr>
		<?php
  		  }else
		{			
		?> 
		<tr class="pagination">
		  <td colspan="4" style="border:none;" align="left">
		<?php
				$pagingObj = new paging();
				echo $pagingObj->paginglink($lastpage,"paging_link",$pageno,"pagescrolling",true,true)
		?>

			
			</td>
		  </tr><?php
			}
			
			?>
			
		</table>
      </td>

</table>
</form>

</td>
</tr>
</table>
<script>
function pagescrolling(pageno)
{	
	var hrefstr = "home.php?mode=inactiveuser&pageno="+pageno;
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