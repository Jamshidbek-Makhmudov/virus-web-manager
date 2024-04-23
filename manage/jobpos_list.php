<?php
$page_name = "jobpos_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_position"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="btn_wrap">
			<div class="right">
				<a href="./jobpos_reg.php" class="btn2"><h1><?=$_LANG_TEXT['btnregist'][$lang_code]?></h1></a>
			</div>
		</div>

		<!--검색결과리스트-->
		<table class="list" style="margin-top:0">
		<caption class="tit"><?=$_LANG_TEXT['jobpostext'][$lang_code]?></caption>
<?php
	$qry_params = array();
	$qry_label = QRY_JOBPOS_LIST;
	$sql = query($qry_label,$qry_params);
?>
		<tr>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
			<th style='width:200px;min-width:230px'><?=$_LANG_TEXT['codetext'][$lang_code]?></th>
			<th style='width:200px;min-width:230px'><?=$_LANG_TEXT['codenametext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<th class="num_last"><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
		</tr>
<?php
			
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );				
	$result = @sqlsrv_query($wvcs_dbcon, $sql,$params,$options);

	$jb_gb = "P";

	if($result){

		$cnt =  sqlsrv_num_rows( $result );

		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$jpos_seq = $row['jpos_seq'];
				$jpos_code = $row['jpos_code'];
				$jpos_name = $row['jpos_name'];
				$sort = $row['sort'];
				$use_yn = $row['use_yn'];
				$create_ymd = $row['create_ymd'];
				$grade = "";

				$param_enc = ParamEnCoding("jb_gb=".$jb_gb."&jb_seq=".$jpos_seq);
				
		  ?>	
			<tr onclick="javascript:location.href='jobpos_reg.php?enc=<?=$param_enc?>'" style='cursor:pointer' >
				<td><?php echo $cnt; ?></td>
				<td><?php echo $jpos_code; ?></td>
				<td><?php echo $jpos_name; ?></td>
				<td><?php echo $sort; ?></td>
				<td><?php echo $use_yn; ?></td>
				<td><?php echo $create_ymd; ?></td>
				<td  class="num_last" onclick="event.stopPropagation();"><span onclick="JobCodeDeleteSubmit('<?=$jb_gb?>','<?=$jpos_seq?>')" style='cursor:pointer' class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
			</tr>
			<?php
			
				$cnt--;
			}

		if($result) sqlsrv_free_stmt($result);  
	}
?>	
		
		</table>


		<table class="list" style="margin-top:30px">
		<caption class="tit"><?=$_LANG_TEXT['jobdutytext'][$lang_code]?></caption>
<?php
	$qry_params = array();
	$qry_label = QRY_JOBDUTY_LIST;
	$sql = query($qry_label,$qry_params);
	
?>
		<tr>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
			<th style='width:200px;min-width:230px'><?=$_LANG_TEXT['codetext'][$lang_code]?></th>
			<th style='width:200px;min-width:230px'><?=$_LANG_TEXT['codenametext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<th class="num_last"><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
		</tr>
<?php
			
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );				
	$result = @sqlsrv_query($wvcs_dbcon, $sql,$params,$options);

	$jb_gb = "D";

	if($result){

		$cnt =  sqlsrv_num_rows( $result );
		
		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

			$jduty_seq = $row['jduty_seq'];
			$jduty_code = $row['jduty_code'];
			$jduty_name = $row['jduty_name'];
			$sort = $row['sort'];
			$use_yn = $row['use_yn'];
			$create_ymd = $row['create_ymd'];
			$grade = "";

			$param_enc = ParamEnCoding("jb_gb=".$jb_gb."&jb_seq=".$jduty_seq);
			
	  ?>	
		<tr >
			<td><?php echo $cnt; ?></td>
			<td><?php echo $jduty_code; ?></td>
			<td><span onclick="javascript:location.href='jobpos_reg.php?enc=<?=$param_enc?>'"  style='cursor:pointer'><?php echo $jduty_name; ?></span></td>
			<td><?php echo $sort; ?></td>
			<td><?php echo $use_yn; ?></td>
			<td><?php echo $create_ymd; ?></td>
			<td class='num_last'><span onclick="JobCodeDeleteSubmit('<?=$jb_gb?>','<?=$jduty_seq?>')" style='cursor:pointer'  class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
		</tr>
		<?php
		  $cnt--;
		}
		if($result) sqlsrv_free_stmt($result);  
	}
?>		
		</table>


		<table class="list" style="margin-top:30px">
		<caption class="tit"><?=$_LANG_TEXT['jobgradetext'][$lang_code]?></caption>
<?php
	$qry_params = array();
	$qry_label = QRY_JOBGRADE_LIST;
	$sql = query($qry_label,$qry_params);
?>
		<tr>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
			<th style='width:200px;min-width:230px'><?=$_LANG_TEXT['codetext'][$lang_code]?></th>
			<th style='width:200px;min-width:230px'><?=$_LANG_TEXT['codenametext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<th style='width:100px;min-width:100px'><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<th class="num_last"><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
		</tr>
<?php
					
		$params = array();
		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );				
		$result = @sqlsrv_query($wvcs_dbcon, $sql,$params,$options);

		$jb_gb = "G";

		if($result){

			$cnt =  sqlsrv_num_rows( $result );
			
			while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$jgrade_seq = $row['jgrade_seq'];
				$jgrade_code = $row['jgrade_code'];
				$jgrade_name = $row['jgrade_name'];
				$sort = $row['sort'];
				$use_yn = $row['use_yn'];
				$create_ymd = $row['create_ymd'];
				$grade = "";

				$param_enc = ParamEnCoding("jb_gb=".$jb_gb."&jb_seq=".$jgrade_seq);
				
		  ?>	
			<tr>
				<td><?php echo $cnt; ?></td>
				<td><?php echo $jgrade_code; ?></td>
				<td><span onclick="javascript:location.href='jobpos_reg.php?enc=<?=$param_enc?>'"  style='cursor:pointer'><?php echo $jgrade_name; ?></span></td>
				<td><?php echo $sort; ?></td>
				<td><?php echo $use_yn; ?></td>
				<td><?php echo $create_ymd; ?></td>
				<td class='num_last'><span onclick="JobCodeDeleteSubmit('<?=$jb_gb?>','<?=$jgrade_seq?>')" style='cursor:pointer' class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
			</tr>
			<?php
			
				$cnt--;
			}
			if($result) sqlsrv_free_stmt($result);  
		}
			
?>		
		</table>


		<div class="btn_wrap">
			<div class="right">
				<a href="./jobpos_reg.php" class="btn2"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
			</div>
		</div>
	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>