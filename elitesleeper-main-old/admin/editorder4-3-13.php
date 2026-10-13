<?php
include("class/order.php");
include("class/country.php");
$id = $_REQUEST['id'];
$allState = new country();

if(isset($_POST['submit'])){	
	
	$state=$_POST['bill_state'];
	$state2=$_POST['ship_state'];
	$country=$_POST['bill_country'];
	$country2=$_POST['ship_country'];
	
	
	if($allState->checkState($state,$country)>0)
		$flag1='true';
	else
		$flag1='false';
	if($allState->checkState($state2,$country2)>0)
		$flag2='true';
	else
		$flag2='false';
	if($flag1=='true' && $flag2=='true') 
	{
		$data = array();
		
		@extract($_POST);
		$data['first_name'] = addslashes(trim($firstname));
		$data['last_name'] = addslashes(trim($lastname));
		$data['email_id'] = addslashes(trim($email));
		$data['password'] = addslashes(trim($password));
		$data['bill_phone'] = addslashes(trim($phone));
		$data['creation_date'] = trim($creation_date);
		$data['bill_country'] = trim($bill_country);
		$data['bill_state'] = trim($bill_state);
		$data['bill_city'] = trim($bill_city);
		$data['bill_street'] = trim($bill_street);
		$data['bill_zip'] = trim($bill_zip);
		$data['ship_street'] = trim($ship_street);
		$data['ship_city'] = trim($ship_city);
								
			$data['ship_country'] =trim($ship_country);
			$data['ship_state'] = trim($ship_state);
			$data['ship_zip'] = trim($ship_zip);
		

		$data['status'] = addslashes(trim($is_active));
		$userObj  = new order();
		if($userObj->updateOrder($data,'id',$id))
		{
			$msg = "Updated Successfully";
			 header("Refresh: 2; url=home.php?mode=manageorder");
			//header("location:home.php?mode=manageorder");			
		}
		else
		{
			$msg = "Sorry! could not updated successfully";
		}
	}
	else{
		$msg = "Please select the state according to country";
	}
 } 
 
	$userObj = new order();
	
	$userResult = $userObj->fetchAll("WHERE erev_customer.id='$id'");
	
	if(mysqli_num_rows($userResult)){	
		//@extract(mysqli_fetch_assoc($userResult));
		
		$row=mysqli_fetch_array($userResult);
		$firstname = $row['first_name'];
		$lastname = $row['last_name'];
		
		$bill_country = ucfirst(stripslashes($row['bill_country']));
		$phone= ucfirst(stripslashes($row['bill_phone']));
		$password= $row['password'];
		$creation_date = ucfirst(stripslashes($row['creation_date']));
							
		$email = stripslashes($row['email_id']);
		$bill_state = stripslashes($row['bill_state']);
		$bill_city = stripslashes($row['bill_city']);
		$bill_street = stripslashes($row['bill_street']);
		$bill_zip = stripslashes($row['bill_zip']);
		$ship_street = stripslashes($row['ship_street']);
		$ship_city = stripslashes($row['ship_city']);
							
		$ship_country = stripslashes($row['ship_country']);
		$ship_state = stripslashes($row['ship_state']);
		$ship_zip = stripslashes($row['ship_zip']);
		$status = stripslashes($row['status']);

		$payment_date = stripslashes($row['payment_date']);
		$transaction_id = stripslashes($row['transaction_id']);
		$payment_amount = stripslashes($row['payment_amount']);
		$payment_nature = stripslashes($row['payment_nature']);

		$is_active = $row['status'];
		$r_date = $r_date;
		
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
  if (xmlHttp.readyState==4)
  {
	  var res = xmlHttp.responseText;
	 // alert(res);
	  if(res==1){
		  document.getElementById("chk").value='0';
	  }
	  else{
		  document.getElementById("chk").value='1';
	  }


	
	  // txtResult will be filled with new page
      //document.getElementById("div_email1").innerHTML = xmlHttp.responseText;
	 
	  if(res==1)
	  {	
		document.getElementById("div_email_2").style.display="block";
		document.getElementById("div_email_2").innerHTML="<font color='red' size='2'>Email should be unique</font>";
		document.getElementById("div_email").style.display="none";	
		document.getElementById("email").focus();	  
	  }
	  else
	  {
		document.getElementById("div_email_2").style.display="none";

	  }
  }
  else {
          
  }
}

function htmlData(page,id)
{
  
	
  xmlHttp = GetXmlHttpObject();

  if (xmlHttp==null)
  {
      alert ("Browser does not support HTTP Request");
      return;
  }
	p=page;
  url="valid_email.php"+"?p="+p+"&id="+id;
  
  xmlHttp.onreadystatechange = stateChanged;
  xmlHttp.open("POST",url,true) ;
  xmlHttp.send(null);
}
function changeList(cid,id,f)
{
  
  xmlHttp = GetXmlHttpObject();

  if (xmlHttp==null)
  {
      alert ("Browser does not support HTTP Request");
      return;
  }
	p=id;
  url="selectState.php"+"?p="+p+"&cid="+cid+"&f="+f;
  if(f=='f')
  {
	  xmlHttp.onreadystatechange = stateChanged2;
  }
  if(f=='s')
  {
		xmlHttp.onreadystatechange = stateChanged3;
  }
  xmlHttp.open("POST",url,true) ;
  xmlHttp.send(null);
}
function stateChanged2()
{
  if (xmlHttp.readyState==4)
  {
	  var res = xmlHttp.responseText;
		
	  if(res)
	  {	
		document.getElementById("bill_state_select").style.display="block";
		document.getElementById("bill_state_select").innerHTML=res;
		
			  
	  }
	  else
	  {
		document.getElementById("bill_state_select").style.display="none";

	  }
  }
  else {
          
  }
}
function stateChanged3()
{
  if (xmlHttp.readyState==4)
  {
	  var res = xmlHttp.responseText;
		
	  if(res)
	  {	
		document.getElementById("ship_state_select").style.display="block";
		document.getElementById("ship_state_select").innerHTML=res;
		
			  
	  }
	  else
	  {
		document.getElementById("ship_state_select").style.display="none";

	  }
  }
  else {
          
  }
}
</script>

<script type="text/javascript" src="js/valid.js"></script>


</head>
<form name='edituser' id='edituser' action="<?php $_SERVER['PHP_SELF']?>" method='post' onSubmit="return val();">
	
	<input type="hidden" name="id" value="<?=$id?>" />
	<input type="hidden" name="chk" id="chk" value="1" />
	
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="padding-bottom:54px;" class="admin-table">
    <tr><td colspan="4"class="text_heading">
			
			Edit Order
			</td></tr>
    <tr><td style="color:red;"><?php echo $msg;$msg="";?></td></tr>
    <tr><td align="left" colspan="2" style="padding-top:10px; padding-left:20px" class='admin_text'>
	First name:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='firstname' name='firstname' value="<?=$firstname?>"><div id="div_fname"></div>
	<br />
    <br />
	
    

	Last name:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='lastname' name='lastname' value="<?=$lastname?>"><div id="div_lname"></div>
	<br />
    <br />
	
    
	
	Email:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='email' name='email' value="<?=$email?>" onchange="return htmlData(this.value,<?=$id ?>);">
	<div id="div_email"></div><div id="div_email_2"></div>
	<br />
    <br />
	Password:<span class='red_14'></span><br>
	<input class='textbox' type='password' id='pass' name='password' value="<?=$password?>"><div id="div_pass"></div>
	<br />
    <br />
	
	Creation date:<span class='red_14'></span><br><table>
	<tr><td>
	<input class='textbox' type='text' id='creation_date' name='creation_date' value="<?=$creation_date?>" onclick="showCalendarControl(document.getElementById('creation_date'));" readonly="readonly">
	</td><td><img src="cal/calender_ico.gif" alt="" width="20" height="23" border="0"  onclick="showCalendarControl(document.getElementById('creation_date'));" style="cursor:pointer;"  /></td></tr></table>
	<div id="div_creation_date"></div>
	<br />
    <br />
	Phone:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='phone' name='phone' value="<?=$phone?>"><div id="div_phone"></div>
	<br />
    <br />
	Street:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='bill_street' name='bill_street' value="<?=$bill_street?>"><div id="div_street"></div>
	<br />
    <br />
	Country:<span class='red_14'></span><br>
	<select name="bill_country" id ="bill_country" onChange="return changeList(this.value,<?=$id?>,'f');">
	<option value="0">--Select--</option>	
	<option value="1"<?php if($bill_country=='1'){echo "selected";}?>>	United States </option>
	<option value="2"<?php if($bill_country=='2'){echo "selected";}?>>	Canada </option>

	</select><div id="div_bill_country"></div>
	<br />
    <br />
	State:<span class='red_14'></span><br>
	<?php
	$query=$allState->fetchAll($row['bill_country']);
	$result2=$allState->selectState($row['bill_state']);
	?>
	<div id="bill_state_select"><select name="bill_state" id="bill_state" >
	<?php
	if($result2)
	{
		echo "<option value=".$result2['id'].">".$result2['name']."</option>";
	}
	else
	{
	echo "<option value='0'>--Select--</option>";
	}
	
	echo "<option value='0'>--Select--</option>";
	foreach($query as $value)
	{
		echo "<option value=".$value['id'].">".$value['name']."</option>";
	
	}
	
	?>
	</select></div>
	<div id="div_state"></div>
	<br />
    <br />
	City:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='bill_city' name='bill_city' value="<?=$bill_city?>"><div id="div_bill_city"></div>
	<br />
    <br />
	Zip:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='bill_zip' name='bill_zip' value="<?=$bill_zip?>"><div id="div_zip"></div>
	<br />
    <br />
	Ship street:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='ship_street' name='ship_street' value="<?=$ship_street?>"><div id="div_ship_street"></div>
	<br />
    <br />
	Ship country:<span class='red_14'></span><br>
	<select name="ship_country" id ="ship_country"  onChange="return changeList(this.value,<?=$id?>,'s');">
	<option value="0">--Select--</option>	
	<option value="1" <?php if($ship_country=='1'){echo "selected";}?>>	United States </option>
	<option value="2"  <?php if($ship_country=='2'){echo "selected";}?>>	Canada </option>

	</select><div id="div_ship_country"></div>
	<br />
    <br />
	<?php
	
	$query=$allState->fetchAll($row['ship_country']);
	$result=$allState->selectState($row['ship_state']);
	?>
	Ship state:<span class='red_14'></span><br>
	<div id="ship_state_select"><select name="ship_state" id="ship_state">
	<?php
	if($result)
	{
		echo "<option value=".$result['id'].">".$result['name']."</option>";
	}
	else
	{
	echo "<option value='0'>--Select--</option>";
	}
	echo "<option value='0'>--Select--</option>";
	foreach($query as $value)
	{
		echo "<option value=".$value['id'].">".$value['name']."</option>";
	
	}
	
	?>
	</select></div>
	<div id="div_ship_state"></div>
	<br />
    <br />
	Ship city:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='ship_city' name='ship_city' value="<?=$ship_street?>"><div id="div_ship_city"></div>
	<br />
    <br />
	Ship zip:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='ship_zip' name='ship_zip' value="<?=$ship_zip?>"><div id="div_ship_zip"></div>
	<br />
    <br />
	Transaction id :<span class='red_14'></span><br>
	<input class='textbox' type='text' id='tid' name='tid' value="<?=$transaction_id?>"readonly="readonly" style="disable:true;"><div id="div_ship_zip"></div>
	<br />
    <br />
	Payment Amount:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='p_amount' name='p_amount' value="<?=$payment_amount?>""readonly="readonly" style="disable:true;"><div id="div_ship_zip"></div>
	<br />
    <br />
	Payment Type:<span class='red_14'></span><br>
	<input class='textbox' type='text' id='ex_date' name='ex_date' value="<?=$payment_nature?>""readonly="readonly" style="disable:true;"><div id="div_ship_zip"></div>
	<br />
    <br />
	Status:<span class='red_14'></span><br>
	<input class='textbox' type='radio' name='is_active' value="0" <?php if($is_active=='0')echo "checked"?>>Pending
	<input class='textbox' type='radio' name='is_active' value="1" <?php if($is_active=='1') echo "checked"?>>Complete
	<br />
    <br />
	<input type='submit' value='Submit' name='submit' id="submit_button">
    </td>
    </tr>
    </table>
</form>
<script type="text/javascript" src="cal/calendarcontrol.js"></script>
<link type="text/css" href="cal/CalendarControl.css" rel="stylesheet" />		
