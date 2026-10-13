<title>Elite Sleeper - Sponsored Offers Page</title>
<?php ob_start();
session_start();
$cnx = isset($_REQUEST['cnx']) ? $_REQUEST['cnx'] : '';
$customerid = base64_decode($cnx);
include_once("admin/class/order.php");
include_once("admin/class/dbClass.php");
include_once("admin/config.php");
include_once("admin/includes/connect.php");
$orderObj = new order();
$postdata = array();
if (!empty($customerid)) {
    $orderid = $orderObj->getUserinfo($customerid);
    $postdata['firstname'] = $orderid['first_name'];
    $postdata['lastname'] = $orderid['last_name'];
    $postdata['email'] = $orderid['email_id'];
    $postdata['country'] = $orderid['name'];
    $postdata['address'] = $orderid['bill_street'];
    $postdata['city'] = $orderid['bill_city'];
    $postdata['state'] = $orderid['bill_state'];
    $postdata['zip'] = $orderid['bill_zip'];
    $postdata['ip'] = $_SERVER['REMOTE_ADDR'];
    $postdata['phone'] = $orderid['bill_phone'];
}
?>
<script language="JavaScript"><!--
    // WEBSITE INFO
    var sd_affiliate_id = 2;
    var sd_site_id = 5;
    var sd_offer_shown = 0;
    var sd_redirect_url = "http://elitesleeper.com/index.php?mode=thanks&";
    var coreg_type = 4;
    var sd_page_name = 'http://elitesleeper.com/index.php?mode=thanks&';
    sd_crg_button = '';

    // PLACEMENT LAYOUT
    coreg_width = "728";

    // PLACEMENT STYLE
    coreg_main_bg_color = 'FFFFFF';
    coreg_alt_bg_color = 'EEEEEE';
    coreg_color_text = '000066';
    coreg_font_size = '12px';
    coreg_font_family = 'Arial, Helvetica, sans-serif';
    coreg_color_border = 'CCCCCC';
    coreg_error_bg_color = 'FE2E2E';
    coreg_error_txt_color = 'FF363C';
    // TARGETING DATA

    coreg_field_state = "<?= $orderid['bill_state'] ?>";
    coreg_field_zip = "<?=$orderid['bill_zip'] ?>";
    coreg_field_gender = " ";
    coreg_field_birth_day = " ";
    coreg_field_birth_month = " ";
    coreg_field_birth_year = " ";
    coreg_field_country = "<?=$orderid['name']?>";

    // REST OF USERS DATA
    coreg_field_fname = "<?=$orderid['first_name']?>";
    coreg_field_lname = "<?=$orderid['last_name']?>";
    coreg_field_email = "<?=$orderid['email_id']?>";
    coreg_field_address = "<?=$orderid['bill_street']?>";
    coreg_field_city = "<?=$orderid['bill_city']?>";
    coreg_field_hphone_area = "<?=$orderid['bill_phone']?>";
    coreg_field_hphone_prefix = "0";
    coreg_field_hphone_suffix = "0";
    coreg_field_wphone_area = "0";
    coreg_field_wphone_prefix = "0";
    coreg_field_wphone_suffix = "0";
    coreg_field_ip = "<?=$_SERVER['REMOTE_ADDR']?>";

    // OPTIONAL DATA
    coreg_field_username = ' ';
    coreg_field_password = ' ';

    if (coreg_field_gender == 'M') {
        coreg_field_title = 'Mr';
    } else {
        coreg_field_title = 'Ms';
    }
    //--></script>
<h1 style="font-family: arial; text-align: center; font-weight: normal; font-size: 18px;">Please review our sponsor
    offers below and say YES to all that interest you.  </br>We Help Simplify Your Life!</h1>
<table width="100%">
    <td align="left" valign="top">
        <!---p><font face="Arial" size="2"><b>You are nearly at
        EliteMate's live interactive website meeting people.<br>
        Please be patient, participating with these offers makes it possible for
        EliteMate to give you<br>
        the first log in Totally Free And Fully Privileged.</b><br>
        <br>
        </font>
        <b>EliteMate has the hottest singles on the planet.&nbsp; Have fun!</b></font>
        </P----->

        <table width="100%" cellspacing=0>
            <tr>
                <td style="background-color:white;padding:5px" align="center">
                    <!----img src="http://www.elitemate.com/images/ads/dive728x90.1.jpg" border="0" width="650" height="90"---->
                    <br>

                    <!--<script language="JavaScript" src="http://www.thesmartconnect.com/javascript/smartdata/coreg.js"></script>-->
                    <?php
                    $curl_session = curl_init();
                    curl_setopt($curl_session, CURLOPT_URL, "https://elitecoregoffers.com/Remote_content/getMysiteKey");
                    curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($curl_session, CURLOPT_HEADER, false);
                    curl_setopt($curl_session, CURLOPT_POSTFIELDS, "request_data=" . urlencode(json_encode($postdata)));
                    $response = curl_exec($curl_session);
                    curl_close($curl_session);
                    echo "<script type='text/javascript'>var s_user_session_key = '$response';</script>";
                    ?>
                    <script type="text/javascript">
                        var so_affiliate_id = 1;
                        var so_site_id = 21;
                        var so_redirect_url = "http://www.elitesleeper.com/thank_you.php";
                    </script>
                    <script src="https://elitecoregoffers.com/assets/offers.js" type="text/javascript"></script>
                </td>
            </tr>
        </table>
        <p align="center">
        <table align="center" width="100%">
            <TR>
                <TD align="center">
                    <form name="modform" action="<?php echo WWW_ROOT; ?>" method="get">
                        <!--input type="hidden" name="mode" value="confirm"-->
                        <input type="submit" value="Skip"/>
                    </form>
                </TD>
            </TR>
        </TABLE>
        </p>
    </td>
</table>
<style>
    .left-content-area table {
        width: 600px !important;
    }

</style>

  