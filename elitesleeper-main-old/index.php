<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
ob_start();
session_start();
error_reporting(1);
require_once("class/header.php");
require_once("class/footer.php");
require_once("class/left_part.php");
require_once("class/mainContent.php");
require_once("class/page.php");
include("admin/class/dbClass.php");
include("admin/includes/connect.php");
include("admin/class/country.php");
include("admin/config.php");
$site = $_SERVER["HTTP_HOST"];
$site_arr = explode(".", $site);
if (in_array('elitestimulator', $site_arr)){
    $site_id = 1;
}
if (in_array('elitesleeper', $site_arr)){
    $site_id = 4;
}
//funtion for call tracking /////////////
$session_id = session_id();
$_SESSION['id'] = $session_id;
if (isset($_REQUEST['affiliate_id'])) {
    $_SESSION['site_id'] = $site_id;
    $_SESSION['affiliate_id'] = $_REQUEST['affiliate_id'];
    $affiliate_id = $_REQUEST['affiliate_id'];
    $sub_id = $_REQUEST['sub_id'];
    echo "<img src='" . AFFILIATE_ROOT . "/tracking_new.php?affiliate_id=$affiliate_id&sub_id=$sub_id&session_id=$session_id&site_id=$site_id&status=guest' height='0' width='0'>";
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php
    switch ($_GET['mode']) {
        case 'contact':
            $title = "Contact us-EliteSleeper";
            break;
        case 'ordernow':
            $title = "Order now-EliteSleeper";
            break;
        case 'faq':
            $title = "FAQ-EliteSleeper";
            break;
        case'howitwork':
            $title = "How it works-EliteSleeper";
            break;
        case'thankyou':
            $title = "Elite Sleeper Payment Confirmation Page";
            break;
        case'optoutlink':
            $title = "Add Your Email Address To Be Removed From-EliteSleeper";
            break;
        case'offers':
            $title = " Elite Sleeper - Sponsored Offers Page";
            break;
        case'thanks':
            $title = "Thanks Pages";
            break;
        default:
            $title = "EliteSleeper - Always Get A Great Night's Rest";
    }
    ?>
    <title><?= $title ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="https://www.elitesleeper.com/favicon.ico?v=3">
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="manifest" href="manifest.json">
    <script type="text/javascript">
    (function(document, window){
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://api.pushnami.com/scripts/v1/pushnami-adv/618c256847953500111dcb20";
        script.onload = function() {
            Pushnami
                .update()
                .prompt()
        };
        document.getElementsByTagName("head")[0].appendChild(script);
    })(document, window);
    </script>
    <script type="text/javascript" src="service-worker.js"></script>
</head>
<body>
<?php //echo file_get_contents("https://elitecashwire.com/persistentid.php?impression=1"); ?>
<div id="body_wrapper">
    <div id="center_wrapper">
        <div id="wrapper">
            <?php
            $header_Obj = new Header();
            echo $header_Obj->show_header1();
            ?>
        </div>
        <div class="midd-sec-wrap">
            <div class="left-content-area" <?php if ($_REQUEST['mode'] == 'payment'){ ?>style="width:100%;" <?php } ?>>
                <?php echo $header_Obj->show_header2();
                $content_Obj = new MainContent();
                switch ($_REQUEST['mode']) {
                    case'faq':
                        include_once("faq.php");
                        break;
                    case'howitwork':
                        include_once("howitwork.php");
                        break;
                    //case 'ordernow':require("order_now.php");break;
                    case 'ordernow':
                        if(!empty($_GET['token-id'])){
                            if($_SESSION['payment-type']=='onetime'){
                                require("merchant-payment.php");
                            }else{
                                require("recurring-merchant-payment.php");
                            }

                        }else{
                            require("order_now.php");
                        }
                        break;
                    case 'payment':
                        require("Payment/index.php");
                        break;
                    case 'contact':
                        require("contactus.php");
                        break;
                    case 'thankyou':
                        require("thank_you.php");
                        break;
                    case 'optoutlink':
                        require("suppression_list.php");
                        break;
                    case 'offers':
                        require("offers.php");
                        break;
                    case 'thanks':
                        require("thanks.php");
                        break;
                    default:
                        $content_Obj->index();
                }
                ?>
            </div>
            <?php if ($_REQUEST['mode'] != 'payment') { ?>
                <div class="right-content-area">
                    <?php
                    $leftpartObj = new LeftPart();
                    $leftpartObj->facts();
                    ?>
                </div>
            <?php } ?>

        </div>

        <?php $footer_Obj = new Footer();
        ?>

    </div>
</div>

</body>
</html>
