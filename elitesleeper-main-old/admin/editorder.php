<?php
session_start();
include("class/order.php");
include("class/country.php");
$id = $_REQUEST['id'];
$allState = new country();

if(isset($_POST['submit'])){	

$state=$_POST['bill_state'];
$state2=$_POST['ship_state'];
$country=$_POST['bill_country'];
$country2=$_POST['ship_country'];

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
$msg = "<font class='msg_success'>Order has been updated uccessfully.";
$_SESSION['msg']=$msg;
//header("Refresh: 2; url=home.php?mode=manageorder");
header("location:home.php?mode=manageorder");			
}
else
{
$msg = "Sorry! could not updated successfully";
}

} 

$userObj = new order();

$userResult = $userObj->fetchAll("WHERE elitesleeper_customer.id='$id'");

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
<!--<script type="text/javascript" src='js/jquery-1.2.6.js'></script>-->
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
function changeList(cid) {
		$.ajax({
		url:"state.php?f=b&cid="+cid+"",
		success:function (data){			
			$('#bill_state_select').html(data);
		}
	});
}
function changeList2(cid) {
		$.ajax({
		url:"state.php?f=s&cid="+cid+"",
		success:function (data){			
			$('#ship_state_select').html(data);
		}
	});
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
<tr><td class="text_heading">

Edit Order
</td></tr>
<!--<tr><td style="color:red;"><?php echo $msg;$msg="";?></td></tr>-->
<tr>
<td>

<table width="100%" border="0" cellpadding="0" cellspacing="5" align="center" >
<tr>
<td>
<tr>
<td align="right" class='admin_text'>
First name:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='firstname' name='firstname' value="<?=$firstname?>"><div id="div_fname"></div></td>
</tr>
<tr>
<td align="right" class='admin_text'>
Last name:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='lastname' name='lastname' value="<?=$lastname?>"><div id="div_lname"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Email:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='email' name='email' value="<?=$email?>" onchange="return htmlData(this.value,<?=$id ?>);">
<div id="div_email"></div><div id="div_email_2"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Password:<span class='red_14'></span></td>
<td><input class='textbox' type='password' id='pass' name='password' value="<?=$password?>"><div id="div_pass"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Creation date:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='creation_date' name='creation_date' value="<?=$creation_date?>" onclick="showCalendarControl(document.getElementById('creation_date'));" readonly="readonly">
<img src="cal/calender_ico.gif" alt=""  width="20" height="23" border="0"  onclick="showCalendarControl(document.getElementById('creation_date'));" style="cursor:pointer; vertical-align: bottom;"  /></td>
<div id="div_creation_date"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Phone:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='phone' name='phone' value="<?=$phone?>"><div id="div_phone"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Street:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='bill_street' name='bill_street' value="<?=$bill_street?>"><div id="div_street"></div></td>
</tr>


<tr>
<td align="right" class='admin_text'>
Country:<span class='red_14'></span></td>
<td><?php
$country_obj=new country();
$res=$country_obj->fetchCountry();
?>
<Select name="bill_country" id="bill_country" onchange="return changeList(this.value);" style="width:135px;">
<option value="<?php ?>" selected='selected'>--- Select---</option>
<?php
while($record=mysqli_fetch_array($res))
{
?>
<option value="<?php echo $record['id']; ?>"<?php if($bill_country==$record['id']){echo "selected";}?>> <?php  echo $record['name']; ?>   </option>

</option>;

<?php	}

?>
</select>
</select><div id="div_bill_country"></div></td>
</tr>



<tr>
<td align="right" class='admin_text'>
State:<span class='red_14'></span></td>
<td><?php
$query=$allState->fetchAll($row['bill_country']);
$result2=$allState->selectState($row['bill_state']);
?>
<div id="bill_state_select">

<?php 

//echo $state.'manish';
if(!empty($query)) {?>
<select name="bill_state" id="bill_state" >


<?php 

foreach($query as $value) {
if($value['id'] == $bill_state) {
$selected = 'selected = "Selected"';
} else {
$selected = '';
}
echo "<option ".$selected." value=".$value['id'].">".$value['name']."</option>";
}

?>
</select>
<?php } else {?>
<input type="text" name="bill_state" id="bill_state" value="<?php echo $bill_state ?>">
<?php }?>
</div>
<div id="div_state"></div></td>
</tr>



<tr>
<td align="right" class='admin_text'>
City:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='bill_city' name='bill_city' value="<?=$bill_city?>"><div id="div_bill_city"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Zip:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='bill_zip' name='bill_zip' value="<?=$bill_zip?>"><div id="div_zip"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Ship street:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='ship_street' name='ship_street' value="<?=$ship_street?>"><div id="div_ship_street"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Ship country:<span class='red_14'></span></td>
<td><?php
$country_obj=new country();
$res=$country_obj->fetchCountry();
?>
<Select name="ship_country" id="ship_country" onchange="return changeList2(this.value);" style="width:135px;">
<option value="<?php ?>" selected='selected'>--- Select---</option>
<?php
while($record=mysqli_fetch_array($res))
{
?>
<option value="<?php echo $record['id']; ?>"<?php if($ship_country==$record['id']){echo "selected";}?>> <?php  echo $record['name']; ?>   </option>

</option>;

<?php	}

?>
</select>

</select><div id="div_ship_country"></div></td>
</tr>




<?php

$query=$allState->fetchAll($row['ship_country']);
$result=$allState->selectState($row['ship_state']);
?>


<tr>
<td align="right" class='admin_text'>
Ship state:<span class='red_14'></span></td>
<td><div id="ship_state_select">

<?php 

if(!empty($query)) {?>
<select name="ship_state" id="ship_state" >


<?php 

foreach($query as $value) {
if($value['id'] == $ship_state) {
$selected = 'selected = "Selected"';
} else {
$selected = '';
}
echo "<option ".$selected." value=".$value['id'].">".$value['name']."</option>";
}

?>
</select>
<?php } else {?>
<input type="text" name="ship_state" id="ship_state" value="<?php echo $ship_state?>">
<?php }?>
</div>
<div id="div_ship_state"></div></td>
</tr>


<tr>
<td align="right" class='admin_text'>
Ship city:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='ship_city' name='ship_city' value="<?=$ship_street?>"><div id="div_ship_city"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Ship zip:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='ship_zip' name='ship_zip' value="<?=$ship_zip?>"><div id="div_ship_zip"></div></td>
</tr>


<tr>
<td align="right" class='admin_text'>
Transaction id :<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='tid' name='tid' value="<?=$transaction_id?>"readonly="readonly" style="disable:true;"><div id="div_ship_zip"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Payment Amount:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='p_amount' name='p_amount' value="<?=$payment_amount?>""readonly="readonly" style="disable:true;"><div id="div_ship_zip"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Payment Type:<span class='red_14'></span></td>
<td><input class='textbox' type='text' id='ex_date' name='ex_date' value="<?=$payment_nature?>""readonly="readonly" style="disable:true;"><div id="div_ship_zip"></div></td>
</tr>

<tr>
<td align="right" class='admin_text'>
Status:<span class='red_14'></span></td>
<td><input class='textbox check_boxes' type='radio' name='is_active' value="0" <?php if($is_active=='0')echo "checked"?>>Pending
<input class='textbox check_boxes' type='radio' name='is_active' value="1" <?php if($is_active=='1') echo "checked"?>>Complete</td>
</tr>
<tr>
<td align="right" class='admin_text'>
&nbsp;<span class='red_14'></span></td>
<td>&nbsp;</td>
</tr>


<tr>
<td align="right" class='admin_text'>
&nbsp;<span class='red_14'></span></td>
<td><input type='submit' value='Submit' name='submit' id="submit_button"></td>
</tr>
</td>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
<script type="text/javascript" src="cal/calendarcontrol.js"></script>
<link type="text/css" href="cal/CalendarControl.css" rel="stylesheet" />		
