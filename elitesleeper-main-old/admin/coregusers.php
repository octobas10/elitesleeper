<?php 
/**
 * @author        Anil Kumar
 * @created       14/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India) 
 * @updated       
 */
include("class/order.php");
include("class/paging.php");
include("class/newsletter.php");
           $coregObj = new pages();
			$suprission_status=0;

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
$condition = "";
if(isset($_REQUEST['sname']))
{
	$sname=$_REQUEST['sname'];

	//$condition = "AND (name like '%$sname%' or email like '%$sname%' or city like '%$sname%' or country like '%$sname%' or workphone like '%$sname%') ";
	$condition = "AND (name like '%$sname%' or email like '%$sname%') ";
}



$userResult= $coregObj->selectCoreg($limit="",$suprission_status,$condition,$orderby);
					$num_value=mysqli_num_rows($userResult);			
$num_rows = mysqli_num_rows($userResult);


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
			$i=1;
$tiles=$coregObj->selectCoreg($limit,$suprission_status,$condition, $orderby);

?>

<!-- Add jQuery library -->
	<!--<script type="text/javascript" src="lib/jquery-1.10.1.min.js"></script>-->

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script>

jQuery(document).ready(function() {  
    
	jQuery("#coregusers_o").change(function(){
		var limit=jQuery("#coregusers_o option:selected").val();
		var hrefstr = "home.php?mode=coregusers&limit="+limit;
		window.location.href = hrefstr;
	});
});
</script>
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});


		});
	</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
		.fancybox-inner{
			height: 346px !important;
		}

		
	</style>


<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Coreg Users List</td>
   <!--<td align="right" class="default_btn" ><a class="fancybox fancybox.iframe" href="/admin/export_popup.php">Export as Csv</a></td>-->
   <a class="link_rt fancybox fancybox.iframe" href="/admin/export_popup.php">Export as Csv</a>  
  </tr>
    <tr>
	   
	   <td>
		   <form name="searchform" action="" method="POST">
			   <table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding:20px 0 0 0;">
				   <tr>
				       <td> You can search any record by entering any keyword in the below search field except date & IP.</td>
				   </tr>
				   <tr>
				   <td  class="iner_heading" align="right">
				   <span>Search</span>
				   <input type="text" name="sname" value="<?=$sname?>">
				   <input type="submit" name="searchuser" value="Search User" class="default_btn" onclick="return searchCheck();">
				   </td></tr>
			   </table>
		   </form>
	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;">
        <form name='pagemanage' method='post' action="">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr><td style="color:red;"><?=$msg?><?php $msg="";?></td></tr>
				<?php 
				if($num_rows>0){
				?>
				<tr>
					 <td class="num_record" ></td>
					 <td class="num_record" align="right">
							No. Of Records:
						<select id="coregusers_o">
						<option value="50" <?php if(isset($_GET['limit'])&&($_GET['limit']==50)){ echo 'selected';} ?>>50</option>
						<option value="100"  <?php if(isset($_GET['limit'])&&($_GET['limit']==100)){ echo 'selected';} ?>>100</option>
						<option value="200" <?php if(isset($_GET['limit'])&&($_GET['limit']==200)){ echo 'selected';} ?>>200</option>
						<option value="500" <?php if(isset($_GET['limit'])&&($_GET['limit']==500)){ echo 'selected';} ?>>500</option>
						</select>
					</td>
			   </tr><?php
				}?>
				<tr>
					<td align="center" colspan="6" >
						 <table width="100%"  border="0" align="left" class="alt_table" cellpadding="0" cellspacing="1" class='admin-table'>
							<tr>
							
							<th  width='5%' align="left">
							Sr. No.
							</th>
								
							<th  width='15%' align="left">
							Name
							 <?php 
								if(($_GET['sort']=='desc')&&($_GET['column']=='name')){
									
									?>
									<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=asc&column=name&sname=<?=$sname?>">
									<img class="arrow" src='images/up_arrow.png' border='0'></a>
								<?php }
								  elseif(($_GET['sort']=='asc')&&($_GET['column']=='name')){
								?>
									<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=desc&column=name&sname=<?=$sname?>">
									<img class="arrow" src='images/down_arrow.png' border='0'></a>
									
								<?php }else{
									?>
								<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=asc&column=name&sname=<?=$sname?>">
								<img class="arrow" src='images/up_arrow.png' border='0'></a>
								<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=desc&column=name&sname=<?=$sname?>">
								<img class="arrow" src='images/down_arrow.png' border='0'></a>
								<?php 
								}
								?>
							
							</th>

							<th  width='25%' align="left">
							Email ID
							<?php 
								if(($_GET['sort']=='desc')&&($_GET['column']=='email')){
									
									?>
									<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=asc&column=email&sname=<?=$sname?>">
									<img class="arrow" src='images/up_arrow.png' border='0'></a>
								<?php }
								  elseif(($_GET['sort']=='asc')&&($_GET['column']=='email')){
								?>
									<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=desc&column=email&sname=<?=$sname?>">
									<img class="arrow" src='images/down_arrow.png' border='0'></a>
									
								<?php }else{
									?>
								<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=asc&column=email&sname=<?=$sname?>">
								<img class="arrow" src='images/up_arrow.png' border='0'></a>
								<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=desc&column=email&sname=<?=$sname?>">
								<img class="arrow" src='images/down_arrow.png' border='0'></a>
								<?php 
								}
								?>
							
							</th>
							
							<th  width='20%' align="left">
							Date
							<?php 
								if(($_GET['sort']=='desc')&&($_GET['column']=='date')){
									
									?>
									<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=asc&column=date&sname=<?=$sname?>">
									<img class="arrow" src='images/up_arrow.png' border='0'></a>
								<?php }
								  elseif(($_GET['sort']=='asc')&&($_GET['column']=='date')){
								?>
									<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=desc&column=date&sname=<?=$sname?>">
									<img class="arrow" src='images/down_arrow.png' border='0'></a>
									
								<?php }else{
									?>
								<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=asc&column=date&sname=<?=$sname?>">
								<img class="arrow" src='images/up_arrow.png' border='0'></a>
								<a  href="home.php?mode=coregusers&pageno=<?=$pageno?>&sort=desc&column=date&sname=<?=$sname?>">
								<img class="arrow" src='images/down_arrow.png' border='0'></a>
								<?php 
								}
								?>
							</th>
							<th  width='23%' align="left">
							IP Address
							</th>

							<th  width='12%' align="left">
							Action
							</th>

							</tr>
							<?php	
							

							if(mysqli_num_rows($userResult))
							{
								
							while($userRecord = mysqli_fetch_array($tiles)){
							if($num_value>=$i){
								if($userRecord['mail_status']==1){
								$mail_status='Sent';
								}else{
								$mail_status='Not Sent';
								}
							//echo '<pre>'; print_r($userRecord); die;
							?>
							
							<tr>
							
						
							<td align='left'  ><?=$i?></td>
							<td align='left'  ><?=$userRecord['name']?></td>

							<td align='left' ><?=$userRecord['email']?></td>
							<!--<td align='left' ><?=$userRecord['city']?></td>
							<td align='left' ><?=$userRecord['country']?></td>-->
							 <!--td align='left' ><?=$userRecord['workphone']?></td--->
							<td align='left' ><?php
							
							echo date('M d Y',strtotime($userRecord['enterdate']));
							?></td>
							<td align='left' ><?=$userRecord['ip']?></td>

							<td align='left' >
							<a href="home.php?mode=viewcoregusers&id=<?=$userRecord['id']?>">View</a>
							</td>
							</tr>
							<?php
							$i++;}}
							}
								
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
						  <td colspan="6" style="border:none;" align="left">
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
            </tr>
     </table>
</form>

</td>
</tr>
</table>
<script>
function searchCheck(){
 
 var form = document.forms['searchform'];
 if(form.sname.value=="")
 {
 // alert("Please enter a search data");
  //return false;
  return true;
 }
 return true;
}
function pagescrolling(pageno)
{	
	var hrefstr = "home.php?mode=coregusers&pageno="+pageno;
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