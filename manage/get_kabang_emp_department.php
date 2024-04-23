<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$find_employee = empty($_REQUEST["find_employee"]) ? false : ($_REQUEST["find_employee"] == 'Y');
$args = array("use_yn"=>'Y');
$Model_manage = new Model_manage;
$data = $Model_manage->getKabankDepartment($args);

$department = array();

function get_children($v) { 
	global $_search_depth;
	global $_search_seq; 
	return ($v["depth"] == $_search_depth) && ($v["p_dept_seq"] == $_search_seq); 
}

function get_depth_child($department, $search_depth, $search_seq) {
	global $data;
	global $_search_depth;
	global $_search_seq; 

	$_search_depth = $search_depth;
	$_search_seq   = $search_seq;

	@extract($department);
	
	$children = array_filter($data, "get_children");
	$str_details = "";
	$str_open = ($search_depth <= 3) ? "open":"";

	if (count($children) > 0) {
		$str_details .= "\n\t\t<details class=\"tree-nav__item is-expandable\" {$str_open}>";
		$str_details .= "\n\t\t<summary class=\"tree-nav__item-title\">{$dept_name} <a href='javascript:void(0);' onclick=\"setDeptNapePath('{$dept_name_path}')\">[선택]</a></summary>";

		foreach ($children as $idx => $dept) {
			$_search_seq = $dept_seq;
			$str_details .= get_depth_child($dept, $search_depth + 1, $dept["dept_seq"]);

			$_search_seq = $search_depth;
		}

		$str_details .= "\n\t\t</details>";
	} else {
		$str_details .= "\n\t\t\t\t<div class=\"tree-nav__item\"><div class=\"tree-nav__item-title\"><i class=\"icon ion-edit\"></i> {$dept_name} <a href='javascript:void(0);' onclick=\"setDeptNapePath('{$dept_name_path}')\">[선택]</a></div></div>";
	}

	return $str_details;
}

function get_depth_employee($department, $search_depth, $search_seq) {
	global $data;
	global $_search_depth;
	global $_search_seq; 

	$_search_depth = $search_depth;
	$_search_seq   = $search_seq;

	@extract($department);
	
	$children = array_filter($data, "get_children");
	$str_details = "";
	$str_open = (($search_depth <= 3) && (count($children) > 0)) ? "open":"";

	$str_details .= "\n\t\t<details class=\"tree-nav__item is-expandable\" {$str_open}>";
	$str_details .= "\n\t\t<summary class=\"tree-nav__item-title\">{$dept_name}</summary>";

	foreach ($children as $idx => $dept) {
		$_search_seq = $dept_seq;
		$str_details .= get_depth_employee($dept, $search_depth + 1, $dept["dept_seq"]);

		$_search_seq = $search_depth;
	}

	$str_details .= "\n\t\t</details>";

	return $str_details;
}

$root = array_filter($data, function($v) { return $v["depth"] == 1; });
?>
<nav class="tree-nav">
	<?php
	foreach ($root as $idx => $dept) {
		if ($find_employee) {
			echo get_depth_employee($dept, 2, $dept["dept_seq"]);
		} else {
			echo get_depth_child($dept, 2, $dept["dept_seq"]);
		}
	}
	?>
</nav>
<script>
	function openAllDetails() {
		$('.tree-nav__item .is-expandable').attr("open", true)
	}

	function closeAllDetails() {
		$('.tree-nav__item .is-expandable').attr("open", false)
	}
</script>
<style>
	summary { display: block; cursor: pointer; outline: 0;  }
	summary::-webkit-details-marker { display: none; }
	.tree-nav__item { display: block; white-space: nowrap; color: ##737296; position: relative; }
	.tree-nav__item.is-expandable::before { border-left: 1px dotted #4f5773; content: ""; height: 100%; left: 0.8rem; position: absolute; top: 2.4rem; height: calc(100% - 2.4rem); }
	.tree-nav__item .tree-nav__item { margin-left: 2.4rem; }
	.tree-nav__item.is-expandable[open] > .tree-nav__item-title::before { content: "-"; }
	.tree-nav__item.is-expandable > .tree-nav__item-title { padding-left: 2.4rem; }
	.tree-nav__item.is-expandable > .tree-nav__item-title::before { position: absolute; color: #737296; font-size: 16px; content: "+"; left: 0; display: inline-block; text-align: center; border: 1px solid #737296; border-radius: 3px; line-height: 14px; width: 16px; height: 16px; margin: 8px; }
	.tree-nav__item-title { cursor: pointer; display: block; outline: 0; color: #737296; font-size: 16px; line-height: 2rem; }
	.tree-nav__item-title .icon { display: inline; padding-left: 1.6rem; margin-right: 0.8rem; color: #666; font-size: 16px; position: relative; }
	.tree-nav__item-title .icon::before { content: ''; top: 0; position: absolute; left: 0; display: inline-block; width: 1.6rem; text-align: center; border: 1px solid #ddd; border-radius: 3px; line-height: 14px; width: 16px; height: 16px; margin: 2px 8px 8px; font-style: normal; }
	.tree-nav__item-title::-webkit-details-marker { display: none; }
</style>