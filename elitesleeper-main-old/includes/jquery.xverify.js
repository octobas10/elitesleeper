(function($) { 
	var apiKey = $.getApiKey();
	var affiliateid = '';
	var subaffiliateid = '';
	var phonetype = '';
	var emailstatus = '';
	
	var servicetimeout = 9000;
	
	var emailcallstatus = false;
	var namecallstatus = false;
	var ipcallstatus = false;
	var phonecallstatus = false;
	var addresscallstatus = false;
	
	var services = $.getServices();
	var serviceURL = "http://www.xverify.com/services/";
	var x_forms = [];
	function formatErrors(errors,msg){
		var formatedErrors = {};
		$.each(errors, function(i, error,msg){
			formatedErrors[error[0]] = msg;
		});
		return formatedErrors;
	}
	function verifyParameters(){
		var allVars = getUrlVars();
		v1 = allVars['v1'];
		v2 = allVars['v2'];
		
		if( jQuery.trim(v1) !='' && v1 != undefined)
		{
			affiliateid = jQuery.trim(v1);
			
			if( jQuery.trim(v2) !='' && v2 != undefined)
				subaffiliateid = v2;
		}
//		alert("AFF id" + affiliateid + "sub" + subaffiliateid);
	}
	function getUrlVars()
	{
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
		  hash = hashes[i].split('=');
		  vars.push(hash[0]);
		  vars[hash[0]] = hash[1];
		}
		return vars;
	}
	
	function checkServiceExist(serviceName){
		var serviceStr = services;
		var serviceArray = serviceStr.split(",");
		
		var return_value = false;
		for(var i=0; i<serviceArray.length; i++)
		{
		  if(serviceName == serviceArray[i])
		  {
			return_value = true;
			break;
		  }
		}
		return return_value;
		
	}
	
  $.xVerify = {
		serviceURI : serviceURL,
			email : function(email, callback, options) {
			//var options = options || {};			
			var url = serviceURL + "emails/verify/?type=json&apikey="+ apiKey + "&domain=elitemate.com&v1="+ affiliateid +"&v2="+ subaffiliateid + "&emailstatus="+ emailstatus +  "&callback=?";
			
			emailcallstatus = true;
			setTimeout(function() {	emailtimeout(email,callback);}, servicetimeout);

			$.getJSON(
					  url, {"email" : email}, 
					  function(json){
				var service_email = json["email"];
				if(service_email.error){
					service_email.errors_json = formatErrors(service_email.error,service_email.message);
				}
				emailcallstatus = false;
				callback(service_email);
			});
	  },
	  
	  yahooemail : function(email, callback, options) {
		  
		  var signedURL = makeSignedRequest("dj0yJmk9UTkwYnpXUzBTR0tMJmQ9WVdrOVdWZ3hWM1Y2TmpJbWNHbzlNalF4TmpRME56WXkmcz1jb25zdW1lcnNlY3JldCZ4PTg2","daa1c942b6edaeed9f26bd1591e2da41d0f99b9c","http://query.yahooapis.com/v1/yql?q=%20SELECT%20*%20FROM%20yahoo.identity%20WHERE%20yid%3D%22"+email+"%22&diagnostics=true&format=json&jsoncallback=?");
		
		$.getJSON(signedURL,
		null,function(yqlResults) {
			if(yqlResults.query.results == null){
			  emailstatus = 2;
			}else{
			  emailstatus = 1;
			}
		  $.xVerify.email(email,callback)
		  });

 


		  },
	  	ipVerify : function(ip, callback, options) {
			//var options = options || {};			
			var url = serviceURL + "ipdata/verify/?type=json&apikey="+ apiKey + "&v1="+ affiliateid +"&v2="+ subaffiliateid +"&callback=?";
			
			ipcallstatus = true;
			setTimeout(function() {	iptimeout(ip,callback);}, servicetimeout);

			$.getJSON(
					  url, {"ip" : ip}, 
					  function(json){
						var service_ip = json["ipdata"];
						if(service_ip.error){
							service_ip.errors_json = formatErrors(service_ip.error,service_ip.message);
						}
				ipcallstatus = false;		
				callback(service_ip);
			});
	  },
	  	phone : function(phone, callback, options){
			var url = serviceURL + "phone/verify/?type=json&apikey="+ apiKey + "&v1="+ affiliateid +"&v2="+ subaffiliateid + "&phonetype="+ phonetype +"&callback=?";
			
			phonecallstatus = true;
			setTimeout(function() {	phonetimeout(phone,callback);}, servicetimeout);
			
			$.getJSON(url, {"phone" : phone}, function(json){
				var service_phone = json["phone"];
				if(service_phone.error){
					service_phone.errors_json = formatErrors(service_phone.error,service_phone.message);
				}
				phonecallstatus = false;	
				callback(service_phone);
			});
		},
		
		address : function(address, callback, options){
			var options = options || {};
			var url = serviceURL + "address/verify/?type=json&apikey="+ apiKey + "&v1="+ affiliateid +"&v2="+ subaffiliateid + "&callback=?";
			
			addresscallstatus = true;
			setTimeout(function() {	addresstimeout(address,callback);}, servicetimeout);
			// TODO: Clean this up. 
			var formated_address = {"street" : address.street.val(), "zip" : address.zip.val()};
			if(address.city) formated_address["city"] = address.city.val();
			if(address.state) formated_address["state"] = address.state.val();
			
			$.getJSON(url, formated_address, function(json){
				var res_address = json["address"];
				if(res_address.error){
					res_address.errors_json = formatErrors(res_address.error);
				}
				
				addresscallstatus = false;
				callback(res_address);
			});
		},
		
		name : function(names, callback, options){
			var options = options || {};
			var url = serviceURL + "name/verify/?type=json&apikey="+ apiKey + "&v1="+ affiliateid +"&v2="+ subaffiliateid + "&callback=?";
			
			namecallstatus = true;
			setTimeout(function() {	nametimeout(names,callback);}, servicetimeout);
			
			// TODO: Clean this up. 
			var formated_name = {"firstname" : names.firstname.val(), "lastname" : names.lastname.val()};
			$.getJSON(url, formated_name, function(json){
				var res_name = json["name"];
				if(res_name.error){
					res_name.errors_json = formatErrors(res_name.error);
				}
				namecallstatus = false;
				callback(res_name);
			});
		},
		
		addField : function(values,callback, options) {
			var url = serviceURL +  "scoring/register?callback=?";
			$.getJSON(
					  url, {"value" : ''}, 
					  function(json){
						var time = json["time"];
						callback(time);
			});
	  },		
	};
	
	function emailtimeout(email,callback){
		
		if(emailcallstatus == true)
		{
			var res_name = new Object();
			res_name.error = false;
			res_name.address = email;
			callback(res_name);
		}
	}
	
	function nametimeout(names,callback){
		
		if(namecallstatus == true)
		{
			var res_name = new Object();
			res_name.error = false;
			res_name.last = names.lastname.val();
			res_name.first = names.firstname.val();								
			callback(res_name);
		}
	}
	
	
	function iptimeout(ip,callback){
		
		if(ipcallstatus == true)
		{
			var res_name = new Object();
			res_name.error = false;
			callback(res_name);
		}
	}
	
	
	function phonetimeout(phone,callback){
		
		if(phonecallstatus == true)
		{
			var res_name = new Object();
			res_name.error = false;
			callback(res_name);
		}
	}
	
	
	function addresstimeout(address,callback){
		
		if(addresscallstatus  == true)
		{
			var res_name = new Object();
			res_name.error = false;
			/*
			var formated_address = {"street" : address.street.val(), "zip" : address.zip.val()};
			if(address.city) formated_address["city"] = address.city.val();
			if(address.state) formated_address["state"] = address.state.val();
			*/
			
			res_name.zip = address.zip.val();
			res_name.address1 = address.street.val();
								
			if(address.city) res_name.city = address.city.val(); 
			if(address.state) res_name.state = address.state.val();
			
			callback(res_name);
		}
	}
	function addxVerifyTip(field){
		var loading_div_id = field.id + "_loadingmsg";
		var loading_div = "<div class='xverify_loading_msg' id='"+loading_div_id+"'><table class='xverify_msg'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table></div>";
		$("body").prepend(loading_div);

	}
	
	function checkEmailSyntax(str){
		str = jQuery.trim(str);
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return false
		 }

 		 return true	
	}
	
	function removexVerifyTip(field){
		var loading_div_id = field.id + "_loadingmsg";
		$("#"+loading_div_id).remove();

	}
	
	function initxVerifyTip(field, options){
		if(options == null){
			options = {message_position : "center right"};
		}
		var x_tip = $(field).tooltip({ 
		    // place tooltip on the right edge 
		    position: options.message_position, 
		    // a little tweaking of the position 
		    offset: [-2, 10], 
		    // use a simple show/hide effect 
		    effect: "fade", 
		    // custom opacity setting 
		    opacity: 0.7, 
		    // use this single tooltip element 
		    tip: '#' + field.id +"_loadingmsg",
		 		events: { input: "change, focus" },
				effect : "fade",
				api: true
		});
		return x_tip;
	}
	
	$.fn.xEmail = function(form, options){
	if(checkServiceExist('email'))
	{
		var x_form = form;
		var x_field_state = "unverified";
		var ex_tip = null;
		
		
		// TODO: Find out how to extend this
		var new_methods = {
			getxVerifyState : function(){ return x_field_state },
			getxVerifyTip : function(){ return x_tip },
			setxVerifyTip : function(tip){ x_tip = tip }
		}
		
		this.each(function(){
			var self = this, $self = $(this);
			addxVerifyTip(self);
			ex_tip = initxVerifyTip(self, { message_position : x_form.getMessagePos() });
			ex_tip.onBeforeHide(function(event){
				if(x_field_state != "valid"){
					event.preventDefault();
				}
			});
			$(self).blur(function(){
				if(x_field_state == "processing" || x_field_state == "invalid"){
					ex_tip.getTip().show();
				}
			});
			$(self).change(function(event){
				var form = $(self).parents('form:first')[0];
				var sub = null;
				if(x_form.disableSub()){
					var sub = $(form).find("input[type='submit']")[0];
				}
				if(sub == null)
				{
					var sub = $(form).find("input[type='image']")[0];
				}
				var ltip = ex_tip.getTip();
				if(checkEmailSyntax(self.value))
				{
					ltip.removeClass("xverify_error_msg");
					ltip.addClass("xverify_loading_msg");
					ltip.html("<table class='xverify_message'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table>");
				}
				else if(jQuery.trim(self.value) == '')
				{
					ltip.removeClass("xverify_loading_msg");
					ltip.addClass("xverify_error_msg");
					ltip.html("<p>Please enter email address</p>");
					return;
				}
				else
				{
					ltip.removeClass("xverify_loading_msg");
					ltip.addClass("xverify_error_msg");
					ltip.html("<p>Invalid Email Syntax</p>");
					return;
				}
				
				
				if(false && sub) sub.disabled = true;
				x_form.setFormState("processing");
				x_field_state = "processing";
				sendrequest = true;
				gdomains = getdomainfromemail(jQuery.trim(self.value));
				//ex_tip.show();
				if(gdomains == 'yahoo' || gdomains == 'rocketmail' || gdomains == 'ymail')
				{
					current_option = getYahooOption();
					sendrequest = false;
					
					if(current_option == 1)
					{
						$.xVerify.yahooemail(self.value, function(email){
						if(email.error){
							//var field = email.errors[0][0];
							var msg = x_form.getErrMsgs().email || email.message;
							var etip = ex_tip.getTip();
							etip.html("<p>"+msg+"</p>");
							etip.addClass("xverify_error_msg");
							etip.removeClass("xverify_loading_msg");
							x_form.setFormState("invalid");
							x_field_state = "invalid";
							action = x_form.attr("action");
						}else{
							x_form.setFormState("valid");
							x_field_state = "valid";
							if(false && sub) sub.disabled = false;
							$(self).val(email.address);
							ex_tip.hide();
						}
					});
					}					
					else if(current_option == 2)
					{
						sendrequest = true;
					}
					else if(current_option == 3)
					{
						sendrequest = true;
					}
					else if(current_option == 4)
					{
						sendrequest = true;
					}
				}
				
				if(sendrequest == true)
				{
					$.xVerify.email(self.value, function(email){
					if(email.error){
						//var field = email.errors[0][0];
						var msg = x_form.getErrMsgs().email || email.message;
						var etip = ex_tip.getTip();
						etip.html("<p>"+msg+"</p>");
						etip.addClass("xverify_error_msg");
						etip.removeClass("xverify_loading_msg");
						x_form.setFormState("invalid");
						x_field_state = "invalid";
						action = x_form.attr("action");
					}else{
						x_form.setFormState("valid");
						x_field_state = "valid";
						if(false && sub) sub.disabled = false;
						$(self).val(email.address);
						ex_tip.hide();
					}
				});
				}
			});
		});
		return this;
	}
	}
	
	function checkPhoneSyntax(str){
		if(str.length < 10){
		   return false
		}
		
		rePhoneNumber = new RegExp(/^[1-9]\d{2}\d{3}\d{4}$/);
		if (!rePhoneNumber.test(str)) {
          return false;
     	}
 		 return true	
	}
	// Start xform
		$.fn.xPhone = function(form, phone,selectphonetype){
		if(checkServiceExist('phone'))
		{	
			var x_form = form;
			var x_field_state = "unverified";
			var x_tip = null;
			var self = this, $self = $(this);
			
			var new_methods = {
				getxVerifyState : function(){ return x_field_state },
				getxVerifyTip : function(){ return x_tip },
				setxVerifyTip : function(tip){ x_tip = tip }
			}
			
			this.each(function(){
				var self = this, $self = $(this);
				addxVerifyTip(self);
					x_tip = initxVerifyTip(self, { message_position : x_form.getMessagePos() })
					x_tip.onBeforeHide(function(event){
						if(x_field_state != "valid"){
							event.preventDefault();
						}
					})
				
					$(self).blur(function(){
						if(x_field_state == "processing" || x_field_state == "invalid"){
							x_tip.show();
						}
					});
					
					function handleVerify(field){
						var form = $(field).parents('form:first')[0];
						var sub = null;
						if(x_form.disableSub()){
							var sub = $(form).find("input[type='submit']")[0];
						}
						if(sub == null)
						{
							var sub = $(form).find("input[type='image']")[0];
						}
						
						var num = null;
						var ltip = x_tip.getTip();
						num = self.value;
						phonetype = selectphonetype;
	
						if(checkPhoneSyntax(self.value))
						{
							ltip.removeClass("xverify_error_msg");
							ltip.addClass("xverify_loading_msg");
							ltip.html("<table class='xverify_message'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table>");
						}
						else if(jQuery.trim(self.value) == '')
						{
							ltip.removeClass("xverify_loading_msg");
							ltip.addClass("xverify_error_msg");
							ltip.html("<p>Please enter phone number</p>");
							return;
						}
						else
						{
							if(false && sub) sub.disabled = true;
							ltip.removeClass("xverify_loading_msg");
							ltip.addClass("xverify_error_msg");
							ltip.html("<p>Invalid Phone</p>");
							return;
						}
						
						if(num){
							var ltip = x_tip.getTip();
							ltip.removeClass("xverify_error_msg");
							ltip.addClass("xverify_loading_msg");
							ltip.html("<table class='xverify_msg'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table>");
							//x_tip.show();
							if(false && sub) sub.disabled = true;
							x_form.setFormState("processing");
							x_field_state = "processing";
							$.xVerify.phone(num, function(response_phone){
								//x_tip.show();
								if(response_phone.error){
									
									var msg = x_form.getErrMsgs().phone || response_phone.message;
									var etip = x_tip.getTip();
									etip.html("<p>"+msg+"</p>");
									etip.addClass("xverify_error_msg");
									etip.removeClass("xverify_loading_msg");
									x_form.setFormState("invalid");
									x_field_state = "invalid";
	
								}else{
									x_form.setFormState("valid");
									x_field_state = "valid";
									if(false && sub) sub.disabled = false;
									x_tip.hide();
								}
							});
						}
					}
					
					$(self).change(function(){
						handleVerify(self);
					});
			});
			return this;
		}
	}
		
		$.fn.xAddField = function(form, options){
			var x_form = form;
			$.xVerify.addField('', function(response_name){
					field = '<input type="hidden" name="start_time" value=' + response_name + ' />';
//					alert(field);
					x_form.append(field);
				});
				
			
		
		$.fn.xIpVerify = function(form, ip){
		if(checkServiceExist('ip_verify'))
		{	
			var x_form = form;
			var x_field_state = "unverified";
			var x_tip = null;
			var self = this, $self = $(this);
			
			var new_methods = {
				getxVerifyState : function(){ return x_field_state },
				getxVerifyTip : function(){ return x_tip },
				setxVerifyTip : function(tip){ x_tip = tip }
			}
			
			this.each(function(){
				var self = this, $self = $(this);
				addxVerifyTip(self);
					x_tip = initxVerifyTip(self, { message_position : x_form.getMessagePos() })
					x_tip.onBeforeHide(function(event){
						if(x_field_state != "valid"){
							event.preventDefault();
						}
					})
				
					$(self).blur(function(){
						if(x_field_state == "processing" || x_field_state == "invalid"){
							x_tip.show();
						}
					});
					
					function handleVerify(field){
						var form = $(field).parents('form:first')[0];
						var sub = null;
						if(x_form.disableSub()){
							var sub = $(form).find("input[type='submit']")[0];
						}
						if(sub == null)
						{
							var sub = $(form).find("input[type='image']")[0];
						}
						
						var num = null;
						var ltip = x_tip.getTip();
						num = self.value;
						
						ipvalue = checkIpVerifySyntax(self.value);
	
						if(ipvalue == true)
						{
							ltip.removeClass("xverify_error_msg");
							ltip.addClass("xverify_loading_msg");
							ltip.html("<table class='xverify_message'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table>");
						}
						else
						{
							if(false && sub) sub.disabled = true;
							ltip.removeClass("xverify_loading_msg");
							ltip.addClass("xverify_error_msg");
							ltip.html("<p>"+ ipvalue + "</p>");
							return;
						}
						
						if(num){
							var ltip = x_tip.getTip();
							ltip.removeClass("xverify_error_msg");
							ltip.addClass("xverify_loading_msg");
							ltip.html("<table class='xverify_msg'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table>");
							//x_tip.show();
							if(false && sub) sub.disabled = true;
							x_form.setFormState("processing");
							x_field_state = "processing";
							$.xVerify.ipVerify(num, function(response_ip){
								//x_tip.show();
								if(response_ip.error){
									
									var msg = x_form.getErrMsgs().ip || response_ip.message;
									var etip = x_tip.getTip();
									etip.html("<p>"+msg+"</p>");
									etip.addClass("xverify_error_msg");
									etip.removeClass("xverify_loading_msg");
									x_form.setFormState("invalid");
									x_field_state = "invalid";
	
								}else{
									x_form.setFormState("valid");
									x_field_state = "valid";
									if(false && sub) sub.disabled = false;
									x_tip.hide();
								}
							});
						}
					}
					
					$(self).change(function(){
						handleVerify(self);
					});
			});
			return this;
		}
		
		
		
	}
	}
	function checkAddressField(zip,street){
		zip = jQuery.trim( zip );
		street = jQuery.trim( street );
		if(zip == '' || street == '' ){
		   return false
		}
 		 return true	
	}
	
	function checkNameField(first,last){
		first = jQuery.trim( first );
		last = jQuery.trim( last );
		if(first == '' || last == '' ){
		   return false
		}
 		 return true	
	}
	
	function checkIpVerifySyntax (IPvalue) {
		errorString = "";
		theName = "IPaddress";
		var ipPattern = /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/;
		var ipArray = IPvalue.match(ipPattern);
		if (IPvalue == "0.0.0.0")
			errorString = errorString + theName + ': '+IPvalue+' is a special IP address and cannot be used here.';
		else if (IPvalue == "255.255.255.255")
			errorString = errorString + theName + ': '+IPvalue+' is a special IP address and cannot be used here.';
		if (ipArray == null)
			errorString = errorString + theName + ': '+IPvalue+' is not a valid IP address.';
		else 
		{
			for (i = 0; i < 4; i++) {
				thisSegment = ipArray[i];
				if (thisSegment > 255) {
					errorString = errorString + theName + ': '+IPvalue+' is not a valid IP address.';
					i = 4;
				}
				if ((i == 0) && (thisSegment > 255)) {
					errorString = errorString + theName + ': '+IPvalue+' is a special IP address and cannot be used here.';
					i = 4;
				}
			}
		}
		extensionLength = 3;
		if (errorString == "")
			return true;
		else
			return errorString;
	}
	
	$.fn.xAddress = function(form, address){
	if(checkServiceExist('address'))
	{
		var x_form = form;
		var x_addr_state = "unverified";
		var addr_x_tip = null;
		
		function getxVerifyState(){ return x_field_state };
		function getxVerifyTip(){ return addr_x_tip };
		function setxVerifyTip(tip){ 
			addr_x_tip = tip 
		};
		
		var ba = this;
		this.each(function(){
			var handleAddressChange = function(field){
				var sub = null;
				
				if(x_form.disableSub()){
					var sub = $(form).find("input[type='submit']")[0];
				}
				if(sub == null)
				{
					var sub = $(form).find("input[type='image']")[0];
				}
					
				if(false && sub) sub.disabled = true;
				if(address.zip.val() && address.street.val()){
					if(!getxVerifyTip()){
						addr_x_tip = initxVerifyTip(address.street, { message_position : x_form.getMessagePos() })
						addr_x_tip.onBeforeHide(function(event){
							if(x_addr_state != "valid"){
								event.preventDefault();
							}
						});
						
						
					}
					
					if(checkAddressField(address.zip.val(),address.street.val()))
					{
						var ttip = addr_x_tip.getTip();
						ttip.removeClass("xverify_error_msg");
						ttip.addClass("xverify_loading_msg");
						ttip.html("<table class='xverify_msg'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table>");
						if(!addr_x_tip.isShown()){
							addr_x_tip.show();
						}
					}
					else
					{	
						var atip = getxVerifyTip().getTip();
						atip.removeClass("xverify_loading_msg");
						atip.addClass("xverify_error_msg");
						atip.html("<p>Please enter required address field</p>");
						if(!addr_x_tip.isShown()){
							addr_x_tip.show();
						}
						return ;
					}
				
					
					

					$.xVerify.address(address, function(response_address){
						try {
							var sub = $(form).find("input[type='submit']")[0];
							//getxVerifyTip().show();
							if(response_address.error){
								
								var msg = x_form.getErrMsgs().address || response_address.message;

								var etip = addr_x_tip.getTip();
								etip.removeClass("xverify_loading_msg");								
								etip.addClass("xverify_error_msg");
								etip.html("<p>"+msg+"</p>");
								x_form.setFormState("invalid");
								x_field_state = "invalid";

							}else{
								
								address.zip.val(response_address.zip);
								address.street.val(response_address.address1);
								
								if(address.unit != "null"){
									if(response_address.unit){
										$(address.unit).val(response_address.unit);
									}
								} 
								
								if(address.city) $(address.city).val(response_address.city);
								if(address.state) $(address.state).val(response_address.state);
								
								
								var etip = addr_x_tip.getTip();
								x_form.setFormState("valid");
								x_field_state = "valid";
								if(false && sub) sub.disabled = false;
								etip.hide();
							}
							

						}catch(e){
							getxVerifyTip().hide();
						}
					});
				}
			}
			
			if(address.street && address.zip){
				addxVerifyTip(address.street);
				$(address.zip).change(function(){
					var x_addr_state = "processing";
					//handleAddressChange(address.street);
					$(address.street).trigger('change');
				});
				$(address.street).blur(function(){
					if(x_addr_state == "invalid"){
						getxVerifyTip().getTip().show();
					}
				});
				$(address.street).change(function(){
					var x_addr_state = "processing";
					handleAddressChange(address.street);
				});
				if(address.city){
					$(address.city).change(function(){
						var x_addr_state = "processing";
						handleAddressChange(address.street);
					});
				}
				if(address.state){
					$(address.state).change(function(){
						var x_addr_state = "processing";
						handleAddressChange(address.street);
					});
				}
			}
		});
	}
	}
	$.fn.xName = function(form, names){
	if(checkServiceExist('name'))
	{
		var x_form = form;
		var x_addr_state = "unverified";
		var addr_x_tip = null;
		
		function getxVerifyState(){ return x_field_state };
		function getxVerifyTip(){ return addr_x_tip };
		function setxVerifyTip(tip){ 
			addr_x_tip = tip 
		};
		
		var ba = this;
		this.each(function(){
			var handleNameChange = function(field){
				var sub = null;
				
				if(x_form.disableSub()){
					var sub = $(form).find("input[type='submit']")[0];
				}
				if(sub == null)
				{
					var sub = $(form).find("input[type='image']")[0];
				}
					
				if(false && sub) sub.disabled = true;
				if(names.lastname.val() && names.firstname.val()){
					if(!getxVerifyTip()){
						addr_x_tip = initxVerifyTip(names.firstname, { message_position : x_form.getMessagePos() })
						addr_x_tip.onBeforeHide(function(event){
							if(x_addr_state != "valid"){
								event.preventDefault();
							}
						});
						
						
					}
					
					if(checkNameField(names.firstname.val(),names.lastname.val()))
					{
						var ttip = addr_x_tip.getTip();
						ttip.removeClass("xverify_error_msg");
						ttip.addClass("xverify_loading_msg");
						ttip.html("<table class='xverify_msg'><tr valign='center'><td>Verifying</td><td><img src='http://www.xverify.com/images/loader.gif'/></td></tr></table>");
						if(!addr_x_tip.isShown()){
							addr_x_tip.show();
						}
					}
					else
					{	
						var atip = getxVerifyTip().getTip();
						atip.removeClass("xverify_loading_msg");
						atip.addClass("xverify_error_msg");
						atip.html("<p>Please enter required name field</p>");
						if(!addr_x_tip.isShown()){
							addr_x_tip.show();
						}
						return ;
					}
				
					
					

					$.xVerify.name(names, function(response_name){
						try {
							var sub = $(form).find("input[type='submit']")[0];
							//getxVerifyTip().show();
							if(response_name.error){
								
								var msg = x_form.getErrMsgs().address || response_name.message;

								var etip = addr_x_tip.getTip();
								etip.removeClass("xverify_loading_msg");								
								etip.addClass("xverify_error_msg");
								etip.html("<p>"+msg+"</p>");
								x_form.setFormState("invalid");
								x_field_state = "invalid";

							}else{
								
								names.lastname.val(response_name.last);
								names.firstname.val(response_name.first);
								
								var etip = addr_x_tip.getTip();
								x_form.setFormState("valid");
								x_field_state = "valid";
								if(false && sub) sub.disabled = false;
								etip.hide();
							}
							

						}catch(e){
							getxVerifyTip().hide();
						}
					});
				}
			}
			
			if(names.firstname && names.firstname){
				addxVerifyTip(names.firstname);
				$(names.firstname).blur(function(){
					if(x_addr_state == "invalid"){
						getxVerifyTip().getTip().show();
					}
				});
				$(names.firstname).change(function(){
					var x_addr_state = "processing";
					handleNameChange(names.firstname);
				});
				if(names.lastname){
					$(names.lastname).change(function(){
						var x_addr_state = "processing";
						handleNameChange(names.firstname);
					});
				}
			}
		});
	}
	}
	$.fn.xVerifyForm = function(fields, callback, options){
		var settings = options || {disableSub : true};
		settings.message_position = $.getMessagePos();
		verifyParameters();
		var x_form = this;
		var xverify_msg_position =  settings.message_position;
		var x_fields = [];
		var x_form_state = "unverified";
		var err_msgs = settings.error_messages || {};
		var newMethods = {
			getxVerifyFields : function(){ return x_fields },
			setFormState : function(state){ bite_form_state =  state },
			disableSub : function(){ return settings.disableSub},
			getFormState : function(){ return x_form_state },
			getErrMsgs : function(){ return err_msgs },
			getMessagePos : function(){ 
				return xverify_msg_position; 
			},
			isPending : function(){ return  (bite_form_state == "pending") }
		}
		$.extend(this, newMethods);
		
		this.each(function(){

			if(fields){
				if(fields.v1) affiliateid = fields.v1;
				if(fields.v2) subaffiliateid = fields.v2;
				
				if(fields.email) fields.email.xEmail(x_form);
				
				if(fields.phone) fields.phone.xPhone(x_form);
				
				if(fields.address) fields.address.xAddress(x_form);
				
				if(fields.name) fields.address.xAddress(x_form);

				
				
			}else{
		    $("input", this).filter(function(){
					var fid = this.id ? this.id : this.name;
					fid = fid.toLowerCase();
					
				  if(fid.indexOf("v1") >= 0 && affiliateid ==''){
					 affiliateid = this.value;
				  }
				  if(fid.indexOf("v2") >= 0  && subaffiliateid ==''){
					 subaffiliateid = this.value;
				  }
				  
				  if(fid.indexOf("email") >= 0){
						$(this).xEmail(x_form);
						x_fields.push(this);
				    return true;
				 }else if(fid.indexOf("ipverify") >= 0){
						$(this).xIpVerify(x_form);
						x_fields.push(this);
					  return true;
				}
				 else if(fid.indexOf("phone") >= 0){
						var phone = {number : $(this)}
						$(this).xPhone(x_form, phone,'');
						x_fields.push(this);
					  return true;
				}else if(fid.indexOf("cell") >= 0){
						var phone = {number : $(this)}
						$(this).xPhone(x_form, phone,'cell');
						x_fields.push(this);
					  return true;
				}else if(fid.indexOf("landline") >= 0){
						var phone = {number : $(this)}
						$(this).xPhone(x_form, phone,'landline');
						x_fields.push(this);
					  return true;
				}else if(fid.indexOf("voip") >= 0){
						var phone = {number : $(this)}
						$(this).xPhone(x_form, phone,'voip');
						x_fields.push(this);
					  return true;
				}else{
						return false;
					}
				});
			
				var address = {};
				var field_names = {
					street : ["street"],
					unit : ["apartment"],
					city : ["city"],
					state : ["state", "region"],
					zip : ["zip", "postal"]
				};

				$("input", this).filter(function(){
					var fid = this.id ? this.id : this.name;
					var field = this;
					$.each(field_names, function(attr, names){
						$(names).each(function(i,val){
							if(fid && (fid.toLowerCase().indexOf(val) >= 0)){
								if((fid.toLowerCase().indexOf("client") >= 0) || (fid.toLowerCase().indexOf("view") >= 0)){
									// do nothing
								}else{
									address[attr] = $(field);
								}
								return true;
							}
						})
					})
					return true;
				});
				
				$("select", this).filter(function(){
					var fid = this.id ? this.id : this.name;
					var field = this;
					$.each(field_names, function(attr, names){
						$(names).each(function(i,val){
							if(fid && (fid.toLowerCase().indexOf(val) >= 0)){
								if((fid.toLowerCase().indexOf("client") >= 0) || (fid.toLowerCase().indexOf("view") >= 0)){
									// do nothing
								}else{
									address[attr] = $(field);
								}
								return true;
							}
						})
					})
					return true;
				});
				
				if(!address["street"]){
					$("input", this).filter(function(){
						var fid = this.id ? this.id : this.name;
						var field = this;
						if(fid && (fid.toLowerCase() == "addr")){
							address["street"] = $(field);
						}
						return true;
					});
				}
				if(address.street && address.zip){
					$(address.zip).xAddress(x_form, address);
					x_fields.push(address.zip);
				}
				
				var username = {};
				var field_names = {
					firstname : ["firstname"],
					lastname : ["lastname"]
				};

				$("input", this).filter(function(){
					var fid = this.id ? this.id : this.name;
					var field = this;
					$.each(field_names, function(attr, names){
						$(names).each(function(i,val){
							if(fid && (fid.toLowerCase().indexOf(val) >= 0)){
								if((fid.toLowerCase().indexOf("client") >= 0) || (fid.toLowerCase().indexOf("view") >= 0)){
									// do nothing
								}else{
									username[attr] = $(field);
								}
								return true;
							}
						})
					})
					return true;
				});
				
				if(username.firstname && username.lastname){
					$(username.firstname).xName(x_form, username);
					x_fields.push(username.firstname);
				}
			}
	  });
	  
	  
	  $(this).xAddField(x_form);
		
		x_forms.push(x_form);
		return this;
	};
	
})(jQuery);

