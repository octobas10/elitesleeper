<?php
include("class/pages.php");
include("class/country.php");
$id = $_REQUEST['id'];
session_start();
if(isset($_POST['submit'])){	
	$data = array();
	@extract($_POST);
	$data['title'] = addslashes(trim($title));
	$data['heading '] = addslashes($heading);
	$data['meta_keywords'] = addslashes($meta_keywords);
	$data['meta_description'] = addslashes($meta_description);
	$data['details'] =addslashes($details);

	

	$data['status'] = addslashes(trim($is_active));
	$userObj  = new pages();
	if($userObj->updateTable($data,'id',$id))
	{
		$msg = "<font class='msg_success'>Record has been updated successfully";
		$_SESSION['msg']=$msg;
		header("location:home.php?mode=pagemanage");			
	}
	else
	{
		$msg = "Sorry! could not updated successfully";
		$_SESSION['msg']=$msg;
	}
 } else {
	$userObj = new pages();
	$userResult = $userObj->fetchAll(" WHERE id='$id'");
	
	if(mysqli_num_rows($userResult)){	
		//@extract(mysqli_fetch_assoc($userResult));
		
		$row=mysqli_fetch_array($userResult);
		$title = $row['title'];
		$heading = $row['heading'];
		
		$meta_keywords = ucfirst($row['meta_keywords']);
		$meta_description= ucfirst($row['meta_description']);
		$details= $row['details'];
		
		$is_active = $row['status'];
		
	}
}
?>
<head>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>

<script language="javascript">
function val()
		{
				var ch=0;
			
			if(document.getElementById('title').value=="")
			{
				document.getElementById("div_title").style.display="block";
				document.getElementById('div_title').innerHTML="<font color='red' size='2'> Please enter the Page title</font>";
				document.getElementById("title").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_title").style.display="none";

			}
			if(document.getElementById('heading').value=="")
			{
				document.getElementById("div_heading").style.display="block";
				document.getElementById('div_heading').innerHTML="<font color='red' size='2'> Please enter the Pgae heading </font>";
				document.getElementById("heading").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_heading").style.display="none";

			}
				if(ch>0)
				return false;
			else
				return true;
		}
</script>

</head>
<form name='edituser' id='edituser' action="<?php $_SERVER['PHP_SELF']?>" method='post' onSubmit="return val();">
	
	<input type="hidden" name="id" value="<?=$id?>" />
	<input type="hidden" name="chk" id="chk" value="1" />
	
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="padding-bottom:54px;" class="admin-table textarea-table">
    <tr><td colspan="4"class="text_heading">
			
			Edit Page
			</td></tr>
    <tr><td style="color:red;"><?php echo $msg;$msg="";?></td></tr>
	
	<tr>
	<td align="right" class='admin_text'>
	Page Title:*<span class='red_14'></span></td>
	<td><input class='textbox' type='text' id='title' name='title' value="<?=$title?>"><div id="div_title"></div></td>
	</tr>
	
	<tr>
	<td align="right" class='admin_text'>
	Page Heading:*<span class='red_14'></span></td>
	<td><input class='textbox' type='text' id='heading' name='heading' value="<?=$heading?>"><div id="div_heading"></div></td>
	</tr>
	
	
	<tr>
	<td align="right" class='admin_text'>
	Meta keywords:<span class='red_14'></span></td>
	<td><textarea class='textbox' rows="3" cols="35" id='meta_keywords' name='meta_keywords'><?=$meta_keywords?></textarea><div id="div_meta_keywords"></div></td>
	</tr>
	
	
	<tr>
	<td align="right" class='admin_text'>
	Meta description:<span class='red_14'></span></td>
	<td><textarea class='textbox' rows="3" cols="35" id='meta_description' name='meta_description'><?=$meta_description?></textarea><div id="div_meta_description"></div></td>
	</tr>
	
	<tr>
	<td align="right" class='admin_text'>
	Details:<span class='red_14'></span></td>
	<td><textarea class='textbox' rows="3" cols="35" id='details' name='details'><?=$details?></textarea><div id="div_details"></div></td>
	</tr>
	
	<tr>
	<td align="right" class='admin_text'>
	Status:<span class='red_14'></span></td>
	<td><input class='textbox check_boxes' type='radio' name='is_active' value="0" <?php if($is_active=='0')echo "checked"?>>Inactive
	<input class='textbox check_boxes' type='radio' name='is_active' value="1" <?php if($is_active=='1') echo "checked"?>>Active</td>
	</tr>	
	
	<tr>
	<td align="right" class='admin_text'>
	&nbsp;<span class='red_14'></span></td>
	<td>&nbsp;</td>
	</tr>
	
	<tr>
	<td align="right" class='admin_text'>
	&nbsp;<span class='red_14'></span></td>
	<td><input type='submit' value='Submit' name='submit' id="submit_button" class="default_btn"></td>
	</tr>	
	
    </td>
    </tr>
    </table>
</form>
		
		<script type="text/javascript">
		// Default skin
		// O2k7 skin (silver)
		tinyMCE.init({
			// General options
			mode : "exact",
			elements : "details",
			theme : "advanced",
			skin : "o2k7",
			skin_variant : "silver",
			relative_urls : false,
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,imagemanager",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,insertimage,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
		
		
		
	</script>
	<!-- /TinyMCE -->