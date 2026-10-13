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
	
	$userObj  = new product();
	if($userObj->insertProduct($data))
	{
		$msg = "Product inserted Successfully";
		//header("location:home.php?mode=manageproduct&pageno=$pageno");			
	}
	else
	{
		$msg = "Sorry! could not inserted successfully";
	}
 } else {
	$userObj = new product();
	$userResult = $userObj->fetchAll(" WHERE id='$id'");
	
	if(mysqli_num_rows($userResult)){	
		//@extract(mysqli_fetch_assoc($userResult));
		
		$row=mysqli_fetch_array($userResult);
		$title = $row['title'];
		$price = $row['price'];
		
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
	
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="padding-bottom:54px;" class="admin-table">
    <tr><td colspan="4"class="text_heading">
			
			Add Product
			</td></tr>
    <tr><td style="color:red;"><?php echo $msg;$msg="";?></td></tr>
    <tr><td align="left" colspan="2" style="padding-top:10px; padding-left:20px" class='admin_text'>
	Product title:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='title' name='title' ><div id="div_title"></div>
	<br />
    <br />
	
    

	Price:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='price' name='price' ><div id="div_price"></div>
	<br />
    <br />
	
	<input type='submit' value='Edit Product' name='submit' id="submit_button">
    </td>
    </tr>
    </table>
</form>

	
