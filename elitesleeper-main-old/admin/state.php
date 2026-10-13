<?php
include("class/dbClass.php");
include("includes/connect.php");
include("class/country.php");
if($_REQUEST['f']=='b')
{
	
	$cid=$_REQUEST['cid'];
	$allState=new country();
	$query=$allState->fetchAll($cid);

	if(!empty($query)) {?>

	<select name="bill_state" id="bill_state" class="textbox">
		<?php		
		foreach($query as $value)
		{			
			echo "<option value=".$value['id'].">".$value['name']."</option>";
		
		}
				
	echo "</select>";
	} else {
		echo "<input type='text' name='bill_state' id='bill_state' class='textbox'>";
	}
}
if($_REQUEST['f']=='s')
{
	$cid=$_REQUEST['cid'];
	$allState=new country();
	$query=$allState->fetchAll($cid);
	if(!empty($query)){
	?>
	<select name="ship_state" id="ship_state" class="textbox">
		<?php
		
		
		foreach($query as $value)
		{
			
			echo "<option value=".$value['id'].">".$value['name']."</option>";
		
		}
		
		
	echo "</select>";
}else {
		echo "<input type='text' name='ship_state' id='ship_state' class='textbox'>";
	}
}
?>