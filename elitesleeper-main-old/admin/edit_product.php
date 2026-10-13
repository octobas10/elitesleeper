<?php
include("class/product.php");
include("class/country.php");
$id = $_REQUEST['id'];
$pageno= $_REQUEST['pageno'];
if(isset($_POST['submit'])){	
	$data = array();
	@extract($_POST);
	$data['title'] = addslashes(trim($title));
	$data['price'] = addslashes(trim($price));
	$data['shipping_charge'] = addslashes(trim($shipping_charge));
	
	$userObj  = new product();
	if($userObj->updateProduct($data,'id',$id))
	{
		$msg = "<font class='msg_success'>Record has been updated successfully";
		$_SESSION['msg']=$msg;
		header("location:home.php?mode=manageproduct&pageno=$pageno");			
	}
	else
	{
	    $msg = "Sorry! could not updated successfully";
		//$_SESSION['msg']=$msg;
	}
 } else {
	$userObj = new product();
	$userResult = $userObj->fetchAll(" WHERE id='$id'");
	
	if(mysqli_num_rows($userResult)){	
		//@extract(mysqli_fetch_assoc($userResult));
		
		$row=mysqli_fetch_array($userResult);
		$title = $row['title'];
		$price = $row['price'];
		$shiping_charge= $row['shipping_charge'];
		
		
	}
}
?>
<head>


<script language="javascript">
function val2()
{
			var ch=0;
			
			if(document.getElementById('title').value=="")
			{
				document.getElementById("div_title").style.display="block";
				document.getElementById('div_title').innerHTML="<font color='red' size='2'> Please enter the Product title </font>";
				document.getElementById("title").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_title").style.display="none";

			}
			if(document.getElementById('price').value=="" || !document.getElementById("price").value.match("^[-+]?[0-9]*\.?[0-9]+$"))
			{
			
				document.getElementById("div_price").style.display="block";
				document.getElementById('div_price').innerHTML="<font color='red' size='2'> Please enter valid Price </font>";
				document.getElementById("price").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_price").style.display="none";

			}
			if(document.getElementById('shipping_charge').value=="" || !document.getElementById("shipping_charge").value.match("^[-+]?[0-9]*\.?[0-9]+$"))
			{
			
				document.getElementById("div_shipping_charge").style.display="block";
				document.getElementById('div_shipping_charge').innerHTML="<font color='red' size='2'> Please enter valid Price </font>";
				document.getElementById("shipping_charge").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_shipping_charge").style.display="none";

			}

		if(ch>0)
			return false;
		else
			return true;
}
</script>




</head>
<form name='editproduct' id='editproduct' action="<?php $_SERVER['PHP_SELF']?>" method='post' onSubmit="return val2();">
	
	<input type="hidden" name="id" value="<?=$id?>" />
	<input type="hidden" name="chk" id="chk" value="1" />
	
	<table width="100%" border="0" align="left"  cellpadding="0" cellspacing="0" style="padding-bottom:54px;" class="admin-table edit_product-table">
    <tr><td colspan="4"class="text_heading">
			
			Edit Cost
			</td></tr>
    <tr><td style="color:red;"><?php echo $msg;$msg="";?></td></tr>
	
	<tr>
<td align="right" class='admin_text'>
Product title:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='title' name='title' value="<?=$title?>"><div id="div_title"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Price:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='price' name='price' value="<?=$price?>"><div id="div_price"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Shiping charge:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='shipping_charge' name='shipping_charge' value="<?=$shiping_charge?>"><div id="div_shipping_charge"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
&nbsp;<span class='red_14'></span></td>
<td><input type='submit' value='Update' name='submit' id="submit_button"></td>
</tr>

	
    </td>
    </tr>
    </table>
</form>

	
