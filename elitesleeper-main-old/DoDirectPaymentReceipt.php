<?php
/***********************************************************
DoDirectPaymentReceipt.php

Submits a credit card transaction to PayPal using a
DoDirectPayment request.

The code collects transaction parameters from the form
displayed by DoDirectPayment.php then constructs and sends
the DoDirectPayment request string to the PayPal server.
The paymentType variable becomes the PAYMENTACTION parameter
of the request string.

After the PayPal server returns the response, the code
displays the API request and response in the browser.
If the response from PayPal was a success, it displays the
response parameters. If the response was an error, it
displays the errors.

Called by DoDirectPayment.php.

Calls CallerService.php and APIError.php.

***********************************************************/
ob_start();
session_start();

include_once("admin/class/dbClass.php");
include_once("admin/config.php");
include_once("admin/includes/connect.php");
include_once("admin/class/order.php");
include_once("admin/class/product.php");
include_once("admin/class/payment.php");
include_once("admin/class/country.php");
require_once 'CallerService.php';
require_once 'constants.php';
//require_once 'CallerService.php';
//require_once 'constants.php';
//include("class/priceclass.php");

//session_start();
	
$API_UserName=API_USERNAME;

$API_Password=API_PASSWORD;

$API_Signature=API_SIGNATURE;

$API_Endpoint =API_ENDPOINT;

$subject = SUBJECT;

/**
 * Get required parameters from the web form for the request
 */
 /*
$paymentType =urlencode( $_POST['paymentType']);
$firstName =urlencode( $_POST['firstName']);
$lastName =urlencode( $_POST['lastName']);
$creditCardType =urlencode( $_POST['creditCardType']);
$creditCardNumber = urlencode($_POST['creditCardNumber']);
$expDateMonth =urlencode( $_POST['expDateMonth']);

// Month must be padded with leading zero
$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

$expDateYear =urlencode( $_POST['expDateYear']);
$cvv2Number = urlencode($_POST['cvv2Number']);
$address1 = urlencode($_POST['address1']);
$address2 = urlencode($_POST['address2']);
$city = urlencode($_POST['city']);
$state =urlencode( $_POST['state']);
$zip = urlencode($_POST['zip']);
$amount = urlencode($_POST['amount']);
*/
 $currencyCode = "USD";
 $creditCardType = urlencode($_SESSION['creditCardType']);
$creditCardNumber = urlencode($_SESSION['creditCardNumber']);
 $padDateMonth = str_pad($_SESSION['expDateMonth'], 2, '0', STR_PAD_LEFT);
//$cvv2Number = urlencode($_SESSION['cvv2Number']);
$address1 = urlencode('Northwest Indian College 2522 Kwina Road');
$city = urlencode('Bellingham');
$state = urlencode('WA');
$zip = urlencode('98226');
$firstName = urlencode('amar');
$lastName = urlencode('gupta');

########################################## Amount  of Product ##################################
$feeObj = new product();
$feeResult  = $feeObj->fetchAll();
if(mysqli_num_rows($feeResult)){
$feeRecord = mysqli_fetch_assoc($feeResult);
$amount = $feeRecord['price'];
}
################################################# End of code ###################################### 
$paymentType = "Sale";
$expDateYear = urlencode($_SESSION['expDateYear']);
////////////////////////////////////////////////////////// hard cord amount ////////////////////////////
//$amount = 0.01;
//////////////////////////////////////////////////////////Please change the amount //////////////////////
/* Construct the request string that will be sent to PayPal.
   The variable $nvpstr contains all the variables and is a
   name value pair string with & as a delimiter */
//$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".         $padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode";
$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&COUNTRYCODE=US&CURRENCYCODE=USD&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&FIRSTNAME=$firstName&LASTNAME=$lastName";
//$nvpstr="&PAYMENTACTION=Sale&AMT=1.00&CREDITCARDTYPE=Visa&ACCT=4874977734464526&EXPDATE=112012&CVV2=962&COUNTRYCODE=US&CURRENCYCODE=USD";
//$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".         $padDateMonth.$expDateYear."&CVV2=$cvv2Number&COUNTRYCODE=US&CURRENCYCODE=$currencyCode";




$getAuthModeFromConstantFile = true;
//$getAuthModeFromConstantFile = false;
$nvpHeader = "";

if(!$getAuthModeFromConstantFile) {
	//$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
	//$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
	$AuthMode = "THIRDPARTY"; //Partner's API Credential and Merchant Email as Subject are required.
} else {
	if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature) && !empty($subject)) {
		$AuthMode = "THIRDPARTY";
	}else if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature)) {
		$AuthMode = "3TOKEN";
	}else if(!empty($subject)) {
		$AuthMode = "FIRSTPARTY";
	}
}

switch($AuthMode) {
	
	case "3TOKEN" : 
			$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature);
			break;
	case "FIRSTPARTY" :
			$nvpHeader = "&SUBJECT=".urlencode($subject);
			break;
	case "THIRDPARTY" :
			$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature)."&SUBJECT=".urlencode($subject);
			break;		
	
}

$nvpstr = $nvpHeader.$nvpstr;

/* Make the API call to PayPal, using API signature.
   The API response is stored in an associative array called $resArray */
$resArray=hash_call("doDirectPayment",$nvpstr);

/* Display the API response back to the browser.
   If the response from PayPal was a success, display the response parameters'
   If the response was an error, display the errors received using APIError.php.
   */
$ack = strtoupper($resArray["ACK"]);

if($ack!="SUCCESS")  {
    $_SESSION['reshash']=$resArray;
	$location = "APIError.php";
		 header("Location: $location");
}else if($ack=="SUCCESS"){

	$data = array();
	
	//$data['subscription_price'] = number_format($amount,2,".","");
	//$data['expiration_date'] = $padDateMonth.$expDateYear;
	$data['payment_date'] = date("Y-m-d",strtotime('now'));
	$data['transaction_id'] = $resArray['TRANSACTIONID'];
	$data['payment_amount'] = $amount;
	$data['payment_nature'] = $_SESSION['payment_nature'];
	$data['card_expiry_date'] = $_SESSION['expDateMonth']."-".$_SESSION['expDateYear'];
	$data['customer_id'] = $_SESSION['customer_id'];
	

	
	
	
	$paymentObj = new payment();
	$paymentId = $paymentObj->insertRecord($data);
		
		$data1 = array();
		$id = $_SESSION['customer_id'];
		$data1['status'] = "1";
		$orderObj  = new order();
		$orderObj->updateOrder($data1,"id",$id);
		$res=$orderObj->fetchRow("where id =".$_SESSION['customer_id']."");
		$record=mysqli_fetch_array($res);

	
		// code for country//
		
		if( $record['bill_country']=='1')
			$bill_country='United State';
		if( $record['bill_country']=='2')
			$bill_country='Canada';
		if( $record['ship_country']=='1')
			$ship_country='United State';
		if( $record['ship_country']=='2')
			$ship_country='Canada';
		
		// end of the code/////
		
		//code for state/////////
		
		$stateObj=new country();
		$state=$stateObj->selectState($record['bill_state']);
		$bill_state=$state['name'];
		$state2=$stateObj->selectState($record['bill_state']);
		$ship_state=$state2['name'];
		
		/////end of the code///////////

/*****************	SEND EMAIL TO USER **************************************************/
	
	
	$headers = "X-Mailer: PHP\n"; 	
	$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
	$headers1=$headers."From:".ADMIN_EMAIL."\n";
	//$headers.="Cc:amit@braintechnosys.com\n";
	$to = $_SESSION['email'];
	$subject = "EliteRev customer details";
	$message = "<html><head><title>Membership success</title><style TYPE=text/css>body{font-family:arial;    font-size:12;}</style> </head><body><table border=0 cellspacing=2 cellpadding=2 width=70%>";
	$message.="<tr><td colspan=2 ></td></tr><tr><td colspan='2' height='1' 
	bgcolor='#1F8992'></td></tr>";		
	$message.="<tr><td><b>Dear Customer:</b></td><td></td></tr>";	
	$message.="<tr><td colspan='2'><b>Your Payment to EliteRev has been successed.Your details are..</b></td><td></td></tr>";	
	$message.="<tr><td><b>Customer Name:</b></td><td>".$record['first_name']." ".$record['last_name']."</td></tr>";
	$message.="<tr><td><b>Email Id:</b></td><td>".$_SESSION['email']."</td></tr>";	
	$message.="<tr><td><b>Payment Amount:</b></td><td>".$amount."</td></tr>";	
	$message.="<tr><td><b>Payment Nature:</b></td><td>".$_SESSION['payment_nature']."</td></tr>";
	$message.="<tr><td><b>Payment Date:</b></td><td>".$data['payment_date']."</td></tr>";
	$message.="<tr><td><b>Transaction Id:</b></td><td>".$data['transaction_id']."</td></tr>";
	$message.="<tr><td><b>Billing Address:</b></td><td>".$record['bill_street'].",".$record['bill_city']."<br>".$bill_state.",".$record['bill_zip']."<br>".$bill_country."</td></tr>";
	$message.="<tr><td><b>Shiping Address:</b></td><td>".$record['ship_street'].",".$record['ship_city']."<br>".$ship_state.",".$record['ship_zip']."<br>".$ship_country."</td></tr>";
	$message.="<tr><td><b>Phone:</b></td><td>".$record['bill_phone']."</td></tr>";
	$message.="<tr><td colspan='2' height='1' bgcolor='#1F8992'></td></tr><tr><td colspan=2></td></tr>     </table></body></html>";
	
	mail($to,$subject,$message,$headers1);	


	$headers2=$headers."From:".$_SESSION['email']."\n";
	
	mail(ADMIN_EMAIL,"New user signup",$message,$headers2);
	echo "<script language='javascript'>window.location='index.php?mode=thankyou'</script>";
}
?>
