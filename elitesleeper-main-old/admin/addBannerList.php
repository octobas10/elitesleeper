<?php
include("class/newsletter.php");
include("class/paging.php");

if(isset($_POST['submit_add'])){
//print_r($_FILES['banner_name']['name']);
$subject=stripslashes(htmlspecialchars($_POST['subject'],ENT_QUOTES));
$description=$_POST['description'];
$text=$_POST['text'];
$data_obj = new pages();
$data_obj->bannerlistadd($subject,$description,$text);
header('location: home.php?mode=bannerlist&msg=add');
}

?>
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
		<script src="js/ckeditor/samples/sample.js" type="text/javascript"></script>
<!--<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>-->
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <tr>
   <td align="left" class="text_heading">Add Newsletter</td>
   </tr>
   <tr>
	   <td>

	   </td>
   </tr>
   <tr>
      <td align="left" style="padding-top:10px;">

            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
				<tr><td style="color:red;"><?=$msg?><?php $msg="";?></td></tr>
				<tr>
                <td align="center" colspan="2" >
<form name="frm1" method="post" action="" onsubmit="return validateForm()" enctype="multipart/form-data"  >

<table width="100%" border="0" class="banner_lst" align="left" cellpadding="0" cellspacing="0">
<tr><td class="text_heading" colspan="2">&nbsp;</td></tr>
<tr>
<td class="admin_text" align="right"><label>Subject Line:</label></td><td align="left"><input class="textbox" id="subject" type="text" name="subject" size="103" /></td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr><td class="admin_text" align="right"><label>HTML Template:</label></td><td>
	<textarea class='ckeditor' rows="3" cols="35" id='details' name='description'><?=$description?></textarea><div id="div_meta_description"></div>
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
<td>&nbsp;</td><td><input type="submit" class="default_btn" name="submit_add" value="Add" ></td>
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
		var x=document.forms.frm1.subject.value;
		if (x===null || x==="")
		  {
		  alert("Subject must be filled out");
		  return false;
		  }

		var y=document.forms.frm1.text.value;
		if (y===null || y==="")
		  {
		  alert("Text template must be filled out");
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
		/*tinyMCE.init({
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
		});*/



	</script>
	<!-- /TinyMCE -->
