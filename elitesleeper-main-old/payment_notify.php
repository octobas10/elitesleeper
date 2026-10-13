<?php
 
ob_start();
session_start();
include_once("admin/class/dbClass.php");
include_once("admin/config.php");
include_once("admin/includes/connect.php");
include_once("admin/class/order.php");
include_once("admin/class/payment.php");
include_once("admin/class/country.php");
//mail('abhay@braintechnosys.com','check_url','xyz','from:xyz@gmail.com');
/////////////////////// code for check the user enter via direct or affiliate///////////////////////
		//if(!empty($_SESSION['id'])){
		//$_SESSION['status']='confirm';
		//}
		$session_id=$_SESSION['id'];
		$affiliate_id=$_SESSION['affiliate_id'];
                //take transationid and customed mail to save on elitecashmachine through tracking.php
//                 $transation_id = $_REQUEST['txn_id'];
//                 $_SESSION['trans_id'] = $transation_id;
//                 print_r($_SESSION['trans_id']);
//                 setcookie('trans',$transation_id);


              
                //$customer_email = $_SESSION['email'];
		

?>

<?php

////////////////////////////////end of the code///////////////////////////////////////////////////
if(isset($_REQUEST['flag']) && $_REQUEST['flag']=="true" && isset($_REQUEST['customer_id'])){

	if($_REQUEST['payment_status'] == "Completed"){
        //if($_REQUEST['payment_status'] == "Pending"){
              
		$id = $_REQUEST['customer_id'];
		//$id = 2;
                //$_SESSION['i_id'] = $id;
		$paymentnature = $_REQUEST['paymenttype'];
		$data = array();
	/*	$data['country'] = addslashes($_SESSION['country']);
		$data['email'] = addslashes($_SESSION['email']);
		$data['password'] = addslashes($_SESSION['password']);
		$data['phone'] = addslashes($_SESSION['phone']);
		$data['r_date'] = date("Y-m-d",strtotime('now'));
	*/
		$data['status'] = "1";
		$orderObj  = new order();
		$orderObj->updateOrder($data,"id",$id);		
		$data = array();		

		$data['payment_amount'] = number_format($_REQUEST['payment_gross'],2,".","");	
		$data['payment_date'] = date("Y-m-d",strtotime($_REQUEST['payment_date']));
		$data['transaction_id'] = $_REQUEST['txn_id'];
		$data['payment_nature'] = stripslashes(trim($paymentnature));		
		$data['customer_id'] = $id;		

		$paymentObj = new payment();		
		$paymentId = $paymentObj->insertRecord($data);		
		$res= $orderObj->fetchOrderRow($id);
		
		$orderRecord=mysqli_fetch_array($res);
		//change database country value into real value//
		if($orderRecord['bill_country']=='1')
			$bill_country='United State';
		if($orderRecord['bill_country']=='2')
			$bill_country='Canada';
		if($orderRecord['ship_country']=='1')
			$ship_country='United State';
		if($orderRecord['ship_country']=='2')
			$ship_country='Canada';
		///////////////// end of the code////////////////

		///// change state value///////////////
		$stateObj=new country();
		$state1=$stateObj->selectState($orderRecord['bill_state']);
		$bill_state=$state1['name'];
		$state2=$stateObj->selectState($orderRecord['ship_state']);
		$ship_state=$state2['name'];
		/////////////end of the code////////////
		
		/*****************	SEND EMAIL TO USER **************************************************/
		$headers = "X-Mailer: PHP\n"; 	
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		$headers .="From:".ADMIN_EMAIL."\n";
		//$headers.="Cc:amit@braintechnosys.com\n";
		$to = $orderRecord['email_id'];
		$subject = "EliteRev membership details";
		$message = "<html><head><title>Payment Success</title><style TYPE=text/css>body{font-family:arial;font-size:12;}</style> </head><body><table border=0 cellspacing=2 cellpadding=2 width=70%>";
		$message.="<tr><td colspan=2 ></td></tr><tr><td colspan='2' height='1' 
		bgcolor='#1F8992'></td></tr>";		
		$message.="<tr><td><b>Dear Customer:</b></td><td></td></tr>";	
		$message.="<tr><td><b>Your payment for supplement tablets has made successfully. It will be shipped to you in few days.</b></td><td></td></tr>";	
		$message.="<tr><td><b>FirstName:</b></td><td>".stripslashes($orderRecord['first_name'])."</td></tr>";
		$message.="<tr><td><b>LastName:</b></td><td>".stripslashes($orderRecord['last_name'])."</td></tr>";
		$message.="<tr><td><b>Email Id:</b></td><td>".stripslashes($orderRecord['email_id'])."</td></tr>";
		$message.="<tr><td><b>Phone:</b></td><td>".stripslashes($orderRecord['bill_phone'])."</td></tr>";
		$message.="<tr><td><b>Creation Date:</b></td><td>".stripslashes($orderRecord['creation_date'])."</td></tr>";
		$message.="<tr><td><b>Billing Address:</b></td><td>".stripslashes($orderRecord['bill_street']).",".stripslashes($orderRecord['bill_city'])."<br>".stripslashes($bill_state).",".stripslashes($bill_country)."(".stripslashes($orderRecord['bill_zip']).")"."</td></tr>";
		$message.="<tr><td><b>Shiping Address:</b></td><td>".stripslashes($orderRecord['ship_street']).",".stripslashes($orderRecord['ship_city'])."<br>".stripslashes($ship_state).",".stripslashes($ship_country)."(".stripslashes($orderRecord['ship_country']).")"."</td></tr>";
		$message.="<tr><td><b>Payment Id:</b></td><td>".stripslashes($orderRecord['payment_id'])."</td></tr>";
		$message.="<tr><td><b>Payment Date:</b></td><td>".stripslashes($orderRecord['payment_date'])."</td></tr>";
		$message.="<tr><td><b>Transaction Id:</b></td><td>".stripslashes($orderRecord['transaction_id'])."</td></tr>";
		$message.="<tr><td><b>Payment Amount:</b></td><td>".stripslashes($orderRecord['payment_amount'])."</td></tr>";
		$message.="<tr><td><b>Payment Type:</b></td><td>".stripslashes($orderRecord['payment_nature'])."</td></tr>";
		$message.="<tr><td><b>Customer Id:</b></td><td>".stripslashes($orderRecord['customer_id'])."</td></tr>";
		$message.="<tr><td colspan='2' height='1' bgcolor='#1F8992'></td></tr><tr><td colspan=2></td> </tr>   </table></body></html>";	
		
		$smtp_server    = !empty($_GET['server']) ? $_GET['server'] : 'localhost';
		$smtp_port      = !empty($_GET['port']) ? $_GET['port'] : '25';
		$smtp_helo      = !empty($_GET['helo']) ? $_GET['helo'] : 'relay.elitemate.com';
		
		//// site admin address////

		$smtp_from      = !empty($_GET['from']) ? $_GET['from'] : 'support@elitelitesleeper.com';
	   
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
            echo $e->GetMessage();
        }
        ###############################################


		if(isset($_REQUEST['affiliate_id']) && isset($_REQUEST['site_id']) && isset($_REQUEST['session_id'])){
		file_get_contents(AFFILIATE_ROOT."/tracking_new.php?affiliate_id=".$_REQUEST['affiliate_id']."&site_id=".$_REQUEST['site_id']."&session_id=".$_REQUEST['session_id']."&transtation_id=".$_REQUEST['txn_id']."&status=confirm");
		
		/******************Pixcel Code start****************
		$con = mysqli_query($GLOBALS['conn'],"localhost","brain-www","wA76mEyUwr");
		if (!$con) { die('Could not connect: ' . mysql_error()); }
		mysql_select_db("db_elitecashmachine", $con);
		$result = mysqli_query($GLOBALS['conn'],"SELECT * FROM emate_pixel_tbl where affiliate_id="."'".$_REQUEST['affiliate_id']."' AND site_id=".$_REQUEST['site_id']);
		if(mysqli_num_rows($result)){
		$userRecord = mysqli_fetch_assoc($result);
		$url=trim($userRecord['pixel_url']);
		if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
			file_get_contents($url);
		} else {
			 preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',$url, $matches);
			 if(!empty($matches)){
				 $url=$matches[0][0];
				 if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
				 file_get_contents($url);
				}
			}
		}
		}
		*****************Pixcel Code End*****************/

		}

	}	
}

?>