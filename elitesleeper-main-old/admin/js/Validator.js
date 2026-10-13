/*
  -------------------------------------------------------------------------
	                    JavaScript Form Validator 
                                Version 2.0.2
	Copyright 2003 JavaScript-coder.com. All rights reserved.
	You use this script in your Web pages, provided these opening credit
    lines are kept intact.
	The Form validation script is distributed free from JavaScript-Coder.com

	You may please add a link to JavaScript-Coder.com, 
	making it easy for others to find this script.
	Checkout the Give a link and Get a link page:
	http://www.javascript-coder.com/links/how-to-link.php

    You may not reprint or redistribute this code without permission from 
    JavaScript-Coder.com.
	
	JavaScript Coder
	It precisely codes what you imagine!
	Grab your copy here:
		http://www.javascript-coder.com/
    -------------------------------------------------------------------------  
*/


function Validator(frmname)
{
  this.formobj=document.forms[frmname];
	if(!this.formobj)
	{
	  alert("BUG: couldnot get Form object "+frmname);
		return;
	}
	if(this.formobj.onsubmit)
	{
	 this.formobj.old_onsubmit = this.formobj.onsubmit;
	 this.formobj.onsubmit=null;
	}
	else
	{
	 this.formobj.old_onsubmit = null;
	}
	this.formobj.onsubmit=form_submit_handler;
	this.addValidation = add_validation;
	this.setAddnlValidationFunction=set_addnl_vfunction;
	this.clearAllValidations = clear_all_validations;
	this.clearValidation = clear_validation;
}
function set_addnl_vfunction(functionname)
{
  this.formobj.addnlvalidation = functionname;
}
function clear_all_validations()
{
	for(var itr=0;itr < this.formobj.elements.length;itr++)
	{
		this.formobj.elements[itr].validationset = null;
	}
}
function clear_validation()
{
	document.create_group.zip_code.validationset = null;

}
function form_submit_handler()
{
	for(var itr=0;itr < this.elements.length;itr++)
	{
		if(this.elements[itr].validationset &&
	   !this.elements[itr].validationset.validate())
		{
		  return false;
		}
	}
	if(this.addnlvalidation)
	{
	  str =" var ret = "+this.addnlvalidation+"()";
	  eval(str);
    if(!ret) return ret;
	}
	return true;
}
function add_validation(itemname,descriptor,errstr)
{ 
	


  if(!this.formobj)
	{
	  alert("BUG: the form object is not set properly");
		return;
	}//if
	var itemobj = this.formobj[itemname];
  if(!itemobj)
	{
	  alert("BUG: Couldnot get the input object named: "+itemname);
		return;
	}
	if(!itemobj.validationset)
	{
	  itemobj.validationset = new ValidationSet(itemobj);
	}
  itemobj.validationset.add(descriptor,errstr);
}
function ValidationDesc(inputitem,desc,error)
{
  this.desc=desc;
	this.error=error;
	this.itemobj = inputitem;
	this.validate=vdesc_validate;
}
function vdesc_validate()
{
 if(!V2validateData(this.desc,this.itemobj,this.error))
 {
 	if(this.itemobj.name=='genderchk'){
 		document.joinus_form.gender[0].focus();
 	}else{
    this.itemobj.focus();
 	}
		return false;
 }
 return true;
}
function ValidationSet(inputitem)
{
    this.vSet=new Array();
	this.add= add_validationdesc;
	this.validate= vset_validate;
	this.itemobj = inputitem;
}
function add_validationdesc(desc,error)
{
  this.vSet[this.vSet.length]= 
	  new ValidationDesc(this.itemobj,desc,error);
}
function vset_validate()
{
   for(var itr=0;itr<this.vSet.length;itr++)
	 {
	   if(!this.vSet[itr].validate())
		 {
		   return false;
		 }
	 }
	 return true;
}
function validateEmailv2(email)
{
// a very simple email validation checking. 
// you can add more complex email checking if it helps 
    if(email.length <= 0)
	{
	  return true;
	}
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
return false;
}

/* Function add for the remove all spaces */

// This method is used to remove leading spaces.
function removeLeadingSpaces(str)
{
   var whitespace = new String(" \t\n\r");
   var s = new String(str);

   if (whitespace.indexOf(s.charAt(0)) != -1)
    {
      var j=0, i = s.length;
      while (j < i && whitespace.indexOf(s.charAt(j)) != -1)  j++;
      s = s.substring(j, i);
    }
   return s;
}
// This method is used to remove Trailing spaces.
// It takes argument of the string which Trailing Spaces has to removed.
function removeTrailingSpaces(str)
{
   var whitespace = new String(" \t\n\r");
   var s = new String(str);

   if (whitespace.indexOf(s.charAt(s.length-1)) != -1)
   {
      var i = s.length - 1;       // Get length of string
      while (i >= 0 && whitespace.indexOf(s.charAt(i)) != -1) i--;

      // Get the substring from the front of the string to
      // where the last non-whitespace character is...
      s = s.substring(0, i+1);
   }

   return s;
}
// Removes both Leading and Trailing blanks.
function removeAllSpaces(str)
{
   str = removeLeadingSpaces(str); //Remove Leading Spaces
   str = removeTrailingSpaces(str); //Remove Trailing Spaces
   return str;
}


/* End */

function V2validateData(strValidateStr,objValue,strError) 
{ 
    var epos = strValidateStr.search("="); 
    var  command  = ""; 
    var  cmdvalue = ""; 
    if(epos >= 0) 
    { 
     command  = strValidateStr.substring(0,epos); 
     cmdvalue = strValidateStr.substr(epos+1); 
    } 
    else 
    { 
     command = strValidateStr; 
    } 
    switch(command) 
    { 
        case "req_gender":
         { 
         	var myOption = -1;
         	for (i=document.joinus_form.gender.length-1; i > -1; i--) {
			if (document.joinus_form.gender[i].checked) {
			myOption = i;
			}
			}
			if (myOption == -1) {
			  alert(strError); 
              document.joinus_form.gender[0].focus();
              return false; 
			} 
           break;             
         }
          case "req": 
        case "required": 
         { 
         	
           /*var myOption = -1;
         	for (i=document.joinus_form.gender.length-1; i > -1; i--) {
			if (document.joinus_form.gender[i].checked) {
			myOption = i;
			}
			}
			if (myOption == -1) {
			alert("You must select gender."); 
              return false; 
			}*/
			if(removeAllSpaces(objValue.value)=='' || eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 



		case "sel": 
        case "select": 
         { 
         	
			if (document.add.orderstatus1.value == 0 )
				{
			alert ( "Please select Order Status" );

			}
		break;             
         }//case required 
         
        case "required_textarea": 
         { 
		    //var oEditor = FCKeditorAPI.GetInstance('FCKeditor');
			var oEditor = FCKeditorAPI.GetInstance(objValue.id);
			var oDOM = oEditor.EditorDocument;
			var strFCKEditorText = "";
			if (document.all)// If I.E.
			{
			strFCKEditorText = oDOM.body.innerText;
			}
			else
			{
			var r = oDOM.createRange();
			r.selectNodeContents(oDOM.body);
			strFCKEditorText = r.toString();
			}
		   if(removeAllSpaces(strFCKEditorText)=='' || eval(strFCKEditorText.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
              alert(strError); 
              return false; 
           }
			//alert(strFCKEditorText);
          //alert(objValue.id+'-->'+objValue.name+'-->'+objValue.value);
           
           break;             
         }//case required 
        case "notrequired": 
         { 
            objValue.validationset = null;
            objValue.validationset = false;
            return true; 
          //if 
           break;             
         }//case required 
        
          case "req_checkbox": 
         { 
           if(!(objValue.checked)) 
           { 
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
          case "req_radio": 
         { 
          for (i=document.minister_form.retired.length-1; i > -1; i--) {
			if (document.minister_form.retired[i].checked) {
			myOption = i; i = -1;
			}
			}
			if (myOption == -1) {
			 alert(strError); 
              return false; 
			} 
           break;             
         }//case required 
          case "checkdob": 
         { 
			if (objValue.value=='' && document.joinus_form.day.value=='' && document.joinus_form.year.value=='') {
			 alert(strError); 
              return false; 
			}
           break;             
         }//case required 
        case "maxlength": 
        case "maxlen": 
          { 
             if(eval(objValue.value.length) >  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : "+cmdvalue+" characters maximum "; 
               }//if 
               alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false; 
             }//if 
             break; 
          }//case maxlen 
        case "minlength": 
        case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : " + cmdvalue + " characters minimum  "; 
               }//if               
               alert(strError ); 
               return false;                 
             }//if 
             break; 
            }//case minlen 
			case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : " + cmdvalue + " characters minimum  "; 
               }//if               
               alert(strError ); 
               return false;                 
             }//if 
             break; 
            }//case minlen 
			
        case "alnum": 
        case "alphanumeric": 
           { 
              var charpos = objValue.value.search("[^A-Za-z0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alpha-numeric characters allowed "; 
                }//if 
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
        case "num": 
        case "numeric": 
           { 
              var charpos = objValue.value.search("[^0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only digits allowed "; 
                }//if               
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break;               
           }//numeric 
            
        case "alphabetic": 
        case "alpha": 
           { 
              var charpos = objValue.value.search("[^A-Za-z]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alphabetic characters allowed "; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//alpha
           case "checktotal":
           {
           	EPSILON = 0.00000001;
			if(objValue.value > (100 + EPSILON)) {
				alert(strError);
				return false;
			}
			break;
           }
            case "percet": 
        case "percentage": 
           {  
           	var len = objValue.value.split(".");
           	var dotlength = len.length;
              var charpos = objValue.value.search("[^0-9\.]"); 
              if((objValue.value.length > 0 &&  charpos >= 0) || dotlength>2) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only digits and period allowed "; 
                }//if               
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }
              //if 
              break;               
           }//numeric 
		case "alnumhyphen":
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
			
			case "numhyphen":
			{
              var charpos = objValue.value.search("[^0-9\-\+]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are 0-9 and - "; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
			
			case "alnumspic": // added by ved
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_.\()@&$#]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
			case "alnumspicl": // added by anita
			{
              var charpos = objValue.value.search("[^A-Za-z0-9-_ ]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
			case "alnumspaces": // added by vikash on 13 march
			{
              var charpos = objValue.value.search("[^A-Za-z0-9 ]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9 and spaces"; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
			case "alnumwildcard": // added by ved
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_.\*@#%.]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
        case "email": 
          { 
               if(!validateEmailv2(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError = objValue.name+": Enter a valid Email address "; 
                 }//if                                               
                 alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case email 
		  case "alpnumpass": 
         {
			  
			  var charpos = objValue.value.search("[^0-9]"); 
              if(objValue.value.length > 0 &&  !isNaN(objValue.value) ) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Password must be a alpha-numeric combination "; 
                }//if  
				alert(strError);
                  return false; 
            }//if 
                      
            break; 
         }//case lessthan 

        case "lt": 
        case "lessthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
            if(eval(objValue.value) >=  eval(cmdvalue)) 
            { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : value should be less than "+ cmdvalue; 
              }//if               
              alert(strError); 
              return false;                 
             }//if             
            break; 
         }//case lessthan 
        case "gt": 
        case "greaterthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
             if(eval(objValue.value) <=  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : value should be greater than "+ cmdvalue; 
               }//if               
               alert(strError); 
               return false;                 
             }//if             
            break; 
         }//case greaterthan 
        case "regexp": 
         { 
		 	if(objValue.value.length > 0)
			{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = objValue.name+": Invalid characters found "; 
	              }//if                                                               
	              alert(strError); 
	              return false;                   
	            }//if 
			}
           break; 
         }//case regexp 
         case "regexp": 
         { 
		 	if(objValue.value.length > 0)
			{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = objValue.name+": Invalid characters found "; 
	              }//if                                                               
	              alert(strError); 
	              return false;                   
	            }//if 
			}
           break; 
         }//case confirmemail 
         
       case "doublevalid": // added by vikash on 09 Mar Jan 09
		{
          if(objValue.value.length > 0) 
          { 
            if(isNaN(objValue.value)) 
            { 
              strError = "Please enter only numeric format value"; 
              alert(strError); 
              return false; 
            }//if                             
           
          }//if 			
		break;
		} //case double
        case "confirmemail": 
         { 
            if(objValue.value != document.frmPass.borrowerEmail.value) 
            { 
              alert(strError); 
              return false;                                   
             }
             break; 
         }//case confirmemail 
         case "confirmpassword": 
         { 
            if(objValue.value != document.changepass.npasswd.value) 
            { 
              alert(strError); 
              return false;                                   
             }
             break; 
         }//case confirm password for change password      
         case "confirmpassword_reg": 
         { 
            if(objValue.value != document.frmPass.UserPassword.value) 
            { 
              alert(strError); 
              return false;                                   
             }
             break; 
         }//case confirm password for registration form
         case "uk_postcode": 
         { 
            if(objValue.value != '') 
            { return uk_postcode_validation(objValue.value);
              alert(strError); 
              return false;                                   
             }
             break; 
         }//case confirm password for registration form
    }//switch 
    return true; 
}
/*
	Copyright 2003 JavaScript-coder.com. All rights reserved.
*/
function DoCustomValidation()
{
  var frm = document.forms["myform"];
  if (frm.txt_regemail.value != frm.txt_regconfemail.value)
  {
  	alert('The Email and Confirm Email does not match!');
    return false;
  }
  else if(frm.txt_regpassword.value != frm.txt_regconfpassword.value)
  {
    alert('The Password and Confirm Password does not match!');
    return false;
  }
  else if(frm.txt_terms.checked == false)
  {
    alert('Please agree with the terms and conditions');
    return false;
  }
  else
  {
    return true;
  }
}

function DoCustomValidation2()
{
  var frm = document.forms["myform"];
 
  if(frm.txt_regpassword.value != frm.txt_regconfpassword.value)
  {
    alert('The Password and Confirm Password does not match!');
    return false;
  }
  else
  {
    return true;
  }
}
function DoCustomValidation3()
{
  var frm = document.forms["myform"];
 
  if(frm.fn.value == '' && frm.ln.value == '')
  {
    alert('Please enter either Forename or Last Name');
    return false;
  }
  else
  {
    return true;
  }
}

function DoCustomValidation4()
{
  var frm = document.forms["myform"];
 
  if(frm.hf.value == '' && frm.hl.value == '' && frm.wf.value == '' && frm.wl.value == '')
  {
    alert('Please enter atleast one Forename or Last Name');
    return false;
  }
  else
  {
    return true;
  }
}


function DoCustomValidation5()
{
  var frm = document.forms["myform"];
 
  if(frm.lname.value == '' )
  {
    alert('Please enter Last Name');
    return false;
  }
  else
  {
    return true;
  }
}

// Function to Confirm before a delete operation in the admin
function confirm_delete()
{
  if (confirm("Are you sure you want to delete?")==true)
    return true;
  else
    return false;
}	

// Below function is to toggle the advanced search in the admin for searching customers.
function toggleLayer(whichLayer)
{
	
if (document.getElementById)
{
// this is the way the standards work
var style2 = document.getElementById(whichLayer).style;
style2.display = style2.display? "":"block";
}
else if (document.all)
{
// this is the way old msie versions work
var style2 = document.all[whichLayer].style;
style2.display = style2.display? "":"block";
}
else if (document.layers)
{
// this is the way nn4 works
var style2 = document.layers[whichLayer].style;
style2.display = style2.display? "":"block";
}

if (style2.display){
	document.getElementById("showhide").innerHTML = "Hide Advanced Search";
	//alert("Hide");
}else{
	document.getElementById("showhide").innerHTML = "Show Advanced Search";
	//alert("Show");
}
}

// Function used in emp_modifydelete.php to check is a employee is clicked.
function radio_button_checker()
{
// set var radio_choice to false
var radio_choice = false;

// Loop from zero to the one minus the number of radio button selections
for (counter = 0; counter < emp_edit.txt_empid.length; counter++)
{
// If a radio button has been selected it will return true
// (If not it will return false)
if (emp_edit.txt_empid[counter].checked)
radio_choice = true; 
}

if (!radio_choice)
{
// If there were no selections made display an alert box 
alert("Please select an employee to edit.")
return (false);
}
return (true);
}

function checkCheckBoxes() 
{
	if (document.forms[0].CHKBOX_1.checked == false && 
		document.forms[0].CHKBOX_2.checked == false && 
		document.forms[0].CHKBOX_3.checked == false)
	{
	alert ('You didn\'t agree with the terms and conditions');
	return false;
	}
	else {
	return true;
	}
}

// THIS FUNCTION IS USED IN EMP_REPORT.PHP
function ActionDeterminator()
{
if(document.myform.re[0].checked == true) {
document.myform.action = 'emp_cust_rep_results.php';
}
if(document.myform.re[1].checked == true) {
document.myform.action = 'emp_inv_rep_results.php';
}
if(document.myform.re[2].checked == true) {
document.myform.action = 'emp_sea_rep_results.php';
}
return true;
}


function DoCustomValidation6()
{
  var frm = document.forms["myform"];
 
  if(frm.initial.value == '' && frm.lname.value == '')
  {
    alert('Please enter either Initial or Last Name');
    return false;
  }
  else
  {
    return true;
  }
}

// Function for select drop down validation
function checkDropdown() 
{

	 if ( document.add.cat_id.selectedIndex == 0 )
    {
        alert ( "Please select category first!" );
      }

}



// Function for add product price row
function addRowToProdPrice()
{
	
  var tbl = document.getElementById('tblWeb');
  var lastRow = tbl.rows.length;
  // if there's no header row in the table, then iteration = lastRow + 1
  var iteration = lastRow;
  var row = tbl.insertRow(lastRow);
  
  // right cell
  var cellRight = row.insertCell(0);
  cellRight.innerHTML = '<table width="100%" border=0 cellpadding="0" cellspacing="0"><tr><td width="40%" valign="top" align="right">Product territory & Price:</td><td width="60%" valign="top"><table width="100%" cellpadding="0" cellspacing="0"><tr><td height="22"><select name="country'+iteration+'"><option value="">-select-</option><?php echo"<option>-vvv</option>";?><option>-vvv22</option></select>&nbsp;&nbsp;<input type="text" name="state'+iteration+'" id="state'+iteration+'" size="10"></td></tr></table></td></tr><tr><td>&nbsp</td></tr></table>';  
  
}

// function to enable and disable start and end time with the checkbox
/*
function enable_validation(chkbx)
{
	
	var findIndex=chkbx.indexOf('_');		
	var getno=chkbx.substr(findIndex+1,chkbx.length);	
	var object = eval("document.frm1." + chkbx);
	var startTime="VideoStartTime"+getno;
	var endTime="VideoEndTime"+getno;
    
    if(object.checked)  //check whether any checkbox has been checked
	{		
		document.getElementById(startTime).disabled=false;
		document.getElementById(endTime).disabled=false;
		

	}else{ 		
		
		document.getElementById(startTime).disabled=true;
		document.getElementById(endTime).disabled=true;
		document.getElementById(startTime).value='';
		document.getElementById(endTime).value='';
		
	}
	

	
}
*/
// function to to validate form not to submit without any value

function validate_form()
{	 
	  var error=0;
	  var chkbx='';
	  var strError='';
      
	  //check whether any checkbox has been checked

	  for(n=0;n<document.frm1.elements.length-1;n++){
		
			if(document.frm1.elements[n].type=='checkbox'){
				if(document.frm1.elements[n].checked==true)
				{		
				  error=1;
				}
			}
	  };

	  if(error==0) //error message when any checkbox has not checked
	   {
           alert("Please select the enable link of specific audio to tag with the video !");
		   return false;
	   }
      
	  //checked the start and end time of selected checkbox

	  for(n=0;n<document.frm1.elements.length-1;n++){
		
			if(document.frm1.elements[n].type=='checkbox'){
				if(document.frm1.elements[n].checked==true)
				{		
				  chkbx=document.frm1.elements[n].name;
				  var findIndex=chkbx.indexOf('_');		
				  var getno=chkbx.substr(findIndex+1,chkbx.length);	
				  var startTime="VideoStartTime"+getno;
				  var endTime="VideoEndTime"+getno;
				  var startTimeValue=document.getElementById(startTime).value;
				  var endTimeValue=document.getElementById(endTime).value;

				  if(startTimeValue=='')  //check start time value
					{
                        alert("Please insert the start time of audio!");
						document.getElementById(startTime).focus();
		                return false;
					}else{                                                  //check valid start time value
						  
						  
						  var res=IsValidTime(startTimeValue);
						  if(res==false)
						  {
						  	document.getElementById(startTime).focus();
		                    return false;
						  }
						  
					}
					
				   if(endTimeValue=='')            //check end time value
					{
                        alert("Please insert the end time of audio!");
						document.getElementById(endTime).focus();
		                return false;
					}else{                                                    //check valid end time value
						 
						  var res2=IsValidTime(endTimeValue);
						  if(res2==false)
						  {
						  	document.getElementById(endTime).focus();
		                    return false;
						  }
					}
					if(startTimeValue!='' && endTimeValue!='')  //check start time value
					{
                        var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

						var matchArray1 = startTimeValue.match(timePat);
						var matchArray2 = endTimeValue.match(timePat);
						
						hour1 = parseInt(matchArray1[1]);
						minute1 = parseInt(matchArray1[2]);
						hour2 = parseInt(matchArray2[1]);
						minute2 = parseInt(matchArray2[2]);	
										
						if(hour2==hour1)
						{
							if(minute2<=minute1)
							{
								alert("End Time should always be greater than Start Time !");
								document.getElementById(endTime).focus();
				                return false;
							}
						}else{
							
							if(hour2<hour1)
							{
								alert("End Time should always be greater than Start Time !");
								document.getElementById(endTime).focus();
				                return false;
							}
						}
						//return false;
					}

				}
			}
	  };

document.frm1.submit()
return false;
}

/*
*this function is used to enable the checkbos on the focus of start time
*also it is used increment next start time from previous end time
*/
function enable_checkbox(chkId)
{
	var oldID='';
	var old_name='';
	box = eval("document.frm1.EnableLink_" + chkId); 
    if (box.checked == false) box.checked = true;
    	
    for(n=0;n<document.frm1.elements.length-1;n++){
		
			if(document.frm1.elements[n].type=='text'){
				if(document.frm1.elements[n].id=='VideoStartTime'+chkId){
					if(document.getElementById(oldID).value!=''){
					
					    var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

						var matchArray1 = document.getElementById(oldID).value.match(timePat);
						hour1 = parseInt(matchArray1[1]);
						minute1 = parseInt(matchArray1[2])
						if(minute1<59){
						    minute1 = minute1+parseInt(1);
						}else if(minute1==59){
						    minute1 = 0;
						    hour1 = hour1+parseInt(1);
						}else{
							hour1 = hour1+parseInt(1);
						}
						minute1 = minute1+'';
						hour1 = hour1+'';
												
						if(minute1.length==1)minute1='0'+minute1
						if(hour1.length==1)hour1='0'+hour1						
						
						var current_startID=document.frm1.elements[n].id;
						document.getElementById(current_startID).value=hour1+':'+minute1;
					}
					
					
				}
				
				oldID=document.frm1.elements[n].id;
				old_name=document.frm1.elements[n].name;
			}
			
	  };
    
}

/*
function to check valid time format
*/
function IsValidTime(timeStr) {
// Checks if time is in HH:MM:SS AM/PM format.
// The seconds and AM/PM are optional.

var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

var matchArray = timeStr.match(timePat);
if (matchArray == null) {
alert("Please insert the valid value of time e.g. 00:00 !");
return false;
}
hour = matchArray[1];
minute = matchArray[2];
second = matchArray[4];
ampm = matchArray[6];

if (second=="") { second = null; }
if (ampm=="") { ampm = null }

if (hour < 0  || hour > 23) {
alert("Hour must be between 0 and 23 for military time");
return false;
}
/*if (hour <= 12 && ampm == null) {
if (confirm("Please indicate which time format you are using.  OK = Standard Time, CANCEL = Military Time")) {
alert("You must specify AM or PM.");
return false;
   }
}*/
if  (hour > 12 && ampm != null) {
alert("You can't specify AM or PM for military time.");
return false;
}
if (minute<0 || minute > 59) {
alert ("Minute must be between 0 and 59.");
return false;
}
if (second != null && (second < 0 || second > 59)) {
alert ("Second must be between 0 and 59.");
return false;
}
return true;
}
function changepwd()
{
	
	if(document.getElementById("MemberOldPassword").value == "")
	{
		alert("Please Enter your Old password!");
		document.getElementById("MemberOldPassword").focus();
		return false;
	}
	
	if(document.getElementById("MemberPassword").value == "")
	{
		alert("Please Enter your New password!");
		document.getElementById("MemberPassword").focus();
		return false;
	}
	else
	{
	if(document.getElementById("MemberPassword").value.length<6)
	{ 
		alert("Minimum 6 Characters in Password!"); 
		document.getElementById("MemberPassword").focus();
		return false;
		}	
				
	}
	if(document.getElementById("MemberConpassword").value == "")
	{
		alert("Please confirm your new password!");
		document.getElementById("MemberConpassword").focus();
		return false;
	}
	if(document.getElementById("MemberPassword").value!=document.getElementById("MemberConpassword").value)
	{
		alert("New password and confirm password must be same!");
		document.getElementById("MemberConpassword").focus();
		return false;
	}		
	document.frmPass.submit();
}

//function to check UK postcode format is valid
function uk_postcode_validation(dataval)
{ 
	//alert(dataval);
 test = dataval; 
 size = test.length
 test = test.toUpperCase(); //Change to uppercase
 while (test.slice(0,1) == " ") //Strip leading spaces
  {test = test.substr(1,size-1);size = test.length
  }
 while(test.slice(size-1,size)== " ") //Strip trailing spaces
  {test = test.substr(0,size-1);size = test.length
  }
 //document.details.pcode.value = test; //write back to form field
 if (size < 6 || size > 8){ //Code length rule
  alert(test + " is not a valid UK post code - wrong length");
  
  return false;
  }
 if (!(isNaN(test.charAt(0)))){ //leftmost character must be alpha character rule
   alert(test + " is not a valid UK post code - cannot start with a number");
   
   return false;
  }
 if (isNaN(test.charAt(size-3))){ //first character of inward code must be numeric rule
   alert(test + " is not a valid UK post code - alpha character in wrong position");
   
   return false;
  }
 if (!(isNaN(test.charAt(size-2)))){ //second character of inward code must be alpha rule
   alert(test + " is not a valid UK post code - number in wrong position");
   
   return false;
  }
 if (!(isNaN(test.charAt(size-1)))){ //third character of inward code must be alpha rule
   alert(test + " is not a valid UK post code - number in wrong position");
   
   return false;
  }
 if (!(test.charAt(size-4) == " ")){//space in position length-3 rule
   alert(test + " is not a valid UK post code - no space or space in wrong position");
   
   return false;
   }
 count1 = test.indexOf(" ");count2 = test.lastIndexOf(" ");
 if (count1 != count2){//only one space rule
   alert(test + " is not a valid UK post code - only one space allowed");
   
   return false;
  }
//alert("Postcode Format OK");
return true;
}

// function to toggle the div added on 12 mar09 by vikash
function NewtoggleLayer(whichLayer) 
{
  var elem, vis;
  if(document.getElementById) // this is the way the standards work
    elem = document.getElementById(whichLayer);
  else if(document.all) // this is the way old msie versions work
      elem = document.all[whichLayer];
  else if(document.layers) // this is the way nn4 works
    elem = document.layers[whichLayer];
  vis = elem.style;
  // if the style.display value is blank we try to figure it out here
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}

//function to show the property iamge in popup
function popPropertyImage(sPic, bReturn)
{
   var sProps = "menubar,resizable,width=616,height=466";
   var propImageWin = window.open(sPic,"propertyImageWindow",sProps);
   if (propImageWin.focus) propImageWin.focus();
  return bReturn;
}

// function to open window
function openWindow( pageToLoad, winName, width, height, center) {
 xposition=0; yposition=0;
   if ((parseInt(navigator.appVersion) >= 4 ) && (center)){
       xposition = (screen.width - width) / 2;
       yposition = (screen.height - height) / 2;
    }
   args = "width=" + width + ","
    + "height=" + height + ","
    + "location=0,"
     + "menubar=0,"
     + "resizable=0,"
     + "scrollbars=0,"
     + "status=0,"
    + "titlebar=0,"
    + "toolbar=0,"
    + "hotkeys=0,"
     + "screenx=" + xposition + ","  
     + "screeny=" + yposition + ","  
    + "left=" + xposition + ","     
    + "top=" + yposition;           
     window.open( pageToLoad,winName,args );
 }

