<?php
if($_SERVER['HTTP_HOST'] == 'localhost'){
    define("WWW_ROOT","https://localhost/EliteMate/eliterev.com/");    //Please dont remove last slashe            // change here eliterev site url according to  your site path
    define("ADMIN_ROOT","https://localhost/EliteMate/eliterev.com/admin/");		// change  here eliterev admin url according to  your site path
    define("ADMIN_EMAIL","vicd@rcn.com");
    define("PAYPAL_EMAIL","vicd@rcn.com");
} else {
    define("WWW_ROOT","http://www.elitesleeper.com/");
    define("ADMIN_ROOT","http://www.elitesleeper.com/admin/");
    define("ADMIN_EMAIL","vicd@rcn.com");
    define("PAYPAL_EMAIL","vicd@rcn.com");
}
?>
