<?

if(!$wvcs_dbcon) return;

if(COMPANY_CODE=="600"){	//카카오뱅크
	include "topmenu_600.inc";
}else{
	include "topmenu_comm.inc";
}
?>