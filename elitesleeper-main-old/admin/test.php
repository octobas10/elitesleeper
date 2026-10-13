<html>
<head><title>jQuery Open on image button</title>
<link rel="stylesheet" href="css/ui.css" type="text/css" media="screen" />
 
</head>
<body>
<form>
    <input id="date" type="textbox"/>
</form>
 
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
                $("#date").datepicker({ showOn: 'button', buttonImageOnly: true, buttonImage: 'images/icon_cal.png' });
        });
    </script>
</body>
</html>