<?php
include("class/product.php");
include("class/country.php");


$pageno= $_REQUEST['pageno'];
if(isset($_POST['submit'])){	
	$data = array();
	@extract($_POST);
	$id = $country;
	$data['shipping_charge'] = trim($charge);
	
	$userObj  = new product();
	if($userObj->updateShiping($data,$id))
	{
		$msg = "Updated Successfully";
		//header("location:home.php?mode=manageproduct&pageno=$pageno");			
	}
	else
	{
		$msg = "Sorry! could not updated successfully";
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

<script language= "javascript">
function GetXmlHttpObject(handler)
{
  var objXMLHttp=null
  if (window.XMLHttpRequest)
  {
      objXMLHttp=new XMLHttpRequest()
  }
  else if (window.ActiveXObject)
  {
      objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
  }
  return objXMLHttp
}

function stateChanged()
{
  if (xmlHttp.readyState==4 && xmlHttp.status == 200)
  {
	  var res =xmlHttp.responseText;
		
		//res=res.split(' ').join('');m

		  document.getElementById("charge").value=res.replace(/(^\s*|\s*$)/g,'');
	  
  }
  else {
          
  }
}

function htmlData(id)
{
  
	//alert(id);
  xmlHttp = GetXmlHttpObject();

  if (xmlHttp==null)
  {
      alert ("Browser does not support HTTP Request");
      return;
  }
	
  url="shiping_use.php"+"?p="+id;
  
  xmlHttp.onreadystatechange = stateChanged;
  xmlHttp.open("POST",url,true) ;
  xmlHttp.send(null);
}
</script>


<script language="javascript">
function val2()
{
			var ch=0;
			
			if(document.getElementById('country').value=="0")
			{
				document.getElementById("div_country").style.display="block";
				document.getElementById('div_country').innerHTML="<font color='red' size='2'> Please select the country </font>";
				document.getElementById("country").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_country").style.display="none";

			}
			if(document.getElementById('charge').value=="" || !document.getElementById("charge").value.match("^[-+]?[0-9]*\.?[0-9]+$"))
			{
				document.getElementById("div_charge").style.display="block";
				document.getElementById('div_charge').innerHTML="<font color='red' size='2'> Please enter valid Shiping charges </font>";
				document.getElementById("charge").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_charge").style.display="none";

			}

		if(ch>0)
			return false;
		else
			return true;
}
</script>

</head>
<form name='shipingmanage' id='shipingmanage' action="<?php $_SERVER['PHP_SELF']?>" method='post' onSubmit="return val2();">
	
	<input type="hidden" name="id" value="<?=$id?>" />
	<input type="hidden" name="chk" id="chk" value="1" />
	
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="padding-bottom:54px;" class="admin-table">
    <tr><td colspan="4"class="text_heading">
			
			Shiping Management
			</td></tr>
    <tr><td style="color:red;"><?php echo $msg;$msg="";?></td></tr>
    <tr><td align="left" colspan="2" style="padding-top:10px; padding-left:20px" class='admin_text'>
	Country:<span class='red_14'></span><br>
	<select name="country" id ="country" onchange="return htmlData(this.value);">
	<option value="0" selected >--Select--</option>	
	<option value="1" <?php if($ship_country=='1'){echo "selected";}?>>	United States </option>
	<option value="2"  <?php if($ship_country=='2'){echo "selected";}?>>	Canada </option>

	</select><div id="div_country"></div>
	<br />
    <br />
	
    

	Shiping charge:<span class='red_14'></span><br>
	<input type='text' id='charge' name='charge' value='<?=$price?>'> <div id="div_charge"></div>
	<br />
    <br />
	
	<input type='submit' value='Edit' name='submit' id="submit_button">
    </td>
    </tr>
    </table>
</form>

	
