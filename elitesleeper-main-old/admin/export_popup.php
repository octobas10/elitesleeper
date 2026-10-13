<?php
ob_start();
session_start();
include("config.php");
include("class/paging.php");
include("class/dbClass.php");
include("includes/connect.php");
?>
 <link href="js/kendo.common.min.css" rel="stylesheet" />
    <link href="js/kendo.default.min.css" rel="stylesheet" />
    <script src="js/jquery.min.js"></script>
    <script src="js/kendo.all.min.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css" />

<script>
function checkdate()
{
	fromDate = $( "#datepicker" ).val();
	toDate = $( "#datepicker2" ).val();
	if (Date.parse(fromDate) > Date.parse(toDate)) {
	alert("Invalid Date Range!\nStart Date cannot be after End Date!");
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
<strong>To Export the Coreg Users Detail, please select the date below and after that click on Export button</strong></br></br>
<form method="post" action="export.php">
			From: <input id="datepicker" name="date1"  value="<?php echo @$_POST['date1']; ?>" style="width:150px;" />&nbsp;
			To: <input id="datepicker2" name="date2" value="<?php echo @$_POST['date2']; ?>" style="width:150px" />
			<input type="submit" name="submit" value="Export" onclick="return checkdate()"></form>
        <script>
            $(document).ready(function() {
                // create DatePicker from input HTML element
                $("#datepicker").kendoDatePicker({
				format: "yyyy-MM-dd"
				
				})
                $("#datepicker2").kendoDatePicker({
				format: "yyyy-MM-dd"
				
				})
               
            });
        </script>
     </br></br>   
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
   
   
</table>