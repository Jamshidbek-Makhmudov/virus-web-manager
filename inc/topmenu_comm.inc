<?php
/*$_PAGE
//auth_check.inc 참조
*/
?>
<div id="header">
	<div id='menu_folder'>
	<div class="section_area">
		<div class="section">
			<img src="<?php echo $_www_server; ?>/images/icon.jpg"> <a href="<?php echo $_www_server; ?>/user/my_info.php"><b><?=$_ck_user_name?></b></a> <?=$_CODE["admin_level"][$_ck_user_level]?> <?=$_LANG_TEXT['signingin'][$lang_code]?><span class="line"></span><a href="<?=$_www_server?>/login/logout.php"><?=$_LANG_TEXT['logout'][$lang_code]?></a>
		</div>
	</div>

	<div class="gnb_area">
		<div class="gnb">
			<div class="logo">
				<a href="<?php echo $_www_server; ?>/main.php"><img src="<?php echo $_www_server; ?>/images/<?=$_logo_img?>" alt="logo" ></a>
			</div>
			
			
			<div class="gnb_dep1">
				<ul>
				
					<li class="<?php if($page_name == 'main') echo "active"; ?>"><a href="<?php echo $_www_server; ?>/main.php"><?=$_LANG_TEXT['m_home'][$lang_code]?></a></li>
					
				<?if(in_array($_PAGE["USER"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["USER"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["USER"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_visitor'][$lang_code]?></a>
						<div class="sub" style="left:-45px">
							<div class="line"></div>
							<ul>
								<li><a href="<?php echo $_www_server; ?>/user/user_list.php"><?=$_LANG_TEXT['m_visitor_info'][$lang_code]?></a></li>
							</ul>
						</div>
					</li>
				<?}?>
				
				<?if(in_array($_PAGE["RESULT"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["RESULT"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["RESULT"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_result'][$lang_code]?></a>
						<div class="sub" style="left:-33px">
							<div class="line"></div>
							<ul>
								<li><a href="<?php echo $_www_server; ?>/result/result_list.php"><?=$_LANG_TEXT['m_result'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/result/checkin_scan_log.php"><?=$_LANG_TEXT['m_manage_checkinscanlog'][$lang_code]?></a></li>
							</ul>
						</div>
					</li>
				<?}?>
				
				<?if(in_array($_PAGE["STATISTICS"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["STATISTICS"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["STATISTICS"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_statistics'][$lang_code]?></a>
						<div class="sub" style="left:-33px">
							<div class="line"></div>
							<ul>
								<li><a href="<?php echo $_www_server; ?>/stat/vcs_stat_period.php"><?=$_LANG_TEXT['m_statistics_result'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/vcs_stat_company.php"><?=$_LANG_TEXT['m_statistics_result_company'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/user_pc_stat.php"><?=$_LANG_TEXT['m_statistics_pc'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/file_import_history.php"><?=$_LANG_TEXT['file_inspection_details'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/login_log.php"><?=$_LANG_TEXT['m_manage_adminloginlog'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/work_log.php"><?=$_LANG_TEXT['work_log'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/report.php"><?=$_LANG_TEXT['m_report'][$lang_code]?></a></li>
							</ul>
						</div>
					</li>
				<?}?>


				<?if(in_array($_PAGE["MANAGE"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["MANAGE"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["MANAGE"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_manage'][$lang_code]?></a>
						<div class="sub" style="left:-33px">
							<div class="line"></div>
							<ul>
								<li><a href="<?php echo $_www_server; ?>/manage/tree_list.php"><?=$_LANG_TEXT['m_manage_organization'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/m_user_list.php"><?=$_LANG_TEXT['m_manage_employee'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/dept_list.php"><?=$_LANG_TEXT['m_manage_department'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/scan_center_list.php"><?=$_LANG_TEXT['m_manage_scan_center'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/group_list.php"><?=$_LANG_TEXT['m_manage_group'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/jobpos_list.php"><?=$_LANG_TEXT['m_manage_position'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/policy.php"><?=$_LANG_TEXT['m_manage_policy'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/vaccine_list.php"><?=$_LANG_TEXT['m_manage_vaccine'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/app_update.php"><?=$_LANG_TEXT['m_manage_appupdate'][$lang_code]?></a></li>
								<!--<li><a href="<?php echo $_www_server; ?>/manage/admin_attempt_log_process.php"><?=$_LANG_TEXT['attemptlogin'][$lang_code]?></a></li>-->
								<li><a href="<?php echo $_www_server; ?>/manage/access_ip_config.php"><?=$_LANG_TEXT['m_manage_accessip'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/code_list.php"><?=$_LANG_TEXT['m_manage_code'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/admin_list.php"><?=$_LANG_TEXT['m_manage_admin'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/admin_conf/file_signature.php"><?=$_LANG_TEXT['configtext'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/notice_list.php"><?=$_LANG_TEXT['m_manage_notice'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/manage/faq_list.php"><?=$_LANG_TEXT['m_manage_faq'][$lang_code]?></a></li>
						
							</ul>
						</div>
					</li>
				<?}?>

				</ul>
			</div>
		</div>
	</div>
	</div>
	<div class="navi_area">
		<div class="area">
			<div id="page_navi" class="navi">
				<img src="<?php echo $_www_server; ?>/images/home.jpg" style="margin:-2px 5px 0 0">
				<?php
				foreach($_PAGE as $cate => $arr){

					$page_arr = $arr["PAGE"];

					if(is_array($page_arr[$page_name])){
						list($step1,$step2,$step3,$step4) = $page_arr[$page_name];
						break;
					}
				}
				if($page_name == "main"){	
					echo $step1." > ".$step2." > <span class='organ'>".$step3."</span>";
				}else{
					echo "<span class='organ'>".$step1."</span> > ".$step2." > ".$step3;
				}
				?>
			</div>
<?php
	
	$visible_gate_pop = false;

	if($_ck_user_level=="SUPER"){

		$qry_params = array();
		$qry_label = QRY_COMMON_ORG_USE_ALL;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		$visible_gate_pop = $result;
		
	}else{
		
		if($_ck_user_mng_org_auth){

			$qry_params = array("emp_seq"=> $_ck_user_seq);
			$qry_label = QRY_ADMIN_MNG_ORG;
			$sql = query($qry_label,$qry_params);

			//echo $sql;

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			$visible_gate_pop = $result;
		}
	}

	if($visible_gate_pop){
?>
		<div class="gate">
			<?=$_LANG_TEXT['organselecttext'][$lang_code]?> <span class="gate_pop_btn"><img src="<?php echo $_www_server; ?>/images/bar.jpg"></span>
				<img name='arrow_menu_folder' id='arrow_menu_folder_show' src="<?php echo $_www_server; ?>/images/arrow_down.png" onclick="SetMenuFolder('menu_folder','show');"  style='display:none;' >
				<img name='arrow_menu_folder' id='arrow_menu_folder_hide' src="<?php echo $_www_server; ?>/images/arrow_up.png" onclick="SetMenuFolder('menu_folder','hide');">
				<span style="cursor: pointer;margin-left:30px;" onclick="window.open('<?=$_www_server?>/help/kr/index.html', 'help', 'height=800px, width=1000px, scrollbars=yes, resizable=yes');"><img src="<?=$_www_server?>/images/help.png"></span>
		</div>
		<div class="gate_pop" >
			<ul>
				<li><a href="<?=$_SERVER['PHP_SELF']?>?gate=all"><?=$_LANG_TEXT['alltext'][$lang_code]?></a></li>
			<?
				while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

					//$gate_param -- function.inc 참조
					
					$param_enc = ParamEnCoding("gate=".$row['org_id']."&".$gate_param);
			?>
				<li><a href="<?=$_SERVER['PHP_SELF']?>?enc=<?=$param_enc?>">#<?=$row['org_name']?></a></li>
			<?
				}	
			?>
			</ul>
		</div>
<?
	}//if($visible_gate_pop){
?>
		</div>
	</div>

</div>
<script type='text/javascript'>
	LoadMenuFolder('menu_folder');
</script>