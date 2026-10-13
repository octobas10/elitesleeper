<?php
include("admin/class/dbClass.php");
include("admin/includes/connect.php");

include("admin/class/country.php");
if($_REQUEST['f']=='b')
{
	
	$cid=$_REQUEST['cid'];
	$allState=new country();
	$query=$allState->fetchAll($cid);?>
	<select name="state_B" id="state_B" style="width:135px;">
		<?php
		
		
		foreach($query as $value)
		{
			
			echo "<option value=".$value['id'].">".$value['name']."</option>";
		
		}
		
		
	echo "</select>";
}

if($_REQUEST['f']=='s')
{
	$cid=$_REQUEST['cid'];
	$allState=new country();
	$query=$allState->fetchAll($cid);?>
	<select name="state_S" id="state_S" style="width:135px;">
		<?php
		
		
		foreach($query as $value)
		{
			
			echo "<option value=".$value['id'].">".$value['name']."</option>";
		
		}
		
		
	echo "</select>";
}
?>