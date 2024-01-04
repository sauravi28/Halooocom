$(document).ready(function(){
	//alert('inside');
	document.getElementById("tat_time_c").readOnly = true;
	
	var case_type_value = document.getElementById("case_type_c").value;
	
    if(case_type_value == 'Health_Checkup'){
		
        document.getElementById("filename_new").style.display = 'block';

    }
    else {
        document.getElementById("filename_new").style.display = 'none';
    }
	
});

function getcheckContactsName(){
var name,
element = document.getElementById('account_name');
if (element != null) {
    name = element.value;
}
else {
    name = null;
	//alert("empty str");
}
	
//alert(name);
if(name != null){
	
	const xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
		  var result = this.responseText;
		//alert(result);
		
		if(result == 1)
		{
			alert("The SLA/AMC for Account "+name+" is expired..");
			
			document.getElementById('account_name').value='';
			document.getElementById('account_id').value='';
			
			return false;
			
		}
		
			
	  }	  
	  xhttp.open("GET", "include/javascript/takeContactsNameSlaDate.php?name="+name);
	  xhttp.send();
	}	
}
getcheckContactsName();
const sauravi = setInterval(function() {getcheckContactsName()}, 3000);
	

function getcheckContactsNameSupport(){
var nameVal,
element = document.getElementById('account_name');
if (element != null) {
    nameVal = element.value;
}
else {
    nameVal = null;
	//alert("empty str");
}
	
//alert(nameVal);
if(nameVal != null){
	
	const xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
		  var result = this.responseText;
		//alert(result);
		
		if(result == 1)
		{
			alert("The SLA/AMC for Account "+nameVal+" is not in support..");
			
			document.getElementById('account_name').value='';
			document.getElementById('account_id').value='';
			
			return false;
			
		}
		
			
	  }	  
	  xhttp.open("GET", "include/javascript/takeContactsNameSlaSupport.php?name="+name);
	  xhttp.send();
	}	
}
getcheckContactsNameSupport();
const sauravi1 = setInterval(function() {getcheckContactsNameSupport()}, 3000);



function getcheckContactsNameSlaTicket(){
var nameaccount,
element = document.getElementById('account_name');
if (element != null) {
    nameaccount = element.value;
}
else {
    nameaccount = null;
	//alert("empty str");
}
	
//alert(nameaccount);
if(nameaccount != null){
	
	const xhttp1 = new XMLHttpRequest();
		xhttp1.onload = function() {
		  var result = this.responseText;
		//alert(result);
		
		if(result == 1)
		{
			alert("The SLA/AMC for Account "+nameaccount+" ticket limit is exceeded..");
			
			document.getElementById('account_name').value='';
			document.getElementById('account_id').value='';
			
			return false;
			
		}
		
			
	  }	  
	  xhttp1.open("GET", "include/javascript/takeContactsNameSlaTicket.php?name="+nameaccount);
	  xhttp1.send();
	}	
}
getcheckContactsNameSlaTicket();
const sauravi2 = setInterval(function() {getcheckContactsNameSlaTicket()}, 3000);



function getcheckContactsNameempty(){
var nameaccountem,
element = document.getElementById('account_name');
if (element != null) {
    nameaccountem = element.value;
}
else {
    nameaccountem = null;
	//alert("empty str");
}
	
//alert(nameaccountem);
if(nameaccountem != null){
	
	const xhttp2 = new XMLHttpRequest();
		xhttp2.onload = function() {
		  var result = this.responseText;
		//alert(result);
		
		if(result == 1)
		{
			alert("Please add all Reuired information related to this "+nameaccountem+" account. To create the ticket..");
			
			document.getElementById('account_name').value='';
			document.getElementById('account_id').value='';
			
			return false;
			
		}
		
			
	  }	  
	  xhttp2.open("GET", "include/javascript/takeContactsNameSlaEmpty.php?name="+nameaccountem);
	  xhttp2.send();
	}	
}
getcheckContactsNameempty();
const sauravi3 = setInterval(function() {getcheckContactsNameempty()}, 3000);


function getcheckendDate(){
var endDate,
element = document.getElementById('end_date_c_date');

if (element != null) {
	
    endDate = element.value;
	
}
else {
    endDate = null;
	//alert("empty str");
}
//alert(endDate);
if(endDate != null){
	
	//alert(endDate);
	var date_entered = document.getElementById('date_entered').innerHTML;
		//alert(date_entered);
	var endHR = document.getElementById('end_date_c_hours').value;
    var endMin = document.getElementById('end_date_c_minutes').value;
	var endmeridiem = document.getElementById('end_date_c_meridiem').value;	
		
	var EndDT = endDate+" "+endHR+":"+endMin+" "+endmeridiem;
	
	const xhttp2 = new XMLHttpRequest();
		xhttp2.onload = function() {
		  var result = this.responseText;
		//alert(result);
		
		if(result == 1)
		{
			alert('The end date should not be less than or equal to the ticket created date..');
			
			   document.getElementById('end_date_c_date').value='';
			   document.getElementById('end_date_c_hours').value='';
			   document.getElementById('end_date_c_minutes').value='';
			   document.getElementById('end_date_c_meridiem').value='';
			   document.getElementById('tat_time_c').value ='';
					
			return false;
			
		}
		else if(result == 2)
		{
			alert("The ticket can't be close before 15 mins...");
			
			   document.getElementById('end_date_c_date').value='';
			   document.getElementById('end_date_c_hours').value='';
			   document.getElementById('end_date_c_minutes').value='';
			   document.getElementById('end_date_c_meridiem').value='';
			   document.getElementById('tat_time_c').value = '';
					
			return false;
		}
		else 
		{
			document.getElementById('tat_time_c').value = result;
			
		}
		
			
	  }	  
	  xhttp2.open("GET", "include/javascript/takeEndDateValidation.php?endDate="+EndDT+"&date_entered="+date_entered);
	  xhttp2.send();
	
	}	
}
getcheckendDate();
const sauravi4 = setInterval(function() {getcheckendDate()}, 22000);


function getcheck_TAT(){
var endDate,
element = document.getElementById('end_date_c_date');

if (element != null) {
    endDate = element.value;
}
else {
    endDate = null;
	//alert("empty str");
	}
//alert(endDate);
if(endDate != null){
	
	//alert(endDate);
	var date_entered = document.getElementById('date_entered').innerHTML;
		//alert(date_entered);
	var endHR = document.getElementById('end_date_c_hours').value;
    var endMin = document.getElementById('end_date_c_minutes').value;
	var endmeridiem = document.getElementById('end_date_c_meridiem').value;	
		
	var EndDT = endDate+" "+endHR+":"+endMin+" "+endmeridiem;
	
	const xhttp2 = new XMLHttpRequest();
		xhttp2.onload = function() {
		  var result = this.responseText;
		//alert(result);
		//document.getElementById('tat_time_c').value = result;
		
		if(result == 1)
		{
			alert("The ticket can't be close before 15 mins...");
			
			   document.getElementById('end_date_c_date').value='';
			   document.getElementById('end_date_c_hours').value='';
			   document.getElementById('end_date_c_minutes').value='';
			   document.getElementById('end_date_c_meridiem').value='';
			   document.getElementById('tat_time_c').value = '';
					
			return false;
		}
		else 
		{
			document.getElementById('tat_time_c').value = result;
			
		}
		
	  }	  
	  xhttp2.open("GET", "include/javascript/takeTAT_timeUpdate.php?endDate="+EndDT+"&date_entered="+date_entered);
	  xhttp2.send();
	
	}	
}
getcheck_TAT();
const sauravi5 = setInterval(function() {getcheck_TAT()}, 1000);


function check_form(EditView)
    {
	
		var state = document.getElementById('state').value;
		//alert(state);
		var end_date = document.getElementById('end_date_c_date').value;
		
		if((state == "Resolved" || state == "Closed")&& (end_date == ""))
		{
			alert("Please Select End Date and Time.");
			return false;
		}
		else
		{
			return validate_form(EditView, '');
        }	
	
    }

function checkCaseValidation(){
	
    var value = document.getElementById("state").value;
	var case_numberValCheck = document.getElementById('case_number').innerHTML;
	var date_enteredValCheck = document.getElementById('date_entered').innerHTML;
	var resolutionVal = document.getElementById('resolution').value;
	var lengthVal = resolutionVal.length;
	var case_type_ValCheck = document.getElementById("case_type_c").value;
	var filenameVal = document.getElementById("filename_file").value;
	//alert(filenameVal);
	
	//filenameVal = "C:\fakepath\HCR711_Task7.xlsx"; 
    var extension = filenameVal.split('.').pop(); 
    //alert(extension);
	
	if((value == 'Resolved' || value == 'Closed')&& (case_numberValCheck == "" && date_enteredValCheck == "")){
		
        alert("Before creating new cases, the case should not be closed. First create a new case and then close it.");
		document.getElementById('state').value = '';
		
    }
	else if((value == "Resolved" || value == "Closed")&& (lengthVal < '50')){
		
		alert("Plaese Add Resolution first then select state and Resolution should more than 50 char..");
		document.getElementById('state').value = "";
		
	}
	else if((value == "Resolved" || value == "Closed")&& (case_type_ValCheck == 'Health_Checkup')&& (filenameVal == "")){
		
		alert("Plaese Add Health Checkup File in PDF Format.");
		document.getElementById('state').value = "";
		
	}
	else if((value == "Resolved" || value == "Closed")&& (case_type_ValCheck == 'Health_Checkup')&& (extension !='pdf')){
		
		alert("Plaese Add Health Checkup File in PDF Format only.");
		document.getElementById('state').value = "";
		document.getElementById("filename_file").value = "";
		
	}
	
 
}
checkCaseValidation();

function checkCaseTypeHC(){
	
    var case_type_Val = document.getElementById("case_type_c").value;
	
    if(case_type_Val == 'Health_Checkup'){
		
        document.getElementById("filename_new").style.display = 'block';

    }
    else {
        document.getElementById("filename_new").style.display = 'none';
    }
}
checkCaseTypeHC();

