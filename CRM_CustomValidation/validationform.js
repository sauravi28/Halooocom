

function check_form(EditView){
	
		var state = document.getElementById('state').value;
		//alert(state);
		var end_date = document.getElementById('end_date_c_date').value;
		
		if((state == "Resolved" || state == "Closed")&& (end_date == "")){
			alert("Please Select End Date and Time.");
			return false;
		}
		else{
			return validate_form(EditView, '');
            }			
			
			
    }

