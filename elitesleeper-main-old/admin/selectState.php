<?php
include("class/dbClass.php");
include("includes/connect.php");
include("class/order.php");
include("class/country.php");
if($_REQUEST['f']=='f')
{
	$id=$_REQUEST['p'];
	$cid=$_REQUEST['cid'];


	$userObj=new order();
	$allState=new country();
	$userResult = $userObj->fetchAll(" WHERE customer_id='$id'");
	$row=mysqli_fetch_array($userResult);
	$query=$allState->fetchAll($cid);
	$result2=$allState->selectState($row['bill_state']);?>
	<select name="bill_state" id="bill_state">
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
		
		
	echo "</select>";
}
if($_REQUEST['f']=='s')
{
	$id=$_REQUEST['p'];
	$cid=$_REQUEST['cid'];


	$userObj=new order();
	$allState=new country();
	$userResult = $userObj->fetchAll(" WHERE customer_id='$id'");
	$row=mysqli_fetch_array($userResult);
	$query=$allState->fetchAll($cid);
	$result=$allState->selectState($row['ship_state']);?>
	<select name="ship_state" id="ship_state">
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
		
		
	echo "</select>";
}
?>