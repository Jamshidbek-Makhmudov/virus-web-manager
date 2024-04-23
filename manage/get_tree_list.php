<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$OrgNodeId_prefix = "ORG";
$DeptNodeId_prefix = "DEPT";
$EmpNodeId_prefix = "EMP";

//**조직
$qry_params = array();
$qry_label = QRY_TREE_ORGAN_LIST;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);
$idx = 0;
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$open = ($idx==0);

		if($row['use_yn']=="Y"){
			$name = $row['org_name'];
		}else{
			$name = "<span style='text-decoration:line-through'>".$row['org_name']."</span>";
		}

		$id = $OrgNodeId_prefix.$row['org_id'];

		$data[] = array(
				"id"=>$id,
				"pId"=>"",
				"name"=>$name,
				"open"=>$open,
				"iconSkin"=>"pIcon01",
				"gubun"=>"org",
				"use"=>$row['use_yn']
			);

		$idx++;
	}

}else printJson($msg=$_LANG_TEXT['procfail'][$lang_code]."1");

//**부서
$qry_params = array();
$qry_label = QRY_TREE_DEPT_LIST;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);



if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$pId = ($row['p_dept_seq']=="0")? $OrgNodeId_prefix.$row['org_id'] : $DeptNodeId_prefix.$row['p_dept_seq'];

		if($row['use_yn']=="Y"){
			$name = $row['dept_name'];
		}else{
			$name = "<span style='text-decoration:line-through'>".$row['dept_name']."</span>";
		}

		$id = $DeptNodeId_prefix.$row['dept_seq'];

		$data[] = array(
				"id"=>$id,
				"pId"=>$pId,
				"name"=>$name,
				"open"=>false,
				"iconSkin"=>"",
				"gubun"=>"dept",
				"use"=>$row['use_yn']
			);
		
	}
}else printJson($msg=$_LANG_TEXT['procfail'][$lang_code]."2");


//**사용자
$qry_params = array();
$qry_label = QRY_TREE_EMP_LIST;
$sql = query($qry_label,$qry_params);


$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$pId = ($row['dept_seq']=="")? $OrgNodeId_prefix.$row['org_id'] : $DeptNodeId_prefix.$row['dept_seq'];

		if($row['work_yn']=="Y"){
			$name = aes_256_dec($row['emp_name']);
		}else{
			$name = "<span style='text-decoration:line-through'>".aes_256_dec($row['emp_name'])."</span>";
		}

		$id = $EmpNodeId_prefix.$row['emp_seq'];

		$data[] = array(
				"id"=>$id,
				"pId"=>$pId,
				"name"=>$name,
				"open"=>false,
				"iconSkin"=>"icon01",
				"gubun"=>"user",
				"use"=>$row['work_yn']
			);
		
	}

}else printJson($msg=$_LANG_TEXT['procfail'][$lang_code]."3");

printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);
?>