<?php include("admin/class/supression.php");

if (isset($_GET['yp'])) {

    $email_suppression = base64_decode($_GET['yp']);
    $date = $time = date("Y-m-d");

    $datauser['email'] = $email_suppression;
    $datauser['date'] = $date;
    //print_r($datauser);
    $obj_Supression = new Supression();
    $SuppressionUsers = $obj_Supression->suppressedusers($datauser);
    $message = "You have successfully unsubscribed.";

}


//include ("admin/class/dbClass.php");
//define('DBHOST', 'localhost');
//define('DBUSER', 'brain-www');
//define('DBPASS', 'wA76mEyUwr');
//define('DBNAME', 'db_elitecashmachine');
//$connect = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME) or die("Could not connect: " . mysqli_error());

//    mysqli_select_db(DBNAME, $connect) or die("Cannot select your database: " . mysqli_error());
//	$connect=mysqli_query($GLOBALS['conn'],DBHOST,DBUSER,DBPASS) or die("Could not connect: ".mysqli_error());
//	mysqli_select_db(DBNAME,$connect) or die("Cannot select your database: ".mysqli_error());
if (isset($_POST['addsuppression_x'])) {
    $email_suppression = $_POST['email_suppression'];
    $obj_Supression = new Supression();
    $exist_email = $obj_Supression->email_exists2($email_suppression);
    $site_id = '4';
    if ($exist_email == '1') {
        $msg_email_exist = "This Email Address Already Exist";
    } else {
        $data['email'] = $email_suppression;
        $data['site_id'] = $site_id;
        $SuppressionUsers = $obj_Supression->suppressedusers($data);
        //$data_Suppression = $obj_Supression->insertSupression($data);
        if ($site_id == '4') {
            $site = "EliteSleeper.com";
            $success = "The Email Address Has Been Added To " . $site . "'s Suppression List";

        }
    }
}
?>
<script language="javascript">
    function validate() {
        if (document.getElementById('email_suppression').value == "") {
            alert("Please enter a email address");
            document.getElementById('email_suppression').focus();
            return false;
        } else if (!document.getElementById('email_suppression').value.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/)) {
            alert("Please enter a valid email address");
            document.getElementById('email_suppression').focus();
            return false;
        }

    }
</script>

<?php if ($msg_email_exist != "") { ?>
    <tr>
        <td align="left"
            style='padding-left:18px;padding-top:10px;padding-bottom:10px;color:green;'><?= $msg_email_exist ?></td>
    </tr>
<?php } ?>
<?php if ($success != "") { ?>
    <tr>
        <td align="left"
            style='padding-left:18px;padding-top:10px;padding-bottom:10px;color:green;'><?= $success ?></td>
    </tr>
<?php } ?>


<?php if ($message != "") { ?>
    <tr>
        <td align="left"
            style='padding-left:18px;padding-top:10px;padding-bottom:10px;color:green;'><?= $message ?></td>
    </tr>
<?php } ?>


<tr>
    <td>
        <h1>Opt Out</h1>
        <form name="add_supression" action="" method="post">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="heading" align="left" valign="top" style='padding-top:5px;padding-bottom:5px;'
                        colspan="2">
                        Add Your Email Address To Be Removed From The Elite Sleeper List:
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top"
                        style='padding-top:5px;padding-bottom:5px;padding-left:20px;width:70px'>
                        <input type="text" name="email_suppression" id="email_suppression" size="40">
                    </td>

                    <td align="left" style='padding-top:5px;padding-bottom:5px;padding-left:10px;'>
                        <input type="image" name="addsuppression" id="addsuppression" src="images/submit1.gif" alt=""
                               width="80" height="27" border="0" class="leftorder" onClick="return validate();"/>
                    </td>
                </tr>
            </table>
        </form>

    </td>
</tr>



