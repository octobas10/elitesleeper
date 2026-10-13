<?php

/**
 * @author        Anil Kumar
 * @created       14/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India)
 * @updated
 */
include("class/newsletter.php");
include("class/paging.php");


if($_GET['msg']=='add'){
$msg = "<font class='msg_success'>Newsletter Added successfully";
}
if($_GET['msg']=='edit'){
$msg = "<font class='msg_success'>Newsletter Edited successfully";
}


if(isset($_GET['edit'])){
	$userObj = new pages();
	$sql=$userObj->bannerlistSelectById($_GET['id']);
	$dt=mysqli_fetch_array($sql);
}


if(isset($_GET['id']) && isset($_GET['status'])){
	$userObj = new pages();

	$data = array();
	if(trim(addslashes($_GET['status']))=='1')
		$data['status'] =  0;
	else
		$data['status'] =  1;
	if($userObj->bannerlistUpdate($data,'id',$_GET['id']))
	{
		$msg = "<font class='msg_success'>Status updated successfully";
	}
	unset($userObj);
}

if(isset($_POST['delete'])){
	$userObj = new pages();
	if($userObj->removePage()){
		$msg = "<font class='msg_success'>Newsletter deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>Newsletter Could not deleted ";
	}
	unset($userObj);
}
$condition = "";
if(isset($_REQUEST['del']) && isset($_REQUEST['id'])){
	$userObj = new pages();
	if($userObj->bannerlistDelete($_REQUEST['id'])){

		$msg = "<font class='msg_success'>Newsletter deleted successfully";
	}
	else
	{
		$msg = "<font class='msg_unsuccess'>Newsletter Could not deleted ";
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

	<!-- Add jQuery library -->
	<script type="text/javascript" src="../lib/jquery-1.9.0.min.js"></script>

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="../lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="../source/jquery.fancybox.js?v=2.1.4"></script>
	<link rel="stylesheet" type="text/css" href="../source/jquery.fancybox.css?v=2.1.4" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="../source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="../source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="../source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="../source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="../source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

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
	</style>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Manage Newsletter</td>

   <td>
	<b><a title="edit" border="0" href="home.php?mode=addbannerlist" style="color:green;font-size:15px"> Add New </a> </b>
   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;" colspan="2">
         <form name='pagemanage' method='post' action="">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr><td style="color:red;" class="done_mess" colspan="3"><?=$msg?><?php $msg="";?></td></tr>
				<tr>
                <td align="center" colspan="2" >
                <table width="100%"  border="0" align="left" class="alt_table" cellpadding="2" cellspacing="2" class='admin-table'>
					<tr>
					<!--th  width='15%' align="left">
                    ID<!--<a  href="home.php?mode=bannerlist&pageno=<?=$pageno?>&sort=asc&column=full_name">
					<img src='images/up_arrow.png' border='0'></a>-->
					<!--<a  href="home.php?mode=bannerlist&pageno=<?=$pageno?>&sort=desc&column=full_name">
					<img src='images/down_arrow.png' border='0'></a>-->
					<!--/th-->
					<th  width='80%' align="left">
					Subject Line
					</th>


					<th  width='10%' align="left">
					Status
					</th>
					<th  width='10%' align="left">
					Action
					</th>
					</tr>
					<?php
					$userResult = $userObj->bannerlistSelect();
					if(mysqli_num_rows($userResult))
					{
					while($userRecord = mysqli_fetch_assoc($userResult)){

					?>

					<tr>
					<td align='left'>
					<?php echo $userRecord['subject'];?>
					</td>

					<!--td align="left" ><?php //echo ucfirst(stripslashes($userRecord['id']));?></td-->


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
					<a href="home.php?mode=bannerlist&id=<?=$userRecord['id']?>&status=<?=$statusvalue?>&pageno=<?=$pageno?>" style="color:red;"><?=$statustext?></a></td>


					<td align='left'>
					<span>
					<a title="edit" border="0" href="home.php?mode=viewbannerlist&view=view&id=<?=$userRecord['id']?>&pageno=1" style="color:green;"><img border="0"  title="View" alt="view" src="images/view.png"></a>
					<a title="edit" border="0" href="home.php?mode=editbannerlist&edit=edit&id=<?=$userRecord['id']?>&pageno=1" style="color:green;"><img border="0"  title="Edit" alt="edit" src="images/edit.png"></a>
					<a title="delete" border="0" href="home.php?mode=bannerlist&del=del&id=<?=$userRecord['id']?>&pageno=1"><img border="0" onclick="javascript:return confirmdelete(&quot;home.php?mode=bannerlist&amp;del=del&amp;id=<?=$userRecord['id']?>&quot;)" title="Delete" alt="delete" src="images/delete.png"></a></span>
					</td>
					</tr>
					<?php  }
					}

?>
</table>
      </td>

</table>
</form>

</td>
</tr>
</table>
<?php if(isset($_GET['edit'])){?>
<script language="javascript">
function validateForm()
{
		var x=document.forms["frm1"]["subject"].value;
		if (x==null || x=="")
		  {
		  alert("First subject must be filled out");
		  return false;
		  }

		  var x=document.forms["frm1"]["url"].value;
		if (x==null || x=="")
		  {
		  alert("First url must be filled out");
		  return false;
		  }



}
</script>

<?php }else{?>

<?php }?>
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
	var hrefstr = "home.php?mode=bannerlist&pageno="+pageno;
	var sname="<?php echo $sname;?>";
	if(sname!=""){
		hrefstr+="&sname="+sname;
	}
	window.location.href = hrefstr;
}




</script>
