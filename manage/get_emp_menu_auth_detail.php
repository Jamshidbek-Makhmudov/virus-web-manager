<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$_emp_seq = $_POST['emp_seq'];
$_emp_id = $_POST['emp_id'];
$_menu_code = $_POST['menu_code'];

if($_menu_code ==""){
	$_menu_code = array("U1000","R1000","S1000","M1000");
}

echo "<table class='view'>";
	foreach($_PAGE as $key => $menu){

		$menu_code = $menu['MENU_CODE'];

		if(in_array($menu_code,$_menu_code)){
			
			$menu_name  = $_CODE['menu'][$menu_code];
			$page_count = count($menu['PAGE']);

			echo "<tr>";
			echo "<th>{$menu_name}</th>";
			echo "<td>";
			foreach($menu['PAGE'] as $page_code => $page_info){
				list($gate,$top_menu,$sub_menu) = $page_info; 
				echo "<li><input type='checkbox' name='page_code[]' value='{$menu_code},{$page_code}'>{$sub_menu}</li>";
			}
			echo "</td>";
			echo "</tr>";

		}
	}
echo "</table>";
?>