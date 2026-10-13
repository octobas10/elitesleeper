function val()
		{
				var ch=0;
			
			if(document.getElementById('firstname').value=="")
			{
				document.getElementById("div_fname").style.display="block";
				document.getElementById('div_fname').innerHTML="<font color='red' size='2'> Please enter the first name </font>";
				document.getElementById("firstname").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_fname").style.display="none";

			}
			if(document.getElementById('lastname').value=="")
			{
				document.getElementById("div_lname").style.display="block";
				document.getElementById('div_lname').innerHTML="<font color='red' size='2'> Please enter the last name </font>";
				document.getElementById("lastname").focus();
				ch++;
			}
			else
			{				
				document.getElementById("div_lname").style.display="none";

			}
			if(document.getElementById("div_email").style.display=="block"){			
				ch++;
			}			
			if(document.getElementById("div_email").value="" || !document.getElementById("email").value.match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$"))
			{
				document.getElementById("div_email").style.display="block";
				document.getElementById("div_email").innerHTML="<font color='red' size='2'>Please enter the valid email address </font>";
				document.getElementById("email").focus();
				ch++;
			}
			else
			{
				
				document.getElementById("div_email").style.display="none";				
			}

			if(document.getElementById("pass").value=="")
			{
				document.getElementById("div_pass").style.display="block";
				document.getElementById("div_pass").innerHTML="<font color='red' size='2'> Please enter the Password </font>" ;
				document.getElementById("pass").focus();
				ch++;
			}
			else{
				document.getElementById("div_pass").style.display="none";
			}
			if(document.getElementById("bill_country").value=="0")
			{
				document.getElementById("div_bill_country").style.display="block";
				document.getElementById("div_bill_country").innerHTML="<font color='red' size='2'> Please Select the country </font>" ;
				document.getElementById("bill_country").focus();
				ch++;
			}
			else{
				document.getElementById("div_bill_country").style.display="none";
			}
			if(document.getElementById("phone").value=="")
			{
				document.getElementById("div_phone").style.display="block";
				document.getElementById("div_phone").innerHTML="<font color='red' size='2'> Please enter the Phone number </font>" ;
				document.getElementById("phone").focus();
				ch++;
			}
			else{
				document.getElementById("div_phone").style.display="none";
			}
			if(document.getElementById("bill_street").value=="")
			{
				document.getElementById("div_street").style.display="block";
				document.getElementById("div_street").innerHTML="<font color='red' size='2'> Please enter the  street</font>" ;
				document.getElementById("bill_street").focus();
				ch++;
			}
			else{
				document.getElementById("div_ship_street").style.display="none";
			}
			if(document.getElementById("bill_state").value == "0")
			{
				document.getElementById("div_state").style.display="block";
				document.getElementById("div_state").innerHTML="<font color='red' size='2'> Please select the state</font>" ;
				document.getElementById("bill_state").focus();
				ch++;
			}
			else{
				document.getElementById("div_state").style.display="none";
			}
			if(document.getElementById("creation_date").value=="")
			{
				document.getElementById("div_creation_date").style.display="block";
				document.getElementById("div_creation_date").innerHTML="<font color='red' size='2'> Please enter the Order date</font>" ;
				document.getElementById("creation_date").focus();
				ch++;
			}
			else{
				document.getElementById("div_creation_date").style.display="none";
			}
			if(document.getElementById("bill_zip").value=="")
			{
				document.getElementById("div_zip").style.display="block";
				document.getElementById("div_zip").innerHTML="<font color='red' size='2'> Please Enter the zip code</font>" ;
				document.getElementById("bill_zip").focus();
				ch++;
			}
			else{
				document.getElementById("div_zip").style.display="none";
			}
				
			if(document.getElementById("ship_street").value=="")
			{
				document.getElementById("div_ship_street").style.display="block";
				document.getElementById("div_ship_street").innerHTML="<font color='red' size='2'> Please enter the ship street</font>" ;
				document.getElementById("ship_street").focus();
				ch++;
			}
			else{
				document.getElementById("div_ship_street").style.display="none";
			}
			if(document.getElementById("ship_country").value=="0")
			{
				document.getElementById("div_ship_country").style.display="block";
				document.getElementById("div_ship_country").innerHTML="<font color='red' size='2'> Please select the ship country</font>" ;
				document.getElementById("ship_country").focus();
				ch++;
			}
			else{
				document.getElementById("div_ship_country").style.display="none";
			}
			if(document.getElementById("ship_state").value == "0")
			{
				document.getElementById("div_ship_state").style.display="block";
				document.getElementById("div_ship_state").innerHTML="<font color='red' size='2'> Please select the state</font>" ;
				document.getElementById("ship_state").focus();
				ch++;
			}
			else{
				document.getElementById("div_ship_state").style.display="none";
			}
			if(document.getElementById("ship_zip").value=="")
			{
				document.getElementById("div_ship_zip").style.display="block";
				document.getElementById("div_ship_zip").innerHTML="<font color='red' size='2'> Please enter the zip code</font>" ;
				document.getElementById("ship_zip").focus();
				ch++;
			}
			else{
				document.getElementById("div_ship_zip").style.display="none";
			}
			if(document.getElementById("ship_city").value=="")
			{
				document.getElementById("div_ship_city").style.display="block";
				document.getElementById("div_ship_city").innerHTML="<font color='red' size='2'> Please enter the city</font>" ;
				document.getElementById("ship_city").focus();
				ch++;
			}
			else{
				document.getElementById("div_ship_city").style.display="none";
			}
			if(document.getElementById("bill_city").value=="")
			{
				document.getElementById("div_bill_city").style.display="block";
				document.getElementById("div_bill_city").innerHTML="<font color='red' size='2'> Please enter the city</font>" ;
				document.getElementById("bill_city").focus();
				ch++;
			}
			else{
				document.getElementById("div_bill_city").style.display="none";
			}
			if(document.getElementById("chk").value=='0')
			return false;
			
			
		if(ch>0)
				return false;
			else
				return true;
	}