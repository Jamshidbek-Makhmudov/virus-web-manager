<?php
/*$_PAGE
//auth_check.inc 참조
*/
?>
<div id="header" >
	<div id='menu_folder' >
	<?if($_ck_user_level=="MANAGER" || $_ck_user_level=="MANAGER_S"){	//매니저 등급 접속 ?>
			<div style='background-color:#fee300;height:40px;'>
				<div class="section_area" style='border:0px'>
					<div style='text-align:center;padding-top:5px;'>
						<img src="<?php echo $_www_server; ?>/images/kakao_bi_black.png" style='height:20px;'>
					</div>
					<div class="section" style='margin-top:-20px'>
							<img src="<?php echo $_www_server; ?>/images/icon.jpg"> <b><?=$_ck_user_name?></b> <!--<?=$_CODE["admin_level"][$_ck_user_level]?>--> <?=$_LANG_TEXT['signingin'][$lang_code]?><span class="line"></span><a href="<?=$_www_server?>/login/logout.php"><?=$_LANG_TEXT['logout'][$lang_code]?></a>
					</div>
				</div>
			</div>
		<?}else{?>
			<div class="section_area">
				<div class="section">
					<img src="<?php echo $_www_server; ?>/images/icon.jpg"> <a href="<?php echo $_www_server; ?>/user/my_info.php"><b><?=$_ck_user_name?></b></a> <?=$_CODE["admin_level"][$_ck_user_level]?> <?=$_LANG_TEXT['signingin'][$lang_code]?><span class="line"></span><a href="<?=$_www_server?>/login/logout.php"><?=$_LANG_TEXT['logout'][$lang_code]?></a>
				</div>
			</div>
		<?}?>
	

	<div class="gnb_area">
		<div class="gnb" >
			<div class="logo">
				<a href="<?php echo $_www_server; ?>/main.php"><img src="<?php echo $_www_server; ?>/images/<?=$_logo_img?>" alt="logo" ></a>
			</div>
			
			<div class="gnb_dep1">
				<ul>
					<?if($_ck_user_level=="MANAGER" || $_ck_user_level=="MANAGER_S"){	//매니저 접속
						//메인 대시보드 화면 없음
					}else{?>	
						<li class="<?php if($page_name == 'main') echo "active"; ?>"><a href="<?php echo $_www_server; ?>/main.php"><?=$_LANG_TEXT['m_home'][$lang_code]?></a></li>
					<?}?>

					<?
					//**메뉴리스트
					//print_r($comm_user_page_auth);
					foreach($_PAGE as $cate=>$menu){

						if(is_array($menu['PAGE'][$page_name])){
							list($navi_step1,$navi_step2,$navi_step3) = $menu['PAGE'][$page_name];
						}
						
						if($cate=="MAIN"){
							continue;
						}
	
						$__menu_code = $menu['MENU_CODE'];
						$__menu_name = $menu['MENU_NAME'];
						$__menu_path = $menu['MENU_PATH'];
						$__menu_link = $menu['MENU_LINK'];
						$__page_list = $menu['PAGE'];

						if($_ck_user_level=="SUPER"){	//모든 메뉴권한 허용
							$__checked_user_page_auth1 = true;
						}else{
							$__checked_user_page_auth1 = is_array($comm_user_page_auth[$__menu_code]);
						}

						if($__checked_user_page_auth1==true){
							// 최고관리자가 아닌경우 해당 메뉴의 권한이 있는 첫번째 페이지를 $__menu_link 에 세팅
							if ($_ck_user_level != "SUPER") {
								$pages = $comm_user_page_auth[$__menu_code];
								list($page, $auth)  = array_first($pages);

								$__menu_link = getMenuPageLink($__menu_code, $page);
							}
					?>
						<li class="<?php if($__menu_code==$current_page_menu_code) echo "active"; ?>">
							<a href="<?php echo $__menu_link; ?>"><? echo $__menu_name;?></a>
							<div class="sub" style="left:-45px">
								<div class="line"></div>
									<ul>
										<? 
											foreach($__page_list as $__page_code => $__page_info){
												if($_ck_user_level=="SUPER"){	//모든 페이지권한 허용.
													$__checked_user_page_auth2 = true;
												}else{
													if($comm_user_page_auth[$__menu_code]["all"] ==""){
														$__checked_user_page_auth2 = ($comm_user_page_auth[$__menu_code][$__page_code] !="");
													}else{
														$__checked_user_page_auth2 = true;	//모든 페이지권한 허용
													}
												}

												if($__checked_user_page_auth2==true){
													
													$__page_name = $__page_info[2];
													$__page_url = $_www_server.$__menu_path."/".$__page_code.".php";
										?>
											<li><a href="<?php echo $__page_url;?>"><? echo $__page_name;?></a></li>
										<?
												}
											}
										?>
									</ul>
							</div>
						</li>
					<?
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	</div>
	<div class="navi_area">
		<div class="area" >
			<div id="page_navi" class="navi" >
				<img src="<?php echo $_www_server; ?>/images/home.jpg" style="margin:-2px 5px 0 0">
				<?php
				if($page_name == "main"){	
					echo $navi_step1." > ".$navi_step2." > <span class='organ'>".$navi_step3."</span>";
				}else{
					echo "<span class='organ'>".$navi_step1."</span> > ".$navi_step2." > ".$navi_step3;
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