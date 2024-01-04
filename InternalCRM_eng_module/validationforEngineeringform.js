$(document).ready(function(){
	//alert('inside');
	document.getElementById("taskno_c").readOnly = true;
});


function check_form(EditView)
    {
		var statusVal = document.getElementById('status').value;
		//alert(statusVal);
		var end_date = document.getElementById('end_date_date').value;
		//alert(end_date);
		if((statusVal == "completed")&&(end_date == ""))
		{
			alert("Please Select End Date and Time.");
			return false;
		}
		else
		{
			return validate_form(EditView, '');
        }	
	
    }
	

function getcheckendDate(){
var endDate,
element = document.getElementById('end_date_date');

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
	var endHR = document.getElementById('end_date_hours').value;
    var endMin = document.getElementById('end_date_minutes').value;
	var endmeridiem = document.getElementById('end_date_meridiem').value;	
		
	var EndDT = endDate+" "+endHR+":"+endMin+" "+endmeridiem;
	
	const xhttp2 = new XMLHttpRequest();
		xhttp2.onload = function() {
		  var result = this.responseText;
		//alert(result);
		
		if(result == 1)
		{
			alert('The end date should not be less than or equal to the ticket created date..');
			
			   document.getElementById('end_date_date').value='';
			   document.getElementById('end_date_hours').value='';
			   document.getElementById('end_date_minutes').value='';
			   document.getElementById('end_date_meridiem').value='';
			  	
			return false;
			
		}
		
	  }	  
	  xhttp2.open("GET", "include/javascript/takeEndDateValidation_engineering.php?endDate="+EndDT+"&date_entered="+date_entered);
	  xhttp2.send();
	
	}	
}
getcheckendDate();
const sauravi4 = setInterval(function() {getcheckendDate()}, 20000);


function checkticketValidation(){
	//alert("inside");
	
    var value = document.getElementById("status").value;
	var date_enteredValCheck = document.getElementById('date_entered').innerHTML;
	var accounts_engdt_engineering_1_name = document.getElementById('accounts_engdt_engineering_1_name').value;
		//alert(accounts_engdt_engineering_1_name);
		
	
	if((value == 'completed')&& (date_enteredValCheck == "")){
		
        alert("Before creating new ticket, the ticket should not be closed. First create a new ticket and then close it.");
		document.getElementById('status').value = '';
		
    }
	else if((value == 'completed')&& (accounts_engdt_engineering_1_name == "")){
		
		alert("Plaese add account name first..");
		document.getElementById('status').value = "";
		
	}
	
 
}
checkticketValidation();