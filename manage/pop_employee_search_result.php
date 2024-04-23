<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$target = $_POST["target"];
$searchkey = $_POST["popsearchkey"];

$Model_manage = new Model_manage();

$Model_manage->SHOW_DEBUG_SQL=false;
if($target=="DEPT"){
	$args = array("searchkey"=>$searchkey);
	$result = $Model_manage->getKaBangDept($args);
}else{
	$args = array("target"=>$target,"searchkey"=>$searchkey);
	$result = $Model_manage->getKaBangEmp($args);
}



?>
<table class="list" style='margin-top:0px;'>
	<tr>
		<th style='max-width:50px;width:50px;'><? echo trsLang('번호','numtext');?></th>
		<?if($target=="EMP"){?>
		<th style='max-width:200px;width:200px;'><? echo trsLang('이름','nametext');?></th>
		<th style='max-width:200px;width:200px'><? echo trsLang('영문ID','engnameid');?></th>
		<?}?>
		<th><? echo trsLang('부서명','deptnametext');?></th>
		<th style='width:100px;'><? echo trsLang('선택','choosetexts');?></th>
	</tr>
<?
if($result){
	$total = sqlsrv_num_rows($result);
	$no = $total;
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
	
	if($target=="EMP"){
		$target_name = aes_256_dec($row['emp_name']);
		$target_value = $row['emp_id'];
	}else if($target=="DEPT"){
		$target_name = $row['dept_name'];
		$target_value = $row['dept_code'];
	}
?>
	<tr>
		<td><? echo $no;?></td>
		<?if($target=="EMP"){?>
		<td><? echo aes_256_dec($row['emp_name']);?></td>
		<td><? echo $row['emp_id'];?></td>
		<?}?>
		<td><? echo $row['dept_name'];?></td>
		<td>
			<input type='hidden' class='clsid_target_name' value="<? echo $target_name?>">
			<input type='hidden' class='clsid_target_value' value="<? echo $target_value?>">
			<a href="javascript:void(0)" onclick="selectPolicyTarget()" class='text_link'><? echo trsLang('선택','choosetexts');?></a>
		</td>
	</tr>
<?
	$no--;
	}

	if($total==0){
		echo "<tr><td colspan='5'>".trsLang('데이터가없습니다.','nodata')."</td></tr>";
	}
}
?>
</table>