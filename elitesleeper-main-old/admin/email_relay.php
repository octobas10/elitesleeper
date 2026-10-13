<?php

define('TESTING',1);

$db=mysqli_query($GLOBALS['conn'],'localhost','brain-www','wA76mEyUwr');

$relays=array(
							'wrec'=>array('database'=>'db_elitebizgurus','contact'=>'Elitebizgurus <support@elitebizgurus.com>','url'=>'http://www.elitebizgurus.com'),
							'erev'=>array('database'=>'erev_admin','contact'=>'Eliterev <support@eliterev.com>','url'=>'http://www.eliterev.com'),
							'elitesleeper'=>array('database'=>'db_elitesleeper','contact'=>'Elitesleeper <support@elitesleeper>','url'=>'http://www.elitesleeper.com'),
							'womens'=>array('database'=>'','contact'=>'','url'=>'http://www.'),
							'elitetest'=>array('database'=>'','contact'=>'','url'=>'http://www.elitetestboost.com'),
							'esim'=>array('database'=>'','contact'=>'','url'=>'http://www.'),
							'luck'=>array('database'=>'','contact'=>'','url'=>'http://www.'),
							'moms'=>array('database'=>'','contact'=>'','url'=>'http://www.elitenewmoms.com'),
							);

foreach($relays as $relay => $opts)	{
	mysql_select_db($opts['database']);
	$DBcoregUsers="{$relay}_coreg_users";
	$sql_mail=mysqli_query($GLOBALS['conn'],"SELECT DISTINCT(email) FROM $DBcoregUsers where suprission_status='0'",$db) or die(mysql_error($db));

	while($sql_mail_value=mysql_fetch_object($sql_mail)){
		$ert=mysql_fetch_object(mysqli_query($GLOBALS['conn'],"SELECT * FROM $DBcoregUsers WHERE suprission_status='0' AND email='".$sql_mail_value->email."'",$db));

		$now=time(); // or your date as well
		$your_date=strtotime($ert->date);
		$datediff=$now - $your_date;
		$date1=floor($datediff/(60*60*24));

		if(TESTING || $date1>=7) {
			$sql_value=$ert->banner_id;

			if($sql_value=='0'){
				$sql_value=1;
		  }

			$sql_dat=mysqli_query($GLOBALS['conn'],"SELECT * FROM {$relay}_banner");
			$banner_img=array();
			$i=1;

			while($vaue=mysql_fetch_object($sql_dat))	{
				$banner_img[$i]=$vaue->id;
				$i++;
			}

			$count=mysqli_num_rows($sql_dat);

			foreach($banner_img as $key=>$value) {
						if($sql_value==$value){
							$arr_value=$key;
						}
			}

			if($arr_value==$count) {
				$arr_value=1;
			}else {
				$arr_value++;
			}

			$date=date("Y-m-d");
			$sql=mysqli_query($GLOBALS['conn'],"UPDATE $DBcoregUsers SET `banner_id`='".$banner_img[$arr_value]."',`mail_status`=1, date='$date' WHERE email='".$sql_mail_value->email."'");

			$sql_dat=mysqli_query($GLOBALS['conn'],"SELECT * FROM {$relay}_banner WHERE id=".$banner_img[$arr_value]);
			$banner_obj=mysql_fetch_object($sql_dat);

			$to=trim($sql_mail_value->email);
			$subject=$banner_obj->subject;

			$optout=base64_encode(addslashes(trim($sql_mail_value->email)));
			$banner=stripslashes($banner_obj->description);

			$html=<<<EOF
<!DOCTYPE html>
<html lang="en">
<head><!--Start head-->
	<meta charset="utf-8"/>
	<meta name="description" content="{$subject}"/>
	<meta name="keywords" content="{$subject}"/>
	<title>{$subject}</title>
</head><!--End Head-->
<body><!--Start Body-->
	<p>To unsubscribe <a href='{$opts['url']}/index.php?mode=optoutlink&yp={$optout}'>Click here</a></p>
	{$banner}
	<p>To unsubscribe <a href='{$opts['url']}/index.php?mode=optoutlink&yp={$optout}'>Click here</a></p>
</body>
</html>
EOF;

			$text=<<<EOF
To unsubscribe go to {$opts['url']}/index.php?mode=optoutlink&yp={$optout}
This is the text version of this email.
To unsubscribe go to {$opts['url']}/index.php?mode=optoutlink&yp={$optout}
EOF;

			$ver=phpversion();
			$msg_boundry=md5(rand(0,100));
			$body_boundry=md5(rand(101,200));

			$headers=<<<EOF
From: {$opts['contact']}
Reply-To: {$opts['contact']}
MIME-Version: 1.0
Content-type: multipart/mixed; boundary="{$msg_boundry}"
Subject: {$subject}
X-Mailer: PHP/{$ver}
EOF;

			$body=<<<EOF
--{$msg_boundry}
Content-Type: multipart/alternative; boundary="{$body_boundry}"

--{$body_boundry}
Content-Type: text/plain;
	charset=UTF-8
Content-Transfer-Encoding: quoted-printable

{$text}

--{$body_boundry}
Content-Type: text/html;
	charset=UTF-8
Content-Transfer-Encoding: quoted-printable

{$html}

--{$body_boundry}--

--{$msg_boundry}--
EOF;

			if(TESTING) {
				#$to='deeznutz@inorbit.com';
				#$to='deeznutz10881@yahoo.com';
				$to='marc@macastillo.net';
				#$to='macastillo10881@gmail.com';
				#$to='luralambertos222@mail.com';
				#$to="web-5u88yU@mail-tester.com";
				echo "To: $to\n";
				echo $body;
				mail($to,$subject,$body,$headers);
				exit;
			}

			if(!TESTING && mail($to, $subject, $body, $headers)) {
				$date = date('Y-m-d',time());
				$count_query = mysqli_query($GLOBALS['conn'],"INSERT INTO erev_email_count VALUES('','".$to."','".$date."')");
			}
	  }
	}
}

?>
