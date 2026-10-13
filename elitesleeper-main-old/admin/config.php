<?php
if($_SERVER['HTTP_HOST'] == 'localhost'){
    define("WWW_ROOT","https://localhost/EliteMate/elitesleeper.com/");    //Please dont remove last slashe            // change here eliterev site url according to  your site path
    define("ADMIN_ROOT","https://localhost/EliteMate/elitesleeper.com/admin/");		// change  here eliterev admin url according to  your site path
    define("ADMIN_EMAIL","support@eliterev.com");				// admin email address.
    define("PAYPAL_EMAIL","vicd@rcn.com");
    define("AFFILIATE_ROOT","http://www.elitecashmachine.com");	// change here elitecashmachine url according to  your site path
    define('LIMIT','50');
    define('BASE_PATH','https://localhost/EliteMate/elitesleeper.com/');
	define('PLAN_ID','Elitesleeper-12');
    define('FROM_NAME','elitesleeper');
	define('RECEIVER_EMAIL','vic@elitemate.com');
} else {
    define("WWW_ROOT","http://www.elitesleeper.com/");    //Please dont remove last slashe            // change here eliterev site url according to  your site path
    define("ADMIN_ROOT","http://www.elitesleeper.com/admin/");		// change  here eliterev admin url according to  your site path
    define("ADMIN_EMAIL","support@elitegarcinia.com");				// admin email address.
    define("PAYPAL_EMAIL","vicd@rcn.com");
    define("AFFILIATE_ROOT","http://www.elitecashmachine.com");	// change here elitecashmachine url according to  your site path
    define('LIMIT','50');
    define('BASE_PATH','http://www.elitesleeper.com/');
	define('PLAN_ID','Elitesleeper-12');
    define('FROM_NAME','elitesleeper');
	//define('RECEIVER_EMAIL','vic@elitemate.com');
	define('RECEIVER_EMAIL','pawan@braintechnosys.biz');
}
?>