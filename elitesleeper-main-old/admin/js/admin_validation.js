function validate(form)
	{
		var check=0;
		if(document.getElementById('first_name').value==''){
			document.getElementById('first_div').innerHTML="<font color=red>Please enter title.</font>";
			document.getElementById('first_div').style.display='block';
			check=1;
			
		}
		if(document.getElementById('email').value==''){
			document.getElementById('email_div').innerHTML="<font color=red>Please enter description.</font>";
			document.getElementById('email_div').style.display='block';
			check=1;
		}
		if(document.getElementById('state').value==""){
			document.getElementById('state_div').innerHTML="<font color=red>Please enter location.</font>";
			document.getElementById('state_div').style.display='block';
			check=1;
		}
		if(check==1){
			return false;
		}
		return true;
	}
	
