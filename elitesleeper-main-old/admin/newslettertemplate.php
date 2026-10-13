<?php
include("class/newsletter.php");
include("class/country.php");
include("class/order.php");

$id='1';
	
if(isset($_POST['send']))
{
	$data = array();
	@extract($_POST);
	$data['subject'] = addslashes(trim($subject));
	//$data['testmail'] = addslashes(trim($testmail));
	$data['description'] = addslashes($description);

		$sql_mail=mysqli_query($GLOBALS['conn'],"select distinct(email) from erev_coreg_users where suprission_status='0'");
		while($sql_mail_value=mysql_fetch_object($sql_mail)){
						//echo $sql_mail_value->email_id;
						
					$ert=mysql_fetch_object(mysqli_query($GLOBALS['conn'],"select * from erev_coreg_users where suprission_status='0' and email='".$sql_mail_value->email."'"));
						$sql_value=$ert->banner_id;

						if($sql_value=='0'){
						$sql_value=1;
						}

						$sql_dat=mysqli_query($GLOBALS['conn'],"select * from erev_banner");
						$banner_img=array();
						$i=1;

						while($vaue=mysql_fetch_object($sql_dat)){
							$banner_img[$i]=$vaue->id;
							$i++;
						}

						$count=mysqli_num_rows($sql_dat);
						//echo $count;
						
						//print_r($banner_img);
						foreach($banner_img as $key=>$value){
									if($sql_value==$value){
										$arr_value=$key;
									}
						}
						
						
						if($arr_value==$count){
							$arr_value=1;
						}else{
							$arr_value++;
						}
						//echo "update erev_coreg_users set `banner_id`='".$banner_img[$arr_value]."',`mail_status`=1 where email='".$sql_mail_value->email."'";
						$date=date("Y-m-d");
				        $sql=mysqli_query($GLOBALS['conn'],"update erev_coreg_users set `banner_id`='".$banner_img[$arr_value]."',`mail_status`=1, date='$date' where email='".$sql_mail_value->email."'");


                

						$sql_dat=mysqli_query($GLOBALS['conn'],"select * from erev_banner where id=".$banner_img[$arr_value]);
						$banner_obj=mysql_fetch_object($sql_dat);

						
					  /*  $userObj = new order();
						$userResult = $userObj->fetchList($condition,$orderby,$limit);
							if(mysqli_num_rows($userResult))
							{
							while($userRecord = mysqli_fetch_assoc($userResult)){
						  $to= $userRecord['email_id'].',';
							}}*/
						
						

						$to = trim($sql_mail_value->email);

						//$to = addslashes(trim($testmail));
				 
						// subject
						$subject = $banner_obj->subject;

						$message = "To unsubscrible the mail in future <a href='".WWW_ROOT."index.php?mode=optoutlink&yp=".base64_encode (addslashes(trim($sql_mail_value->email)))."'>Click here</a><br /><br />";

						// message
						 $message .= "<a href='http://eliterev.com/index.php?mode=ordernow'><img src='".ADMIN_ROOT."media/".$banner_obj->banner_name."' width='800px' /></a>";
						 $message .= stripslashes($data['description']);
							
						 //$message .= "To unsubscrible the mail in future <a href='".WWW_ROOT."index.php?mode=optoutlink&yp=".base64_encode (addslashes(trim($testmail)))."'>click here</a>";

						$message .= "<br />To unsubscrible the mail in future <a href='".WWW_ROOT."index.php?mode=optoutlink&yp=".base64_encode(trim($sql_mail_value->email))."'>click here</a>";

						//echo count($to);
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

						// Additional headers
						$headers .= 'From: Eliterev <eliterev@elite.com>' . "\r\n";
						

						mail($to, $subject, $message, $headers);
						
						}
		$msg = "Massage Send Successfully";
}

if(isset($_POST['update'])){

	$data = array();
	@extract($_POST);
	//$data['subject'] = addslashes(trim($subject));
	//$data['testmail'] = addslashes(trim($testmail));
	$data['description'] = addslashes($description);
	
	$userObj  = new pages();
	if($userObj->updateTable($data,'id',$id))
	{
		
		$msg = "Updated Successfully";
		//header("location:home.php?mode=newsletterlist");			
	}
	else
	{
		$msg = "Sorry! could not updated successfully";
	}
 } else {
	$userObj = new pages();
	$userResult = $userObj->fetchAll(" WHERE id='1'");
	
	if(mysqli_num_rows($userResult)){	
		//@extract(mysqli_fetch_assoc($userResult));
		
		$row=mysqli_fetch_array($userResult);
		 $subject = $row['subject'];
		 $description= ucfirst($row['description']);
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
<form name='edituser' id='edituser' action="<?php $_SERVER['PHP_SELF']?>" method='post' onSubmit="return val();" enctype="multipart/form-data">
	
	<input type="hidden" name="id" value="<?=$id?>" />
	<input type="hidden" name="chk" id="chk" value="1" />
	
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="padding-bottom:54px;" class="admin-table">
    <tr><td colspan="4"class="text_heading">Add Newsletter Template</td></tr>
    <tr><td style="color:red;"><?php 
		if($_SESSION['send_msg'] != '') {
			echo 'Massage Send Successfully';
		}
		$_SESSION['send_msg'] = '';
		?></td></tr>
    
	 <tr><td align="left" colspan="2" style="padding-top:10px; padding-left:20px" class='admin_text'>
	
	Design Newsletter Template:<span class='red_14'></span><br>
	<textarea class='textbox' rows="3" cols="35" id='details' name='description'><?=$description?></textarea><div id="div_meta_description"></div>
	<br />
    <br />
	<input type='submit' value='Update' name='update' id="submit_button">
	<input type='button' value='Send Mail' name='send' id="send" onclick="send_req()" >
    </td>
    </tr>
    </table>
</form>
		
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