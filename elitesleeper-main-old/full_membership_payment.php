<?php ob_start();
session_start();
include_once("admin/class/dbClass.php");
include_once("admin/config.php");
include_once("admin/includes/connect.php");
include_once("admin/class/order.php");
include_once("admin/class/product.php");
include_once("admin/class/payment.php");
include_once("admin/class/country.php");
?>
<script >
function payment_submit(){
	document.paypal.submit();
}
</script>
<?php
//include_once("admin/class/payment.php");
########################################## Amount  of Product ##################################
$feeObj = new product();
$feeResult  = $feeObj->fetchAll();
if(mysqli_num_rows($feeResult)){
$feeRecord = mysqli_fetch_assoc($feeResult);
//$amount = $feeRecord['price'] + $feeRecord['shipping_charge'] ;
$amount = $feeRecord['price']+$feeRecord['shipping_charge'];
}
################################################# End of code ###################################### 
?>
<div style="text-align:center;width:100%;">
<a href="<?=WWW_ROOT?>"><img align="top" src="images/banner2.jpg" alt="" /></a><br/>
<img src="<?=WWW_ROOT?>images/please.gif" alt=""  style="padding-top:160px;"/>
</div>

<!--<h1> PLEASE WAIT...</h1>-->
	
<!--form name='paypal' action= "https://www.sandbox.paypal.com/cgi-bin/webscr"-->
<form name='paypal' action= "https://www.paypal.com/cgi-bin/webscr">
<!--<input type="hidden" name="cmd" value="_ext-enter">
<input type="hidden" name="redirect_cmd" value="_xclick-subscriptions" />-->
<input type="hidden" value="_xclick" name="cmd">
<input type="hidden" name="item_name" value="Elite Sleeper - 60 Pills ">
<input type="hidden" name="amount" value="<?=$amount?>">


<!--<input type="hidden" name="cmd" value="_xclick-subscriptions" />--><!-- The button that the person clicked was a Subscribe button.-->
<input type="hidden" value="<?=PAYPAL_EMAIL?>" name="business">
<input type="hidden" name="image_url" value="<?=WWW_ROOT?>images/banner123.jpg">
<!--<input type="hidden" value="abhay@braintechnosys.com" name="business">-->
<!--<input type="hidden" name="image_url" value="<?=WWW_ROOT?>images/world_outcastlogo.jpg">
<input type="hidden" name="image_url" value="<?=WWW_ROOT?>images/banner2.jpg">
<input type="hidden" name="item_name" value="30 Pills of Elite Rev Energy Supplement">
<input type="hidden" name="item_number" value="<?=$_SESSION['customer_id']?>">
<!--	Specify the trial Subscription ------------------------------>
<!--<input type="hidden" name="a1" value="4.95">
<input type="hidden" name="a1" value=<?=$amount?>>
<input type="hidden" name="p1" value="7">
<input type="hidden" name="t1" value="D">-->
<!--------------------------------------------------------------->
<!-- specify the regular subscription ------------------>
<!--<input type="hidden" name="a3" value="<?=$amount?>">

<input type="hidden" name="a3" value=<?=$amount?>>
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="M">-->
<!------------------------------>
<input type="Hidden" name="no_shipping" value="1">
<input type="Hidden" name="rm" value="2">

<!-- <input type="hidden" name="src" value="1">subscription payments recur(1)/subscription payments not recur(0) -->
<!--<input type="hidden" name="srt" value="12">  Number of times that subscription payments recur -->
<!--<input type="hidden" name="sra" value="1">  reattempt(1)/notreattempt(0) failed recurring payments before canceling -->
<input type="hidden" name="return"  value="<?=WWW_ROOT?>payment_success.php?flag=true&customer_id=<?=$_SESSION['customer_id']?>">

<?php if(isset($_SESSION['affiliate_id']) && isset($_SESSION['id']) && isset($_SESSION['site_id'])){ ?>
<input type="hidden" name="notify_url" value="<?=WWW_ROOT?>payment_notify.php?flag=true&customer_id=<?=$_SESSION['customer_id']?>&paymenttype=<?=$_SESSION['payment_nature']?>&affiliate_id=<?=$_SESSION['affiliate_id']?>&session_id=<?= $_SESSION['id']?>&site_id=<?=$_SESSION['site_id'] ?>" >
<?php  }else{ ?>

<input type="hidden" name="notify_url" value="<?=WWW_ROOT?>payment_notify.php?flag=true&customer_id=<?=$_SESSION['customer_id']?>&paymenttype=<?=$_SESSION['payment_nature']?>">
<?php } ?>

<!--input type="hidden" name="notify_url" value="<?=WWW_ROOT?>payment_notify.php?flag=true&customer_id=<?=$_SESSION['customer_id']?>&paymenttype=<?=$_SESSION['payment_nature']?>"-->
<!--<input type="hidden" name="return" value="<?=WWW_ROOT?>payment_notify.php?flag=true&customer_id=<?=$_SESSION['customer_id']?>&paymenttype=<?=$_SESSION['payment_nature']?>">-->
<!--input type="hidden" name="cancel_return" value="<?=WWW_ROOT?>APIError.php"-->
<input type="hidden" name="cancel_return" value="<?=WWW_ROOT?>index.php?mode=offers&cnx=<?= base64_encode($_SESSION['customer_id'])?>">
<input type="hidden" value="1" name="no_note"><!-- hide the text box and the prompt -->
<input type="hidden" name="invoice" value="">
<input type="hidden" value="USD" name="currency_code">
<input type="hidden" value="" name="tax_rate">
<!-- <input name="submit" type="submit" class="admin_text" value="Pay Now" /> -->
</form>

<?php if(isset($_SESSION['pending_url'])){
echo "<iframe onload='payment_submit();' src='".$_SESSION['pending_url']."'  height='0' width='0'></iframe>";
unset($_SESSION['pending_url']);
}else{ ?>
<script>
payment_submit();
</script>
<?php } ?>