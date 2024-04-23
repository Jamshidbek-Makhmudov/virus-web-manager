<?php
$page_name = "tree_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

/*uid = zTree.node.id
  - dept_seq
  - org_id
  - 'EMP'+emp_seq
*/
$uid = $_REQUEST["uid"];

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
?>
<script type="text/javascript" src="<?php echo $_js_server ?>/zTree/jquery.ztree.core-3.5.js"></script>
<link rel="stylesheet" href="<?php echo $_css_server?>/zTree/zTreeStyle/zTreeStyle.css" type="text/css">
<SCRIPT type="text/javascript">
	<!--
	
	var _menuAuth = {
	<?php

		foreach($_CODE['admin_menu_auth'] as $key => $value){
				echo $key.":'".$value."',";
		}
	?> 
	};

	var _height = 0;
	var treeId = "treeDept";
	var OrgNodeId_prefix = "ORG", DeptNodeId_prefix = "DEPT",EmpNodeId_prefix = "EMP";
	var curMenu = null, zTree_Menu = null, nodeId = null;
	var setting = {
		view: {
			showLine: true,
			selectedMulti: false,
			dblClickExpand: false,
			fontCss :setFontCss,
			nameIsHTML: true
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		callback: {
			onNodeCreated: this.onNodeCreated,
			beforeClick: this.beforeClick,
			onclick: this.onclick,
			onExpand : this.onExpand
			
			
		}
	};

	var zNodes =[];

	$(document).ready(function(){
		var callback;
<?php
	if($uid){
?>
		nodeId = '<?=$uid?>';
		callback = function(){
			clickView(treeId,curMenu);
		}
<?	
	}
?>
		getTreeList(callback);

	});

	//-->
</SCRIPT>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>
<div id="tree_list">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_organization"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<div class="container_tree">

			<div class="treetop">
				<div class="btn_wrap">
					<button type="button" onclick="RegOrg();" class="btn  required-create-auth hide"><?=$_LANG_TEXT['btnorganregist'][$lang_code]?></button>
					<button type="button" onclick="regDept();" class="btn  required-create-auth hide"><?=$_LANG_TEXT['btndeptregist'][$lang_code]?></button>
					<button type="button" onclick="regUser();" class="btn  required-create-auth hide"><?=$_LANG_TEXT['btnempregist'][$lang_code]?></button>
				</div>
				<div class="title"> > <span id='content_title'><?=$_LANG_TEXT['viewinfotext'][$lang_code]?></span></div>
			</div>
			
			<div class='wrap'>
			<div id='treeleft' class="treeleft">
				<div id="treeDept" class="ztree"></div>
			</div>

			<div class="treecontent">
				<div id="main_contents"></div>
			</div>

			</div>

		</div>
	</div>
</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>