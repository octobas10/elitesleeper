<?php
include("class/newsletter.php");
include("class/paging.php");
if(isset($_GET['edit'])){
	$userObj = new pages();
	$sql=$userObj->bannerlistSelectById($_GET['id']);
	$dt=mysqli_fetch_array($sql);
}

if(isset($_POST['submit_edit'])){
//print_r($_FILES['banner_name']['name']);
$data['subject']=$_POST['subject'];
$data['description']=$_POST['description'];
$data['text']=$_POST['text'];
$data_obj = new pages();

$data_obj->bannerlistUpdate($data,'id',$_GET['id']);
  // $_SESSION['msg']="<font class='msg_success'>Record has been updated successfully.</font>";
   header('location: home.php?mode=bannerlist&msg=edit');
}

?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Edit Newsletter</td>
   </tr>
   <tr>
	   <td>

	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;">

            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
				<!--<tr><td style="color:red;"><?=$msg?><?php $msg="";?></td></tr>-->
				<tr>
                <td align="center" colspan="2" >
<form id='edituser' name="frm1" method="post" action="" onsubmit="return validateForm()" enctype="multipart/form-data"  >

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
<tr><td class="text_heading" colspan="2">&nbsp;</td></tr>
<tr>
<td><label>Subject Line:</label></td><td align="left"><input id="subject" class="textbox" type="text" name="subject" size="103" value="<?php echo $dt['subject']?>" /></td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr><td><label>HTML Template:</label></td><td>
	<textarea class='textbox' rows="3" cols="35" id='details' name='description'><?=$dt['description']?></textarea><div id="div_meta_description"></div>
	</td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr><td><label>Text Template:</label></td><td>
	<textarea class='textbox' rows="3" cols="35" id='text' name='text'><?=$dt['text']?></textarea><div id="div_meta_text"></div>
	</td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td><td><input type="submit" class="default_btn" name="submit_edit" value="Update" > &nbsp; <input type="button" name="preview" class="default_btn default_btn_preview"  value="Preview" onclick="javascript:window.location='home.php?mode=viewbannerlist&view=view&id=<?php echo $_GET['id']?>'" >  &nbsp; You can preview the template after update.</td>
</tr>
</table>
</form>
</td>

</table>


</td>
</tr>
</table>
<script language="javascript">
function validateForm()
{
		var x=document.forms["frm1"]["subject"].value;
		if (x==null || x=="")
		  {
		  alert("First subject must be filled out");
		  return false;
		  }

		  var x=document.forms["frm1"]["details"].value;
		if (x==null || x=="")
		  {
		  alert("First detail must be filled out");
		  return false;
		  }


}
</script>

<script type="text/javascript">
		function send_req() {
			window.location.href="cronjob.php?send_type=admin";
		}
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
			remove_script_host: false,
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
