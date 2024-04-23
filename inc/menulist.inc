<?

if(!$wvcs_dbcon) return;

if(COMPANY_CODE=="600"){	//카카오뱅크
	include "menulist_600.inc";
}else{
	include "menulist_comm.inc";
}
?>