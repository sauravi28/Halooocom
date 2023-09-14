<?php    

require("dbconnect_mysqli.php");

$action = $_REQUEST['action']; 
$attempt_type = $_REQUEST['attempt_type']; 
$attempt_status = $_REQUEST['attempt_status']; 
$disposition = $_REQUEST['disposition']; 
$sub_disposition1 = $_REQUEST['sub_disposition1']; 
$sub_disposition2 = $_REQUEST['sub_disposition2']; 

if($action=="attempt_type")
{
	if($attempt_type!="")
	{
		$attempt_statuses = "<option value='' selected disabled>Select Attempt Status</option>";

		$att_sts = "Contact P,Contact N,No Contact";

		$attempt_status_array = explode(",",$att_sts);

		foreach($attempt_status_array as $attempt_status_value)
		{
			$attempt_statuses .= "<option value=\"$attempt_status_value\">$attempt_status_value</option>";
		}

		echo $attempt_statuses;
	}
}
elseif($action=="attempt_status")
{
	$dispositions = "<option value='' selected disabled>Select Disposition</option>";

	if($attempt_type=="Telecalling")
	{
		if($attempt_status=="Contact P")
		{
			$disposition = "PTP,FPTP,BRPTP,PAID,Issue,DNC";
		}
		elseif($attempt_status=="Contact N")
		{
			$disposition = "CB,EXPRD,LM";
		}
		elseif($attempt_status=="No Contact")
		{
			$disposition = "NC,RNR,WTR";
		}
	}
	
	else
	{
		$disposition = "";
	}

	$disposition_array = explode(",",$disposition);

	foreach($disposition_array as $dispo_value)
	{
		$dispositions .= "<option value=\"$dispo_value\">$dispo_value</option>";
	}

	echo $dispositions;
}
elseif($action=="disposition")
{
	$sub_dispos1 = "<option value='' selected disabled>Select Sub Disposition1</option>";

	if($attempt_type=="Telecalling" && $attempt_status=="Contact P")
	{		
		if($disposition=="PTP" || $disposition=="NCHP" || $disposition=="NCHN" || $disposition=="RES - A" || $disposition=="RES - NI")
		{
			//$sub_dispo1_value="";
			//$sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
		}
		elseif($disposition=="FPTP")
		{
			$sub_dispo1 = "Expecting Salary,Arranging Amount,Unable to Pay,Other Reason";
			$sub_dispo1_array = explode(",",$sub_dispo1);
			foreach($sub_dispo1_array as $sub_dispo1_value)
			{
				$sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
			}
		}
                elseif($disposition=="BRPTP")
                {
                        $sub_dispo1 = "Amount Issue,Unable to Pay,No Amount";
                        $sub_dispo1_array = explode(",",$sub_dispo1);
                        foreach($sub_dispo1_array as $sub_dispo1_value)
                        {
                                $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                        }
                }
                elseif($disposition=="PAID")
                {
			$sub_dispo1 = "Customer Claims Paid,EMI,Pre Closure,Settlement";
                        $sub_dispo1_array = explode(",",$sub_dispo1);
                        foreach($sub_dispo1_array as $sub_dispo1_value)
                        {
                                $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                        }
                }
                elseif($disposition=="Issue")
                {
                        $sub_dispo1 = "Medical Issue,Financial,Refuse to pay,Dispute,Any other reason,Preclosure issue";
                        $sub_dispo1_array = explode(",",$sub_dispo1);
                        foreach($sub_dispo1_array as $sub_dispo1_value)
                        {
                                $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                        }
                }
		elseif($disposition=="DNC")
		{
			$sub_dispo1_value = "Do not call";
			$sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
		}
	}
	elseif($attempt_type=="Telecalling"&& $attempt_status=="Contact N")
	{
		$sub_dispo1 = "";
		if($disposition=="CB")
                {
                        $sub_dispo1_value = "Call Back";
                        $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                }
		elseif($disposition=="EXPRD")
		{
			$sub_dispo1_value = "Deceased";
			$sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
		}
                elseif($disposition=="LM")
                {
                        $sub_dispo1_value = "Left Message";
                        $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                }
	}
	elseif($attempt_type=="Telecalling" && $attempt_status=="No Contact")
	{
		$sub_dispo1 = "";
                if($disposition=="NC" || $disposition=="RNR" || $disposition=="WTR")
                {
                        $sub_dispo1_value = "No contact";
                        $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                }
	}
        elseif($attempt_type=="Field" && $attempt_status=="No Contact")
        {
                $sub_dispo1 = "";

                if($disposition=="Skip")
                {
                        $sub_dispo1_value = "Skip";
                        $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                }
		elseif($disposition=="HL")
                {
                        $sub_dispo1_value = "House Lock";
                        $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                }
                elseif($disposition=="WTR")
                {
                        $sub_dispo1_value = "No contact";
                        $sub_dispos1 .= "<option value=\"$sub_dispo1_value\">$sub_dispo1_value</option>";
                }
        }

	echo $sub_dispos1;

}
elseif($action=="subdisposition1")
{

	$sub_dispos2 = "<option value='' selected disabled>Select Sub Disposition2</option>";

	if($attempt_type!="" && $attempt_status!="" && $disposition!="" && $sub_disposition1!="")
	{
		if($sub_disposition1=="Medical Issue")
		{
			$sub_dispo2_value = "Medical Emergency / Covid-19 Affected";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="Financial")
		{
			$sub_dispo2_value = "Salary cut due to pandemic/ Job Loss";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="Refuse to pay")
		{
			$sub_dispo2_value1 = "Intention problem";
			$sub_dispo2_value2 = "Business Loss/Slow down";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value1\">$sub_dispo2_value1</option>";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value2\">$sub_dispo2_value2</option>";
		}
		if($sub_disposition1=="Dispute")
		{
			$sub_dispo2_value1 = "Loan Cancellation(Education loan)/Voucher related";
			$sub_dispo2_value2 = "Broken Settlement";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value1\">$sub_dispo2_value1</option>";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value2\">$sub_dispo2_value2</option>";
		}
		if($sub_disposition1=="Any other reason")
		{
			$sub_dispo2_value = "Any other reason";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="Preclosure issue")
		{
			$sub_dispo2_value = "Broken Preclosure";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="Do not call")
		{
			$sub_dispo2_value = "Date and reason";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="Call Back")
		{
			$sub_dispo2_value = "Date and time";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="Deceased")
		{
			$sub_dispo2_value = "Date of expiry";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="Left Message")
		{
			$sub_dispo2_value = "Left Message to third party(Relation Type)";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($attempt_type=="Telecalling" && $sub_disposition1=="No contact")
		{
			$sub_dispo2_value1 = "Switch off/Not reachable/DNE";
			$sub_dispo2_value2 = "Ringing no response/Busy/Disconnecting the calls";
			$sub_dispo2_value3 = "Wrong";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value1\">$sub_dispo2_value1</option>";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value2\">$sub_dispo2_value2</option>";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value3\">$sub_dispo2_value3</option>";
		}
		if($sub_disposition1=="Skip")
		{
			$sub_dispo2_value = "Switch off/Not reachable/DNE";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($sub_disposition1=="House Lock")
		{
			$sub_dispo2_value = "Ringing no response/Busy/Disconnecting the calls";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
		if($attempt_type=="Field" && $sub_disposition1=="No contact")
		{
			$sub_dispo2_value = "Wrong Number";
			$sub_dispos2 .= "<option value=\"$sub_dispo2_value\">$sub_dispo2_value</option>";
		}
	}
	echo $sub_dispos2;

}


?>


