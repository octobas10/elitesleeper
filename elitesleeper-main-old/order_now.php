<?php
session_start();
include_once("admin/class/order.php");
include_once("admin/class/payment.php");
include_once("admin/class/product.php");
$country_obj = new country();
$res = $country_obj->fetchCountry();
$res2 = $country_obj->fetchCountry();
$affiliate_id = $_SESSION['affiliate_id'];
//////////////insert data customer table ////////////
if (isset($_POST['ordernow_x']) || (isset($_POST['terms']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']))) {
    if ($err == '') {
		/*persistent id */
		$shortform = [
			'email'=>$_REQUEST['email'],
			'firstname'=>$_REQUEST['fname'],
			'lastname'=>$_REQUEST['lname'],
			'address'=>$_REQUEST['street_Address_B'],
			'city'=>$_REQUEST['city_B'],
			'state'=>$_REQUEST['state_B'],
			'zip'=> $_REQUEST['zip_B'],
			'phone'=>$_REQUEST['phone_B'],
		];
		$data = json_encode($shortform);
		//echo file_get_contents("https://elitecashwire.com/persistentid.php?persistent=".$data);
		/*persistent id */
         $data1['affiliate_id'] = $affiliate_id;
        $data1['first_name'] = addslashes(trim($_POST['fname']));
        $data1['last_name'] = addslashes(trim($_POST['lname']));
        $data1['email_id'] = addslashes(trim($_POST['email']));
        $data1['password'] = addslashes(trim($_POST['pass']));
        $data1['creation_date'] = trim(date("Y-m-d", strtotime('now')));
        $data1['bill_street'] = trim($_POST['street_Address_B']);
        $data1['bill_city'] = trim($_POST['city_B']);

        if ($_POST['state_h'] != NULL) {
            $state = trim($_POST['state_h']);
        } elseif ($_POST['state_B'] != NULL) {
            $state = trim($_POST['state_B']);
        }
        $data1['bill_state'] = $state;
        $data1['bill_country'] = trim($_POST['country_B']);
        $data1['bill_zip'] = trim($_POST['zip_B']);
        $data1['bill_phone'] = addslashes(trim($_POST['phone_B']));
        if (isset($_POST['same_address'])) {
            $data1['ship_street'] = $data1['bill_street'];
            $data1['ship_city'] = $data1['bill_city'];
            $data1['ship_country'] = $data1['bill_country'];
            $data1['ship_state'] = $data1['bill_state'];
            $data1['ship_zip'] = $data1['bill_zip'];
            $data1['ship_phone'] = $data1['bill_phone'];
        } else {
            $data1['ship_street'] = addslashes(trim($_POST['street_Address_S']));
            $data1['ship_city'] = trim($_POST['city_S']);
            $data1['ship_country'] = trim($_POST['country_S']);
            $data1['ship_state'] = trim($_POST['state_S']);
            $data1['ship_zip'] = trim($_POST['zip_S']);
            $data1['ship_phone'] = addslashes(trim($_POST['phone_S']));
        }
        $data1['status'] = 0;
        $orderObj = new order();
        $orderid = $orderObj->insertOrder($data1);

        if (isset($_SESSION['affiliate_id'])) {
            $session_id = $_SESSION['id'];
            $affiliate_id = $_SESSION['affiliate_id'];
            $u_name = addslashes(trim($_POST['fname'])) . ' ' . addslashes(trim($_POST['lname']));
            $u_email = addslashes(trim($_POST['email']));
            //echo "<img src='".AFFILIATE_ROOT."/tracking_new.php?u_name=$u_name&u_email=$u_email&affiliate_id=$affiliate_id&session_id=$session_id&status=pending' height='0' width='0'>";
            $_SESSION['pending_url'] = AFFILIATE_ROOT . "/tracking_new.php?u_name=$u_name&u_email=$u_email&affiliate_id=$affiliate_id&session_id=$session_id&status=pending";
        }

        /*****maintaining all values in session******/
        $_SESSION['country'] = addslashes(trim($data1['bill_country']));
        //$_SESSION['state_code'] = addslashes(trim($data['bill_state']));
        $_SESSION['state_code'] = $state;
        $_SESSION['zip'] = addslashes(trim($data1['bill_zip']));
        $_SESSION['first_name'] = $_POST['fname'];
        $_SESSION['last_name'] = addslashes(trim($_POST['lname']));
        $_SESSION['add1'] = addslashes(trim($_POST['street_Address_B']));
        $_SESSION['city'] = addslashes(trim($_POST['city_B']));
        $_SESSION['homephone1'] = addslashes(trim($_POST['phone_B']));
        $_SESSION['payment_nature'] = addslashes(trim($_POST['payment']));
        $_SESSION['email'] = addslashes(trim($_POST['email']));
        $_SESSION['customer_id'] = addslashes(trim($orderid));
       
	    /* if ($_POST['payment'] == 'onetime') {
            header("location:full_membership_payment.php");
        }
        if ($_POST['payment'] == 'recurring') {
            header("location:recurring_membership_payment.php");
        } */
		$_SESSION['payment-type']='onetime';
        if($_POST['payment'] == 'onetime') {
            $type=base64_encode('onetime');
            $_SESSION['payment-type']='onetime';
            header("location:merchant-payment.php?mode=one-time");
        }
        if($_POST['payment'] == 'recurring') {
            $_SESSION['payment-type']='recurring';
            header("location:recurring-merchant-payment.php?mode=recurring");
        }

    }
}
///////////////end of the code////////////////////


?>
<script type="text/javascript" src='includes/jquery-1.2.6.js'></script>
<script type="text/javascript" src="includes/xverify_client.js"></script>
<script type="text/javascript" src="includes/jquery.xverify.js"></script>
<script type="text/javascript" src="includes/jquery.blockUI.js"></script>

<script language="javascript">
    function dropdown(cid) {

        if (cid == '1' || cid == '2') {
            $("#state_B").show();
            $("#state_h").hide();

            $.ajax({
                url: "state.php?f=b&cid=" + cid + "",
                success: function (data) {

                    $('#all_state_new').html(data);
                }
            });
        } else {
            $("#state_B").hide();
            $("#state_h").show();
        }
    }

    function dropdown2(cid) {
        if (cid == '1' || cid == '2') {
            $("#state_S").show();
            $("#state_h2").hide();

            $.ajax({
                url: "state.php?f=s&cid=" + cid + "",
                success: function (data) {
                    $('#all_state_new2').html(data);
                }
            });
        } else {
            $("#state_S").hide();
            $("#state_h2").show();
        }
    }
</script>

<SCRIPT LANGUAGE="JavaScript">
    function textCounter(field, cntfield, maxlimit) {
        if (field.value.length > maxlimit) // if too long...trim it!
            field.value = field.value.substring(0, maxlimit);
        else
            cntfield.value = maxlimit - field.value.length;
    }
</script>
<script language="javascript">

    function selected() {
        document.getElementById('country_S').value = "";
        document.getElementById('country_B').value = "";
    }

    function address_type() {
        if (document.getElementById('same_address').checked == true) {
            document.getElementById('shipping_address').style.display = "none";
        } else {
            document.getElementById('shipping_address').style.display = "block";
        }
    }

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
        if (document.getElementById('pass').value == "") {
            alert("Please enter a password");
            document.getElementById('pass').focus();
            return false;
        }
        if (document.getElementById('street_Address_B').value == "") {
            alert("Please enter a street address for billing address");
            document.getElementById('street_Address_B').focus();
            return false;
        }
        if (document.getElementById('city_B').value == "") {
            alert("Please enter a city for billing address");
            document.getElementById('city_B').focus();
            return false;
        }
        if (document.getElementById('country_B').value == "") {
            alert("Please select a country for billing address");
            document.getElementById('country_B').focus();
            return false;
        }
        /*if(document.getElementById('state_B').value==""){
        alert("Please select a state for billing address");
        document.getElementById('state_B').focus();
        return false;
        }*/
        if (document.getElementById('zip_B').value == "") {
            alert("Please enter a zip/post code for billing address");
            document.getElementById('zip_B').focus();
            return false;
        }
        if (document.getElementById('phone_B').value == "") {
            alert("Please enter a phone for billing address");
            document.getElementById('phone_B').focus();
            return false;
        }
        if (document.getElementById('same_address').checked == false) {
            if (document.getElementById('street_Address_S').value == "") {
                alert("Please enter a street address for shipping address");
                document.getElementById('street_Address_S').focus();
                return false;
            }
            if (document.getElementById('city_S').value == "") {
                alert("Please enter a city for shipping address");
                document.getElementById('city_S').focus();
                return false;
            }
            if (document.getElementById('country_S').value == "") {
                alert("Please select a country for shipping address");
                document.getElementById('country_S').focus();
                return false;
            }
            if (document.getElementById('state_S').value == "" && document.getElementById('state_h2').value == "") {
                alert("Please select a state for shipping address");
                document.getElementById('state_S').focus();
                return false;
            }
            if (document.getElementById('zip_S').value == "") {
                alert("Please enter a zip/post code for shipping address");
                document.getElementById('zip_S').focus();
                return false;
            }
            if (document.getElementById('phone_S').value == "") {
                alert("Please enter a phone for shipping address");
                document.getElementById('phone_S').focus();
                return false;
            }

        }
        if (document.getElementById('terms').checked == false) {
            alert("Please select the terms and conditions");
            document.getElementById('terms').focus();
            return false;
        }
        if (document.getElementById('terms').checked && document.getElementById("digital_signature") != null && document.getElementById("digital_signature") !== undefined) {
            var digital_signature = document.getElementById('digital_signature').value;
            if (digital_signature == "") {
                alert('Please sign/type your name in the Privacy Policy and Terms of Service consent field.');
                document.getElementById('digital_signature').focus();
                return false;
            }
        }
        $.blockUI({
            message: '<h4>Just a moment...validating your email address before processing your order.</h4>',
            css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
        });
        jQuery.xVerify.email(document.getElementById('email').value, function (email) {
            $.unblockUI();
            if (email.error) {
                alert(email.message);
                document.getElementById('email').focus();
            } else {
                document.getElementById('contact').submit();
            }
        });
        //validation for bank details/////////////
        /*if(document.getElementById('creditCardNumber').value==""){
        alert("Please enter a credit Card Number");
        document.getElementById('creditCardNumber').focus();
        return false;
        }if(document.getElementById('cvv2Number').value==""){
        alert("Please enter a Card Varification Number");
        document.getElementById('cvv2Number').focus();
        return false;
        }*/
        /*
        if(document.getElementById('recurring_type').checked==true){
        document.getElementById('contact')
        }else{
        document.getElementById('contact').action="index.php?mode=one_Time";
        document.getElementById('contact').submit();

        }*/
        return false;
    }
</script>
<body onload="return selected();">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="9"></td>
    </tr>
    <tr>
        <td><h1>Order Now</h1></td>
    </tr>
    <?php if (!empty($err))
        echo "<font class='green_heading'>" . $err;
    if (!empty($msg))
        echo "<font class='green_heading'>" . $msg;
    ?>

    <tr>
        <td class="heading1txt">
            <form name="contact" id="contact" method="post" action="">
                <table width="98%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="16"></td>
                    </tr>

                    <tr>
                        <td align="center" valign="middle" colspan="2"><img src="images/green_horline.gif" width="630"
                                                                            height="1" vspace="13"/></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">

                            <fieldset>
                                <legend><span class="heading">Customer Detail</span></legend>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td height="16"></td>
                                    </tr>

                                    <tr>
                                        <td class="heading" valign="top"
                                            style='padding-left:18px;padding-top:10px;padding-bottom:10px;'>First Name:
                                            <input type="text" name="fname" id="fname" value="">
                                        </td>
                                        <td class="heading" valign="top"
                                            style='padding-left:18px;padding-top:10px;padding-bottom:10px;'>Last Name:
                                            <input type="text" name="lname" id="lname" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="heading" valign="top"
                                            style='padding-left:18px;padding-top:10px;padding-bottom:25px;'>Email:
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text"
                                                                                                         name="email"
                                                                                                         id="email"
                                                                                                         value="">
                                        </td>
                                        <td class="heading" valign="top"
                                            style='padding-left:18px;padding-top:10px;padding-bottom:10px;'>
                                            <!--Password: -->
                                            &nbsp;<input type="hidden" name="pass" id="pass" value="1111">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                            <fieldset>
                                                <legend><span class="heading">Billing Address</span></legend>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td height="40"></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="heading" valign="top"
                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                            Street Address:
                                                        </td>
                                                        <td><input type="text" name="street_Address_B"
                                                                   id="street_Address_B" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="heading" valign="top"
                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                            City:
                                                        </td>
                                                        <td><input type="text" name="city_B" id="city_B" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="heading" valign="top"
                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                            Country:
                                                        </td>
                                                        <td>

                                                            <select name="country_B" id="country_B"
                                                                    onchange="return dropdown(this.value);"
                                                                    style="width:135px;">
                                                                <option value=''>--Select--</option>
                                                                <?php
                                                                while ($record = mysqli_fetch_array($res)) {
                                                                    if ($record['id'] == 1) {
                                                                        $selected = "selected='selected'";
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo "<option " . $selected . " value=" . $record['id'] . ">" . $record['name'] . "</option>";
                                                                }

                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>

                                                        <td class="heading" valign="top"
                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                            State/Province:
                                                        </td>
                                                        <td id="div_state_B">
                                                            <span id="state_h" style="display:none;"><input type="text"
                                                                                                            name="state_h"
                                                                                                            id="state_h"
                                                                                                            style="width:135px;"></span>

                                                            <span id="all_state_new"><select name="state_B" id="state_B"
                                                                                             style="width:135px;">
	<option value='' selected>--Select--</option>
	<?php
    /*$res4=$country_obj->fetchUsState();
    while($record=mysqli_fetch_array($res4))
    {
    echo "<option value=".$record['id'].">".$record['name']."</option>";
    }*/

    ?>
	</select></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="heading" valign="top"
                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                            Zip/Post code:
                                                        </td>
                                                        <td><input type="text" name="zip_B" id="zip_B" value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="heading" valign="top"
                                                            style='padding-left:7px;padding-top:10px;padding-bottom:5px;'>
                                                            Phone:
                                                        </td>
                                                        <td><input type="text" name="phone_B" id="phone_B" value="">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </td>
                                        <td align="left" valign="top">
                                            <fieldset>
                                                <legend><span class="heading">Shipping Address</span></legend>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td colspan="2" class="heading"
                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                            <input type="checkbox" name="same_address" id="same_address"
                                                                   value="" onClick="return address_type();">Same As
                                                            Billing:
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div id="shipping_address" style="display:block;">
                                                                <table border="0" width="100%">
                                                                    <tr>
                                                                        <td class="heading" valign="top"
                                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                                            Street Address:
                                                                        </td>
                                                                        <td><input type="text" name="street_Address_S"
                                                                                   id="street_Address_S" value=""></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="heading" valign="top"
                                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                                            City:
                                                                        </td>
                                                                        <td><input type="text" name="city_S" id="city_S"
                                                                                   value=""></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="heading" valign="top"
                                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                                            Country:
                                                                        </td>
                                                                        <td>

                                                                            <select name="country_S" id="country_S"
                                                                                    onchange="return dropdown2(this.value);"
                                                                                    style="width:135px;">
                                                                                <option value='' selected>--Select--
                                                                                </option>

                                                                                <?php

                                                                                while ($record = mysqli_fetch_array($res2)) {
                                                                                    echo "<option value=" . $record['name'] . ">" . $record['name'] . "</option>";
                                                                                }

                                                                                ?>
                                                                            </select>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="heading" valign="top"
                                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                                            State/Province:
                                                                        </td>
                                                                        <td id="div_state_S">
                                                                            <!--<select name="state_S" id="state_S" style="width:135px;">
	<option value='' selected>--Select--</option>
	<?php
                                                                            /*$res3=$country_obj->fetchUsState();
                                                                            while($record=mysqli_fetch_array($res3))
                                                                            {
                                                                            echo "<option value=".$record['id'].">".$record['name']."</option>";
                                                                            }
                                                                            */
                                                                            ?>
	</select>-->
                                                                            <span id="state_h2"
                                                                                  style="display:none;"><input
                                                                                        type="text" name="state_h2"
                                                                                        id="state_h2"
                                                                                        style="width:135px;"></span>

                                                                            <span id="all_state_new2"><select
                                                                                        name="state_S" id="state_S"
                                                                                        style="width:135px;">
<option value='' selected>--Select--</option>
<?php
/*$res4=$country_obj->fetchUsState();
while($record=mysqli_fetch_array($res4))
{
echo "<option value=".$record['id'].">".$record['name']."</option>";
}*/

?>
</select></span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="heading" valign="top"
                                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                                            Zip/Post code:
                                                                        </td>
                                                                        <td><input type="text" name="zip_S" id="zip_S"
                                                                                   value=""></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="heading" valign="top"
                                                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                                                                            Phone:
                                                                        </td>
                                                                        <td><input type="text" name="phone_S"
                                                                                   id="phone_S" value=""></td>
                                                                    </tr>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </td>
                                    </tr>


                                    <tr>
                                        <!-----------------Query to get the Product Price------------------->
                                        <?php
                                        $userObj = new product();
                                        $userResult = $userObj->fetchAll();
                                        if (mysqli_num_rows($userResult))
                                        {
                                        while ($userRecord = mysqli_fetch_assoc($userResult)){
                                        ?>
                                        <td colspan="2" class="heading"
                                            style='padding-left:7px;padding-top:5px;padding-bottom:5px;'><label>One time
                                                payment of
                                                $<?php echo stripslashes($userRecord['price'] + $userRecord['shipping_charge']); ?>
                                                for 1 bottle of EliteSleeper:</label>
                                            <input type="radio" name="payment" checked="checked" value="onetime"><br>
                                            <label>Recurring monthly 1 bottle shipment of EliteSleeper for
                                                $<?php echo stripslashes($userRecord['price'] + $userRecord['shipping_charge']); ?>
                                                :</label>
                                            <input type="radio" name="payment" value="recurring">
                                        </td>
                                    </tr>
                                    <?php }
                                    } ?>
                                    <!-----------------Query to get the Product Price------------------->

                                </table>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" align="right" style='padding-left:7px;padding-top:5px;padding-bottom:5px;'>
                            <input type="checkbox" name="terms" id="terms">
                            <span style="color:#444444;font-weight:bold;">I agree to the terms and conditions </span><a
                                    href="eliteSleeper_privacy.htm" target="_blank"><span
                                        style="color:#444444;text-decoration:none;"> Terms and Conditions/Privacy Policy</span></a>

                            <div id="digital_signature_container"
                                 style="display: none; text-align: left; border: 1px solid #CCC; background-color: #FFF; padding: 5px; font-size: 12px; font-family: Arial; margin-bottom: 5px;">
                                <p style="font-size: 12px; padding: 5px 0;">I Express My Consent To The Web And Mobile
                                    <a href="eliteSleeper_privacy.htm">Privacy</a> And <a
                                            href="eliteSleeper_privacy.htm">Terms</a> Of <a
                                            href="http://www.elitesleeper.com">EliteSleeper.com</a>.</p>

                                <p style="font-size: 12px; padding: 5px 0;">Please Enter Your Full Name Below As Your
                                    Digital Signature Of Expressing Your Consent To Allow <a
                                            href="http://www.elitesleeper.com">EliteSleeper.com</a> And Its Marketing
                                    Partners To Communicate To You Via SMS As Text Message From EliteMate Members And
                                    It's Advertisers.</p>

                                <p style="font-size: 12px;  padding: 5px 0; margin-bottom: 0px;">Sign Your Name Here
                                    <input type="text" id="digital_signature"></p>
                            </div>

                            <script type="text/javascript">
                                addEvent(document.contact.terms, "click", function (e) {
                                    var e = e || window.event;
                                    var id = (e.target || e.srcElement).id;

                                    var checked = document.getElementById(id).checked;
                                    var digital_signature_container = document.getElementById("digital_signature_container");
                                    if (checked == true) {
                                        digital_signature_container.style.display = "";
                                        digital_signature_container.style.visibility = "visible";
                                        document.getElementById("digital_signature").focus();
                                    } else {
                                        digital_signature_container.style.display = "none";
                                        digital_signature_container.style.visibility = "hidden";
                                    }
                                });

                                function addEvent(obj, type, fn) {
                                    if (obj.attachEvent) {
                                        obj['e' + type + fn] = fn;
                                        obj[type + fn] = function () {
                                            obj['e' + type + fn](window.event);
                                        }
                                        obj.attachEvent('on' + type, obj[type + fn]);
                                    } else
                                        obj.addEventListener(type, fn, false);
                                }

                                function removeEvent(obj, type, fn) {
                                    if (obj.detachEvent) {
                                        obj.detachEvent('on' + type, obj[type + fn]);
                                        obj[type + fn] = null;
                                    } else
                                        obj.removeEventListener(type, fn, false);
                                }
                            </script>

                            <input type="image" name="ordernow" src="images/submit1.gif" alt="" width="80" height="27"
                                   border="0" class="leftorder" onClick="return validate();"/>
                        </td>
                    </tr>
                    <tr>
                        <td height="25"></td>

                        <td>&nbsp;</td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>

