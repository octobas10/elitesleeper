<?php
//print_r($_POST);
ob_start();
include_once("admin/class/dbClass.php");
include_once("admin/config.php");
include_once("admin/includes/connect.php");
include_once("admin/class/product.php");
include_once("admin/class/order.php");
if(isset($_POST['ordernow_x'])){
$data['first_name'] = addslashes(trim($_POST['fname']));
$data['last_name'] = addslashes(trim($_POST['lname']));
$data['email_id'] = addslashes(trim($_POST['email']));
$data['password'] = addslashes(trim($_POST['pass']));
$data['creation_date'] = trim(date("Y-m-d",strtotime('now')));
$data['bill_street'] = addslashes(trim($_POST['street_Address_B']));
$data['bill_city'] = trim($_POST['city_B']);
$data['bill_state'] = trim($_POST['state_B']);
$data['bill_country'] = trim($_POST['country_B']);
$data['bill_zip'] = trim($_POST['zip_B']);
$data['bill_phone'] = addslashes(trim($_POST['phone_B']));
if(isset($_POST['same_address'])){
$data['ship_street'] = $data['bill_street'];
$data['ship_city'] = $data['bill_city'];
$data['ship_country'] = $data['bill_country'];
$data['ship_state'] = $data['bill_state'];
$data['ship_zip'] = $data['bill_zip'];
$data['ship_phone'] = $data['bill_phone'];
} else {
$data['ship_street'] = addslashes(trim($_POST['street_Address_S']));
$data['ship_city'] = trim($_POST['city_S']);
$data['ship_country'] = trim($_POST['country_S']);
$data['ship_state'] = trim($_POST['state_S']);
$data['ship_zip'] = trim($_POST['zip_S']);
$data['ship_phone'] = addslashes(trim($_POST['phone_S']));
}

$data['status'] = 0;
$orderObj  = new order();
$orderid = $orderObj->insertOrder($data);
if(!$orderid)
{
	echo "sorry some sql error found";die;
}
$productObj = new product("",""," LIMIT 1");
$productResult  = $productObj->fetchAll();
if(mysqli_num_rows($productResult)){
$productRecord = mysqli_fetch_assoc($productResult);
$id = stripslashes($productRecord['id']);
$title = stripslashes($productRecord['title']);
$price = number_format($productRecord['price'], 2, '.', '');
$shipping_charge = number_format($productRecord['shipping_charge'], 2, '.', '');
$amount = $price+$shipping_charge;
$amount = number_format($amount, 2, '.', '');
}
?>
<img align="top" style="" src="images/banner.jpg" alt="image" height="100" width="1024"/>
<h1> PLEASE WAIT...</h1>
<img src="images/please.gif" alt=""  style="padding-left:100px; padding-top:80px;"/>


	
<!--form name='paypal' action= "https://www.sandbox.paypal.com/cgi-bin/webscr"-->
<form name='paypal' action= "https://www.paypal.com/cgi-bin/webscr">
<input type="hidden" value="<?=PAYPAL_EMAIL?>" name="business">
<input type="hidden" name="image_url" value="<?=WWW_ROOT?>images/logo.jpg">
<input type="hidden" name="item_name" value="<?=$title?>">
<input type="hidden" name="item_number" value="<?=$orderid?>">
<input type="hidden" name="quantity" value="1">
<input type="Hidden" name="no_shipping" value="1">
<input type="Hidden" name="rm" value="2"><!-- request method get(1) or post(2) ---->
<INPUT TYPE="hidden" name="charset" value="utf-8">
<?php
if(isset($_POST['payment']) && $_POST['payment']=="onetime"){
?>
<!--		Code for one time payment --->
<input type="hidden" name="cmd" value="_xclick">
<!--
<input type="hidden" name="amount" value="<?=$price?>">
<input type="hidden" name="shipping" value="<?=$shipping_charge?>">
-->
<input type="hidden" name="amount" value="0.01">
<input type="hidden" name="shipping" value="0.01">
<!--	End of code for one time payment	---------------->
<?php } ?>
<?php
if(isset($_POST['payment']) && $_POST['payment']=="recurring")
{
?>
<!--		Code for recurring billing				--------------------->
<input type="hidden" name="cmd" value="_xclick-subscriptions" /><!-- The button that the person clicked was a Subscribe button.-->
<input type="hidden" value="1" name="no_note"><!-- Do not prompt payers to include a note with their payments. 1 – hide the text box and the prompt -->
<!--	Specify the trial Subscription ------------------------------>
<!--<input type="hidden" name="a1" value="4.95">
<input type="hidden" name="p1" value="7">
<input type="hidden" name="t1" value="D">-->
<!--------	End of trial subscription	--------------------------------------->
<!-- Specify the regular subscription ------------------>
<!--<input type="hidden" name="a3" value="<?=$amount?>">-->
<input type="hidden" name="a3" value="0.01">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="M">
<!----	End of regualr subscription	-------------------------->
<input type="hidden" name="src" value="1"><!-- subscription payments recur(1)/subscription payments not recur(0) -->
<input type="hidden" name="srt" value="12"><!--  Number of times that subscription payments recur -->
<input type="hidden" name="sra" value="1"> <!-- reattempt(1)/notreattempt(0) failed recurring payments before canceling -->
<!--	End of recurring billing code	------------------------>
<?php } ?>
<input type="hidden" name="return"  value="<?=WWW_ROOT?>payment_success.php?flag=true&id=<?=$orderid?>&paymenttype=<?=$_POST['payment']?>">
<input type="hidden" name="notify_url" value="<?=WWW_ROOT?>payment_notify.php?flag=true&id=<?=$orderid?>&paymenttype=<?=$_POST['payment']?>">
<input type="hidden" name="cancel_return" value="<?=WWW_ROOT?>">
<input type="hidden" name="invoice" value="">
<input type="hidden" value="USD" name="currency_code">
<input type="hidden" value="" name="tax_rate">
<!-- <input name="submit" type="submit" class="admin_text" value="Pay Now" /> -->
</form>

<script >

	document.paypal.submit();
	
</script>
<?php
} else {
	//echo "<script>window.location='index.php';</script>";
	//header("location:index.php");
}
?>

