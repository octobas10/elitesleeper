<?php
ob_start();
session_start();
error_reporting(0);
include("config.php");
include("class/dbClass.php");
include("includes/connect.php");
include("class/newsletter.php");

if(isset($_GET['send_type'])) {
	if($_GET['send_type'] == 'admin') {
		//print_r($_SESSION);die;
		if(!isset($_SESSION["ADMIN_ID"])) {
			echo "<script language='javascript'>window.parent.location= 'index.php'</script>";
		}
	}
}
$data = array();
$DBcoregUsers="elitesleeper_coreg_users";
	//$DBcoregUsers="2bk_elitesleeper_coreg_users";
		$sql_mail=mysqli_query($GLOBALS['conn'],"select distinct(email) from $DBcoregUsers where suprission_status='0'");
		while($sql_mail_value=mysql_fetch_object($sql_mail)){

                   // print_r($sql_mail_value); die;
					$ert=mysql_fetch_object(mysqli_query($GLOBALS['conn'],"select * from $DBcoregUsers where suprission_status='0' and email='".$sql_mail_value->email."'"));

					 $now = time(); // or your date as well
					 $your_date = strtotime($ert->date);
					 $datediff = $now - $your_date;
					 $date1= floor($datediff/(60*60*24));
					 if($date1>=15){
						$sql_value=$ert->banner_id;

						if($sql_value=='0'){
						$sql_value=1;
						}

						$sql_dat=mysqli_query($GLOBALS['conn'],"select * from elitesleeper_banner");
						$banner_img=array();
						$i=1;

						while($vaue=mysql_fetch_object($sql_dat)){
							$banner_img[$i]=$vaue->id;
							$i++;
						}

						$count=mysqli_num_rows($sql_dat);



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

						$date=date("Y-m-d");
				        $sql=mysqli_query($GLOBALS['conn'],"update $DBcoregUsers set `banner_id`='".$banner_img[$arr_value]."',`mail_status`=1, date='$date' where email='".$sql_mail_value->email."'");




						$sql_dat=mysqli_query($GLOBALS['conn'],"select * from elitesleeper_banner where id=".$banner_img[$arr_value]);
						$banner_obj=mysql_fetch_object($sql_dat);




						echo $to = trim($sql_mail_value->email);


						$subject = $banner_obj->subject;
						$url = $banner_obj->url;
						$desc=mysqli_query($GLOBALS['conn'],"select * from elitesleeper_banner where id=".$banner_img[$arr_value]);
						$desc_obj=mysql_fetch_object($desc);



						$message = "To unsubscribe <a href='".WWW_ROOT."index.php?mode=optoutlink&yp=".base64_encode (addslashes(trim($sql_mail_value->email)))."'>Click here</a><br /><br />";

						// message

						 $message .= stripslashes($desc_obj->description);


						$message .= "<br />To unsubscribe <a href='".WWW_ROOT."index.php?mode=optoutlink&yp=".base64_encode(trim($sql_mail_value->email))."'>click here</a>";

						//echo count($to);
						// To send HTML mail, the Content-type header must be set
						#$headers  = 'MIME-Version: 1.0' . "\r\n";
						#$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						#$headers .= 'From: Elitesleeper' . "\r\n";
						#//mail($to, $subject, $message, $headers);
						#if(mail($to, $subject, $message, $headers))

						# Bug (20150515): Mail.com email relay issue (email's displaying headers
						# in body of email) Fix: Cleaned up header code - Found that \r was the
						# main cause for headers showing up in email --MAC 20150624
						$testing = 0;
						$headers   = array();
						$headers[] = "MIME-Version: 1.0";
						$headers[] = "Content-type: text/html; charset=iso-8859-1";
						$headers[] = "From: Elitesleeper <support@elitesleeper>";
						$headers[] = "Reply-To: Elitesleeper <support@elitesleeper>";
						$headers[] = "Subject: {$subject}";
						$headers[] = "X-Mailer: PHP/".phpversion();
						if($testing) {
							$to='deeznutz@inorbit.com';
							#$to='marc@macastillo.net';
							#$to='luralambertos222@mail.com';
							echo "To: $to\n";
							echo $message;
							mail($to, $subject, $message, implode("\n",$headers));
							exit;
						}
						if(!$testing && mail($to, $subject, $message, implode("\n", $headers)))
						 {
							$date = date('Y-m-d',time());
							$count_query = mysqli_query($GLOBALS['conn'],"INSERT INTO elitesleeper_email_count VALUES('','".$to."','".$date."')");
						 }
		}
						}
		if(isset($_GET['send_type'])) {
			if($_GET['send_type'] == 'admin') {
				$_SESSION["send_msg"] = 'send_msg';
				echo "<script language='javascript'>window.parent.location= 'home.php?mode=newslettertemplate'</script>";
			}
		} else {
			echo "Cron job successfully run";
		}

?>
