
//alert('Outside');
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

