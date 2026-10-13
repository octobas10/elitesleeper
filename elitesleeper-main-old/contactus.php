<?php
if (isset($_POST['submit_x']) && $_POST['g-recaptcha-response'] != "") {
	extract($_POST);
	$to = 'support@elitesleeper.com';
	$form = $email;
	$subject = $subject;
	$fullname = $fname . " " . $lname;
	$secret = '6LegxxUsAAAAAHMLVPXyaV9gv3CVrBQPOfQ47xTN';
	if ($responseData->success == '1') {
		$message = "<html><head><title>Contact Details</title><style TYPE=text/css>body{font-family:arial;font-size:12;}</style> </head><body><table border=0 cellspacing=2 cellpadding=2 width=70%>";
		$message .= "<tr><td colspan=2 ></td></tr><tr><td colspan='2' height='1' 
			bgcolor='#1F8992'></td></tr>";
		$message .= "<tr><td><b>Contact Us Details</b></td></tr>";
		$message .= "<tr><td><b>Name:</b></td><td>" . $fullname . "</td></tr>";
		$message .= "<tr><td><b>Email:</b></td><td>" . $email . "</td></tr>";
		$message .= "<tr><td><b>Description:</b></td><td>" . $Description . "</td></tr>";
		$message .= "<tr><td colspan='2' height='1' bgcolor='#1F8992'></td></tr></table></body></html>";
		$headers = "X-Mailer: PHP\n";
		$headers .= "From:" . $form . "\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		if (!mail($to, $subject, $message, $headers)) {
			echo "Error occured while sending mail";
		} else {
			$success_msg = 'Your request has been sent successfully';
		}
	}
}
?>
<tr>
	<?php
	if (!empty($success_msg))
		echo "<font color='red'>" . $success_msg . "</font>";

	?>
	<td align="left" valign="top">
		<h1>Contact Us</h1>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<form name="contact" method="post" action="">
			<table width="65%" style="margin-left:40px" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="normal_font contact_form" colspan="2">First Name: </td>
					<td><input type="text" name="fname" id="fname" value=""></td>
				</tr>
				<tr>
					<td class="normal_font contact_form" colspan="2">Last Name: </td>
					<td width="60%"><input type="text" name="lname" id="lname" value=""></td>
				</tr>
				<tr>
					<td class="normal_font contact_form" valign="top" colspan="2">Email: </td>
					<td><input type="text" name="email" id="email" value=""></td>
				</tr>
				<tr>
					<td class="normal_font contact_form" valign="top" colspan="2">Subject:</td>
					<td><input type="text" name="subject" id="subject" value=""></td>
				</tr>
				<tr>
					<td class="normal_font contact_form" colspan="2">Description:</td>
					<td><textarea name="Description" rows="5" id="Description" cols="30" wrap="physical" onKeyDown="textCounter(document.contact.Description,document.contact.remLen2,1000)"
							onKeyUp="textCounter(document.contact.Description,document.contact.remLen2,1000)"></textarea><br>
						<span style="float:right;padding-left:118px">Remaining characters <input readonly type="text" name="remLen2" size="3" maxlength="3" value="1000"></span>
					</td>
				</tr>
				<tr>
					<td class="normal_font contact_form" colspan="2"></td>
					<td class="heading contact_form">
						<div class="term_txt">
							<p>
							<div class="g-recaptcha" data-sitekey="6LegxxUsAAAAAJQzMPFzd5IH_vVumyK2S7bffR5p" data-callback="verifyCaptcha"></div>
							</p>
					</td>
				</tr>
				<tr>
					<td class="normal_font" colspan="2" style=''></td>
					<td align="left">
						<input type="image" src="images/submit1.gif" alt="" width="80" height="27" border="0" class="leftorder" onClick="return validate();" name="submit" />
					</td>
				</tr>
			</table>

		</form>
	</td>
</tr>
<SCRIPT LANGUAGE="JavaScript">
	function textCounter(field, cntfield, maxlimit) {
		if (field.value.length > maxlimit) // if too long...trim it!
			field.value = field.value.substring(0, maxlimit);
		else
			cntfield.value = maxlimit - field.value.length;
	}
</script>
<script language="javascript">
	function validate() {
		if (document.getElementById('fname').value == "") {
			alert("Please enter a first name");
			document.getElementById('fname').focus();
			return false;
		}
		if (document.getElementById('lname').value == "") {
			alert("Please enter a last name");
			document.getElementById('lname').focus();
			return false;
		}
		if (document.getElementById('email').value == "") {
			alert("Please enter a email");
			document.getElementById('email').focus();
			return false;
		} else if (!document.getElementById('email').value.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/)) {
			alert("Please enter a valid email");
			document.getElementById('email').focus();
			return false;
		}
		if (document.getElementById('subject').value == "") {
			alert("Please enter a subject");
			document.getElementById('subject').focus();
			return false;
		}
		if (document.getElementById('Description').value == "") {
			alert("Please enter a description");
			document.getElementById('Description').focus();
			return false;
		}
	}
</script>