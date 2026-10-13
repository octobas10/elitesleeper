<?php
/**
 * @author        Anil Kumar
 * @created       10/10/2016
 * @copyright     Braintechnosys Pvt. Ltd. Noida(India)
 * @updated
 */
ob_start();
session_start();
error_reporting(0);
include("config.php");
include("class/dbClass.php");
include("includes/connect.php");

?>
<?php
if(!isset($_SESSION["ADMIN_ID"])){
	echo "<script language='javascript'>window.parent.location= 'index.php'</script>";
}
 switch($_GET['mode']){



				case 'manageorder': $title='Manage Order for Elitesleeper'; break;
				case 'customerlist': $title='Customer List for Elitesleeper'; break;
				case 'viewcoregusers':$title='Viewcoregusers'; break;
				case 'editorder':$title='Edit Order for Elitesleeper'; break;
				case 'vieworder':$title='View Order for Elitesleeper'; break;
				case 'manageproduct': $title='Manage Cost for Elitesleeper'; break;
				case 'newslettertemplate':$title='Newsletter Template'; break;
				case 'addproduct': $title='Add Product for Elitesleeper'; break;
				case 'editproduct': $title='Edit Cost for Elitesleeper'; break;
				case 'pagemanage': $title='Manage Page for Elitesleeper'; break;
				case 'editpages':$title='Edit Page for Elitesleeper'; break;
				case 'inactiveuser':$title='Suprission list'; break;
				case 'coregusers':$title='Coreg Users list'; break;
				case 'viewPages':$title='View Page for Elitesleeper'; break;
				case 'managefaq': $title='Manage Faqs for Elitesleeper'; break;
				case 'editfaq': $title='Edit Faqs for Elitesleeper'; break;
				case 'viewfaq': $title='View Faqs for Elitesleeper'; break;
				case 'addfaq': $title='Add Faqs for Elitesleeper'; break;
				case 'shipingmanage': $title='Manage Shipping for Elitesleeper'; break;
				case 'changepassword': $title='Change Password for Elitesleeper'; break;
				case 'logout': $title='Logout  for Elitesleeper'; break;
				default: $title='Admin Panel for Elitesleeper';
            }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$title?></title>
<link href="css/justin_ryan.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="js/Validator.js"></script>

<style>

td{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; padding-left:0px;}
th{ background-color:#3E3E3E;border:#0173b9;color:#ffffff;font-weight:100;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; padding-left:3px;text-decoration:none;}
th a{ color:#ffffff;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; padding-left:3px;text-decoration:none;}

</style>

<script type="text/javascript" src="js/admin_validation.js"></script>
</head>

<body style="background:#FFFFFF">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><?php include('includes/header.php')?></td>
  </tr>

  <tr>
    <td align="center" ><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
        <tr>
            <td width="20%" height="350px" bgcolor="#233444" id="button-bg" valign="top" style="text-align:left; border:0;">
			<table cellspacing="0" cellpadding="0" width="99%" border="0"><tr><td>
			<?php include('includes/left_navigation.php')?>
			</td></tr></table>
			</td>
			<td width="80%" bgcolor="#ffffff" valign="top" align='left'>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            	<tr>
                	<td align="right" valign="top"><img src="images/box-top-left.gif" border="0" width="19" height="18" /></td>
                    <td style="background:url(images/box-top-center.gif) repeat-x; height:18px;"></td>
                    <td align="left" valign="top"><img src="images/box-top-right.gif" border="0" width="20" height="18" /></td>
                </tr>
                <tr>
                	<td style="background:url(images/box-mid-left.gif) repeat-y; width:19px;">&nbsp;</td>
                    <td style="width:98%; height:320px; min-height:320px;" valign="top">
                    	<?php
        	if(isset($_SESSION['ADMIN_ID'])){
			//To check what mode are need.
            switch($_GET['mode']){


            //   	case 'adduser': require("adduser.php"); break;
				case 'manageorder': require("manageorder.php"); break;
				case 'customerlist': require("customerlist.php"); break;
				case 'editorder': require("editorder.php"); break;
				case 'inactiveuser': require("inactiveuser.php"); break;
				case 'coregusers': require("coregusers.php"); break;
				case 'vieworder': require("vieworder.php"); break;
                case 'viewcoregusers': require("viewcoregusers.php"); break;
				case 'manageproduct': require("product_manage.php"); break;
				case 'bannerlist': require("bannerlist.php"); break;
				case 'addbannerlist': require("addBannerList.php"); break;
				case 'editbannerlist': require("editBannerList.php"); break;
				case 'viewbannerlist': require("viewBannerList.php"); break;
				case 'newslettertemplate': require("newslettertemplate.php"); break;
				case 'emailrelayer': require("email_relayer.php"); break;
				case 'emailrelayeradmin': require("email_relayer_admin.php"); break;
				case 'editrelayer': require("editRelayer.php"); break;
				case 'addproduct': require("add_product.php"); break;
				case 'editproduct': require("edit_product.php"); break;
				case 'pagemanage': require("managepages.php"); break;
				case 'editpages': require("editpages.php"); break;
				case 'viewPages': require("viewpages.php"); break;
				case 'managefaq': require("managefaq.php"); break;
				case 'editfaq': require("editfaq.php"); break;
				case 'viewfaq': require("viewfaq.php"); break;
				case 'addfaq': require("addfaq.php"); break;
				case 'shipingmanage': require("shiping_manage.php"); break;
				case 'changepassword': require("changepassword.php"); break;
				case 'logout': require("logout.php"); break;
				default: require("home1.php");
            }
        }?>
                    </td>
                    <td style="background:url(images/box-mid-right.gif) repeat-y; height:19px;">&nbsp;</td>
                </tr>
                <tr>
                	<td align="right" valign="top"><img src="images/box-bot-left.gif" border="0" width="19" height="18" /></td>
                    <td style="background:url(images/box-bot-center.gif) repeat-x; height:18px;"></td>
                    <td align="left" valign="top"><img src="images/box-bot-right.jpg" border="0" width="20" height="18" /></td>
                </tr>
                </table>


			</td>
		<td>&nbsp;</td>
        </tr>
		</table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?php include('includes/footer.php')?>
</table>


</body>
</html>
