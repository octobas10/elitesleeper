<?php
ob_start();
session_start();
include("config.php");
include("class/paging.php");
include("includes/connect.php");

?>
 <link href="js/kendo.common.min.css" rel="stylesheet" />
    <link href="js/kendo.default.min.css" rel="stylesheet" />
   <!-- <script src="js/jquery.min.js"></script>-->
    <script src="js/kendo.all.min.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">

<script>
function checkdate()
{
	fromDate = $( "#datepicker" ).val();
	toDate = $( "#datepicker2" ).val();
	if (Date.parse(fromDate) > Date.parse(toDate)) {
	alert("Invalid Date Range!\nStart Date cannot be after End Date!")
	return false;
	}
	if(fromDate=="")
	{
		alert("Start date cannot be empty!");
		return false;
	}
	if(toDate=="")
	{
		alert("End date cannot be empty!");
		return false;
	}
}
</script>
<style>
   
.k-animation-container, .k-widget, .k-widget *, .k-animation-container *, .k-widget *:before, .k-animation-container *:after, .k-block .k-header, .k-list-container {
    -moz-box-sizing: content-box;
    color: #000000;
}      
 </style>
</head>
<body>
<span class="dash_welcome view_welcome">To view the email relayer report, please select the date below and after that click on submit button</span>
<div class="cal_pic">
<form method="post" action="">
			From: <input id="datepicker" name="date1"  value="<?php echo @$_POST['date1']; ?>" style="width:150px;" />&nbsp;
			To: <input id="datepicker2" name="date2" value="<?php echo @$_POST['date2']; ?>" style="width:150px" />
			<input type="submit" name="submit" value="Submit" onclick="return checkdate()" class="default_btn"></form>
			</div>
        <script>
            $(document).ready(function() {
                // create DatePicker from input HTML element
                $("#datepicker").kendoDatePicker({
				format: "yyyy-MM-d"
				
				})
                $("#datepicker2").kendoDatePicker({
				format: "yyyy-MM-d"
				
				})
               
            });
        </script>
     </br></br>   
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   <?php 
		if(@$_POST['submit'])
		{
			$date = $_POST['date1'];
			$date1 = $_POST['date2'];
			$limit = $_POST['pageno'];
			$num_rows=0;

			if($date!="" && $date1!="")
			{
				$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM elitesleeper_email_count WHERE date BETWEEN '".$date."' AND '".$date1."'") or die(mysql_error());
			}
			else if($date!="" && $date1=="")
			{

				$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM elitesleeper_email_count WHERE date ='".$date."'") or die(mysql_error());
			}
			else if($date=="" && $date1!="")
			{

				$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM elitesleeper_email_count WHERE date ='".$date1."'") or die(mysql_error());
			}
			else{
				$sql = mysqli_query($GLOBALS['conn'],"SELECT * FROM elitesleeper_email_count") or die(mysql_error());
			}
		?>
		<?php echo "<h3> <font color=\"red\">Number of mail sent is  ".mysqli_num_rows($sql)."</font></h3><br>";?>
		 <table width="100%"  border="0" align="left" cellpadding="0" class="alt_table" cellspacing="1" class='admin-table'>
					<tr>
					<th  width='5%' align="left">
					S.No.
					</th>
					<th  width='10%' align="left">
					Email
					</th>
					
										
					<th  width='1%' align="left">
					Date 
					</th>
					
					</tr>
					<?php	
					
					if(mysqli_num_rows($sql))
					{
					while($row = mysqli_fetch_array($sql))
					{
				
					?>
					
					<tr>
					<td>
					<?php echo $num_rows+1;?>
					</td>
					<td>
					<?php echo $row['email'];?>
					</td>
				
					<td>
					<?php echo $row['date'];?>
					</td>
					

					
					
					</tr>
					<?php $num_rows++; }
					}
					
				if($num_rows == 0)
 				{ 
			?>
  		<tr><td colspan="8"><?="<font  align='centre' class='footer_pagingtext' >No Record Found !</font><br>"; ?>
		</td></tr>
		<?php
  				}
		else
		{			
		?> 
		<tr>
	  <td colspan="8" style="border:none;" align="left">
	<?php
			$pagingObj = new paging();
			echo $pagingObj->paginglink($lastpage,"paging_link",$pageno,"pagescrolling",false,false)
	?>
		</td>
	  </tr><?php
		}
		if($num_rows>0){
		?>
		<?php
		}?>
		</table>
		<?php
			
		}
       ?>
   
</table>