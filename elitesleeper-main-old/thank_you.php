<?php 
include_once("admin/config.php");
include_once("admin/class/payment.php");
ob_start();
session_start();
mysql_select_db('db_elitecashmachine')or die('not selected');
/////////////////////// code for check the user enter via direct or affiliate///////////////////////
		//if(!empty($_SESSION['id'])){
		//$_SESSION['status']='confirm';
		//}
		$session_id=$_SESSION['id'];
		//$affiliate_id=24;
		$affiliate_id=$_SESSION['affiliate_id'];
		//print_r($_SESSION['affiliate_id']);
		$query="select * from emate_pixel_tbl where affiliate_id='$affiliate_id' and site_id=2";
		//echo $query;
		$result=mysqli_query($GLOBALS['conn'],$query);
		
		$record=mysqli_fetch_array($result);
		$site = $record['pixel_url'];
		//echo $site;exit;	
////////////////////////////////end of the code///////////////////////////////////////////////////
/*****************	SEND EMAIL TO USER **************************************************/
		$result2=mysqli_query($GLOBALS['conn'],"select * from emate_affiliate where id = '$affiliate_id'");
		$record2=mysqli_fetch_array($result2);
		//print_r($record2);
		
		
		$to = $record2['email'];

		mysql_select_db('elitesleeper_admin');
		 $id = $_SESSION['cid'];
		//$id = 74;
		 $result3=mysqli_query($GLOBALS['conn'],"SELECT * FROM elitesleeper_customer LEFT JOIN elitesleeper_payment ON elitesleeper_customer.id = elitesleeper_payment.customer_id where elitesleeper_customer.id = '$id' order by elitesleeper_payment.payment_date desc limit 1");
		
		//echo "SELECT * FROM elitesleeper_customer LEFT JOIN elitesleeper_payment ON elitesleeper_customer.id = elitesleeper_payment.customer_id where elitesleeper_customer.id = '$id' order by elitesleeper_payment.payment_date desc limit 1";
		
		
		$orderRecord=mysqli_fetch_array($result3);
		print_r($orderRecord);
		$subject = "EliteRev membership details";
		$message = "<html><head><title>Payment Success</title><style TYPE=text/css>body{font-family:arial;font-size:12;}</style> </head><body><table border=0 cellspacing=2 cellpadding=2 width=70%>";
		$message.="<tr><td colspan=2 ></td></tr><tr><td colspan='2' height='1' 
		bgcolor='#1F8992'></td></tr>";		
		$message.="<tr><td><b>Payment success :</b></td><td></td></tr>";	
		$message.="<tr><td><b>FirstName:</b></td><td>".stripslashes($orderRecord['first_name'])."</td></tr>";
		$message.="<tr><td><b>LastName:</b></td><td>".stripslashes($orderRecord['last_name'])."</td></tr>";
		$message.="<tr><td><b>Email Id:</b></td><td>".stripslashes($orderRecord['email_id'])."</td></tr>";
		$message.="<tr><td><b>Phone:</b></td><td>".stripslashes($orderRecord['bill_phone'])."</td></tr>";
		$message.="<tr><td><b>Creation Date:</b></td><td>".stripslashes($orderRecord['creation_date'])."</td></tr>";
		$message.="<tr><td colspan='2' height='1' bgcolor='#1F8992'></td></tr><tr><td colspan=2></td> </tr>   </table></body></html>";
		
		$smtp_server    = !empty($_GET['server']) ? $_GET['server'] : 'localhost';
		$smtp_port      = !empty($_GET['port']) ? $_GET['port'] : '25';
		$smtp_helo      = !empty($_GET['helo']) ? $_GET['helo'] : 'relay.elitemate.com';
		
		//// site admin address////

		$smtp_from      = !empty($_GET['from']) ? $_GET['from'] : 'support@elitelitesleeper.com';
		//$smtp_from      = !empty($_GET['from']) ? $_GET['from'] : 'abhay@braintechnosys.com';
	   
		$smtp_recipient = !empty($_GET['recipient']) ? $_GET['recipient'] : $to;
		$smtp_auth      = !empty($_GET['auth']) ? $_GET['auth'] : 1;
		$smtp_user      = !empty($_GET['user']) ? $_GET['user'] : 'support-elitelitesleeper';
		$smtp_pass      = !empty($_GET['pass']) ? $_GET['pass'] : 'supportmail';

		ini_set('display_errors', 1);

        require_once('class/smtp.php');
        
        ###############################################
         try {
            $smtp = new SMTP($smtp_server, $smtp_port, $smtp_helo, $smtp_auth, $smtp_user, $smtp_pass);
            $smtp->dbug = false;
            $smtp->Connect();
            $result=$smtp->Send(array($smtp_recipient),                                     // Array of recipient addresses
                        $smtp_from,                                                 // SMTP From address
                        array('Subject' => $subject,
                              'To'      => $smtp_recipient,
							   //'Cc'		=> 'amar@braintechnosys.com',		
                              'From'    => $smtp_from,
							'X-Mailer' => 'PHP\n',
							'Content-Type' => 'text/html; charset=iso-8859-1\n'),							// The emails headers
                        $message);                                                // The emails body (phwoar...)
        
        } catch (SMTPException $e) {
           //echo $e->GetMessage();
        }
        ###############################################
	?>
	

		<tr>
		<?php
         /*   
                
		if(isset($_SESSION['cid'])){
         
                  $customer_id = $_SESSION['cid'].',1';
                ?>
		<img src="<?=AFFILIATE_ROOT?>/tracking.php?status=confirm&session_id=<?=$session_id?>&s_page=order&affiliate_id=<?=$affiliate_id?>&u_email=<?=$_SESSION['email']?>&transation_id=<?=$customer_id?>" height='0' width='0'>
			<?php
			 if($site){?>
		<img src='<?=stripslashes($site)?>' height='0' width='0'>
        <?php
			}
		} */
		?>
		  
                    <tr>
                      <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <?php
//                           echo $_SESSION['transation_id'];
//                           $customer_email = $_SESSION['email'];
                          ?>
                          <tr>
                             <td class="heading1txt" align="left" style="padding-left:35px;"><span style="font-size:15px ;color:#000000;">Congratulations!<br>  
                            You successfully ordered  EliteSleeper.com<br>
                            Our  EliteSleeper.com Support Team will now ship your product(s).<br>
                            Shipping usually takes 5-7 business days.<br><br>

                            Have a great day!<br><br>

                            Sincerely,<br><br>

                            EliteSleeper.com Support Team<br>
							<a href="mailto:support@elitesleeper.com">support@elitesleeper.com</a><br>
                            We Simplify Healthier Living
                            </span>
							<br/>
							<div class="more_offer_new"><a href="http://www.elitesleeper.com/offers.php">Click Here</a> To Check out Offers for Our Paying Clients</div>
							<div style="display:none;">
							<?php 
								if(isset($_SESSION['payment_success']) && (isset($_SESSION['affiliate_id']) && $_SESSION['affiliate_id']!='') && (isset($_SESSION['site_id']) && $_SESSION['site_id']!='')){
								/******************Pixcel Code start****************/
								$con = mysqli_query($GLOBALS['conn'],"localhost","brain-www","wA76mEyUwr");
								if (!$con) { die('Could not connect: ' . mysql_error()); }
								mysql_select_db("db_elitecashmachine", $con);
								$result = mysqli_query($GLOBALS['conn'],"SELECT * FROM emate_pixel_tbl where affiliate_id="."'".$_SESSION['affiliate_id']."' AND site_id=".$_SESSION['site_id']);
								if(mysqli_num_rows($result)){
								$userRecord = mysqli_fetch_assoc($result);
								//$url=trim($userRecord['pixel_url']);
								echo $userRecord['pixel_url'];
								unset($_SESSION['affiliate_id']);
								unset($_SESSION['site_id']);
								
								}
								/******************Pixcel Code End*****************/
							 } ?>
							 </div>
							</td>
                          </tr>
                          
                        </table></td>
                    </tr>
                    
      