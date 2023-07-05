function checkDateOrTickets(){
	
    var valueDropdown,
	element = document.getElementById("support_tickets_or_date_c");
	
	if (element != null) {
		valueDropdown = element.value;
	}
	else{
			valueDropdown = null;
		}
	if(valueDropdown != null){
		if(valueDropdown == 'no_of_tickets'){
		   document.getElementById("no_of_tickets_c").style.display = 'block';
		   document.getElementById("sla_date_c").style.display = 'none';
		   document.getElementById("sla_date_c").value='';
		   document.getElementById("sla_date_c_trigger").style.display = 'none';
		   
		}
		else if(valueDropdown == 'sla_date'){
			document.getElementById("sla_date_c").style.display = 'block';
			document.getElementById("sla_date_c_trigger").style.display = 'block';
			document.getElementById("no_of_tickets_c").style.display = 'none';
			document.getElementById("no_of_tickets_c").value='';
		}
		else if(valueDropdown == ''){
			document.getElementById("no_of_tickets_c").style.display = 'none';
			document.getElementById("sla_date_c").style.display = 'none';
			document.getElementById("sla_date_c_trigger").style.display = 'none';
			document.getElementById("sla_date_c").value='';
			document.getElementById("no_of_tickets_c").value='';
			
		}
	}
	
}
checkDateOrTickets();



function getcheckDateOrTickets(){
var name,
element = document.getElementById('support_tickets_or_date_c');
var no_of_tickets_cVal = document.getElementById('no_of_tickets_c').value;
var sla_date_c = document.getElementById('sla_date_c').value;
		
if (element != null) {
    name = element.value;
}
else {
    name = null;
	//alert("empty str");
}
	
//alert(name);
if(name != null){
	
	if((name == "no_of_tickets")&& (no_of_tickets_cVal == ""))
		{
			alert("If you Select No of tickets option then Please Add No of ticket value");
			return false;
		}
	else if((name == "sla_date")&& (sla_date_c == "")){
		
			alert("If you Select SLA Date option then Please Add SLA Date value");
			return false;
		}
	
	}	
}
//getcheckDateOrTickets();
const sauravi = setInterval(function() {getcheckDateOrTickets()}, 5000);


/*function check_form(EditView)
    {
	
		var support_tickets_or_date_cval = document.getElementById('support_tickets_or_date_c').value;
		//alert(support_tickets_or_date_cval);
		var sla_date_cVal = document.getElementById('sla_date_c').value;
		var no_of_tickets_cVal = document.getElementById('no_of_tickets_c').value;
		
		if(( support_tickets_or_date_cval == "sla_date" || support_tickets_or_date_cval == "no_of_tickets" ) || (sla_date_cVal == "" || no_of_tickets_cVal== ""))
		{
			alert("If you Select SLA Date option then Please Add SLA Date value Or If you Select No of tickets option then Please Add No of ticket value.");
			return false;
		}
		else
		{
			return validate_form(EditView, '');
        }	
	
    }*/


