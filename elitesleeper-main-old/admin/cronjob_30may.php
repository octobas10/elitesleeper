<?php 
include("config.php");
include("class/dbClass.php");
include("includes/connect.php");
include("class/newsletter.php");
include("class/country.php");
include("class/order.php");

$data = array();
	
		$sql_mail=mysqli_query($GLOBALS['conn'],"select distinct(email) from erev_coreg_users where suprission_status='0'");
		while($sql_mail_value=mysql_fetch_object($sql_mail)){
						
						
					$ert=mysql_fetch_object(mysqli_query($GLOBALS['conn'],"select * from erev_coreg_users where suprission_status='0' and email='".$sql_mail_value->email."'"));
					
					 $now = time(); // or your date as well
					 $your_date = strtotime($ert->date);
					 $datediff = $now - $your_date;
					 $date1= floor($datediff/(60*60*24));
					 if($date1>=15){
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
				        $sql=mysqli_query($GLOBALS['conn'],"update erev_coreg_users set `banner_id`='".$banner_img[$arr_value]."',`mail_status`=1, date='$date' where email='".$sql_mail_value->email."'");


                

						$sql_dat=mysqli_query($GLOBALS['conn'],"select * from erev_banner where id=".$banner_img[$arr_value]);
						$banner_obj=mysql_fetch_object($sql_dat);

						
					

						$to = trim($sql_mail_value->email);

						
						$subject = $banner_obj->subject;
						$desc=mysqli_query($GLOBALS['conn'],"select * from erev_newsletter where id='1'");
						$desc_obj=mysql_fetch_object($desc);
						
						

						$message = "To unsubscrible the mail in future <a href='".WWW_ROOT."index.php?mode=optoutlink&yp=".base64_encode (addslashes(trim($sql_mail_value->email)))."'>Click here</a><br /><br />";

						// message
						 $message .= "<a href='www.eliterev.com/?mode=ordernow'><img src='".ADMIN_ROOT."media/".$banner_obj->banner_name."' width='800px' /></a>";
						 $message .= stripslashes($desc_obj->description);
							
						
						$message .= "<br />To unsubscrible the mail in future <a href='".WWW_ROOT."index.php?mode=optoutlink&yp=".base64_encode(trim($sql_mail_value->email))."'>click here</a>";

						//echo count($to);
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

						// Additional headers
						$headers .= 'From: Eliterev <support@eliterev.com >' . "\r\n";
						
						
						mail($to, $subject, $message, $headers);
		}
						}
echo "Cron job successfully run";
		
?>