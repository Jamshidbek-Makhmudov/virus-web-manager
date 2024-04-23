if(SITE_NAME=="") SITE_NAME = '/wvcs';
let _excel_download_max_size = 5000;		//한번에 엑셀다운로드 허용 건수

if (lang_code == 'CN') {
	var pickerOpts = {
		defaultDate: '+1',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: false,
		showOn: 'both',
		buttonImage: '/wvcs/images/icon_datepicker.png',
		buttonImageOnly: true,
		closeText: 'Close',
		prevText: '上月',
		nextText: '下个月',
		currentText: '今天',
		monthNames: [
			'1月',
			'2月',
			'3月',
			'4月',
			'5月',
			'6月',
			'7月',
			'8月',
			'9月',
			'10月',
			'11月',
			'12月',
		],
		monthNamesShort: [
			'1月',
			'2月',
			'3月',
			'4月',
			'5月',
			'6月',
			'7月',
			'8月',
			'9月',
			'10月',
			'11月',
			'12月',
		],
		dayNames: ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
		dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
		dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '',
		monthSuffix: '月',
		showAnim: '',
	};
} else if (lang_code == 'EN') {
	var pickerOpts = {
		defaultDate: '+1',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: false,
		showOn: 'both',
		buttonImage: '/wvcs/images/icon_datepicker.png',
		buttonImageOnly: true,
		closeText: 'Close',
		prevText: 'Previous Month',
		nextText: 'Next month',
		currentText: 'Today',
		monthNames: [
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December',
		],
		monthNamesShort: [
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December',
		],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '',
		monthSuffix: 'Mon',
		showAnim: '',
	};
} else {
	var pickerOpts = {
		defaultDate: '+1',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: false,
		showOn: 'both',
		buttonImage: '/wvcs/images/icon_datepicker.png',
		buttonImageOnly: true,
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		monthNames: [
			'1월',
			'2월',
			'3월',
			'4월',
			'5월',
			'6월',
			'7월',
			'8월',
			'9월',
			'10월',
			'11월',
			'12월',
		],
		monthNamesShort: [
			'1월',
			'2월',
			'3월',
			'4월',
			'5월',
			'6월',
			'7월',
			'8월',
			'9월',
			'10월',
			'11월',
			'12월',
		],
		dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
		dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
		dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '',
		monthSuffix: '월',
		showAnim: '',
	};
}


var timepickerOpts = {
	twentyFour: true, //Display 24 hour format, defaults to false 
	upArrow: 'wickedpicker__controls__control-up', //The up arrow class selector to use, for custom CSS 
	downArrow: 'wickedpicker__controls__control-down', //The down arrow class selector to use, for custom CSS 
	close: 'wickedpicker__close', //The close class selector to use, for custom CSS 
	hoverState: 'hover-state', //The hover state class to use, for custom CSS 
	title: '', //The Wickedpicker's title, 
	showSeconds: false, //Whether or not to show seconds, 
	secondsInterval: 1, //Change interval for seconds, defaults to 1 , 
	minutesInterval: 1, //Change interval for minutes, defaults to 1 
	beforeShow: null, //A function to be called before the Wickedpicker is shown 
	show: null, //A function to be called when the Wickedpicker is shown 
	clearable: false, //Make the picker's input clearable (has clickable "x")
}

/**
 * Date를 문자열로 변환.
 * @param format 형식 ex) yyyy-mm-dd hh:mm:ss
 * new Date().dateformat('yyyy-mm-dd hh:mm:ss')
 */
Date.prototype.dateformat = function (format) {
	var year = this.getFullYear();
	var month = this.getMonth() < 9 ? '0' + (this.getMonth() + 1) : this.getMonth() + 1; // getMonth() is zero-based
	var day = this.getDate() < 10 ? '0' + this.getDate() : this.getDate();
	var hour = this.getHours() < 10 ? '0' + this.getHours() : this.getHours();
	var min = this.getMinutes() < 10 ? '0' + this.getMinutes() : this.getMinutes();
	var second = this.getSeconds() < 10 ? '0' + this.getSeconds() : this.getSeconds();

	var strDate = format
		.replace('yyyy', year)
		.replace('mm', month)
		.replace('dd', day)
		.replace('hh', hour)
		.replace('mm', min)
		.replace('mi', min)
		.replace('ss', second);

	return strDate;
};

/**
 * 문자열을 Date로 변환.
 * @param valueString 문자열
 * @param inputFormat 형식 ex) YYYY-MM-DD hh:mm:ss
 */
var stringToDate = function (valueString, inputFormat) {
	if (!valueString) {
		return valueString;
	}
	var mask;
	var temp;
	var dateString = '';
	var monthString = '';
	var yearString = '';
	var hourString = '';
	var miniteString = '';
	var secondString = '';
	var j = 0;

	var n = inputFormat.length;
	for (var i = 0; i < n; i++, j++) {
		temp = '' + valueString.charAt(j);
		mask = '' + inputFormat.charAt(i);

		if (mask == 'M') {
			if (isNaN(Number(temp)) || temp == ' ') j--;
			else monthString += temp;
		} else if (mask == 'D') {
			if (isNaN(Number(temp)) || temp == ' ') j--;
			else dateString += temp;
		} else if (mask == 'Y') {
			yearString += temp;
		} else if (mask == 'h') {
			hourString += temp;
		} else if (mask == 'm') {
			miniteString += temp;
		} else if (mask == 's') {
			secondString += temp;
		} else if (!isNaN(Number(temp)) && temp != ' ') {
			return null;
		}
	}

	temp = '' + valueString.charAt(inputFormat.length - i + j);
	if (!(temp == '') && temp != ' ') return null;

	var monthNum = Number(monthString);
	var dayNum = Number(dateString);
	var yearNum = Number(yearString);
	var hourNum = Number(hourString);
	var miniteNum = Number(miniteString);
	var secondNum = Number(secondString);

	if (isNaN(yearNum) || isNaN(monthNum) || isNaN(dayNum)) return null;

	if (yearString.length == 2 && yearNum < 70) yearNum += 2000;

	var newDate = new Date(yearNum, monthNum - 1, dayNum);
	newDate.setHours(hourNum, miniteNum, secondNum);

	if (dayNum != newDate.getDate() || monthNum - 1 != newDate.getMonth()) return null;

	return newDate;
};

function ParamEnCoding(str) {

	var _str = btoa(encodeURIComponent(str));
	//alert(decodeURIComponent(atob (param)));
	return _str;
}

function ParamDeCoding(str){

	var _str = decodeURIComponent (atob(str).replace(/\+/g, ' '));
	//console.log(_str);
	return _str;

}

function changeSourceView(obj) {
	var encodeStr = Base64.encode(obj.value);
	var decodeStr = Base64.decode(encodeStr);
	document.mainForm.resultEncode.value = encodeStr;
	document.mainForm.resultDecode.value = decodeStr;
}

function daysInMonth(year, month) {
	return new Date(year, month, 0).getDate();
}

function addCommas(nStr) {

	if($.isNumeric(nStr)==false) return nStr;

	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function CheckBlankData(obj, msg) {
	if ($(obj).val() == '') {
		$(obj).focus();
		if (msg != '') alert(msg);
		return false;
	} else {
		return true;
	}
}
function onlyNumber(obj) {
	$(obj).keyup(function () {
		$(this).val(
			$(this)
				.val()
				.replace(/[^0-9-]/g, '')
		);
	});
}
function passwordCheck(pw) {
	var num = /[0-9]/;
	var eng_s = /[a-z]/;
	var eng_l = /[A-Z]/;

	var spe = /[~!@\#$%<>^&*]/; // 원하는 특수문자 추가 제거

	var pwd_cnt;
	pwd_cnt = 0;

	if (num.test(pw)) {
		pwd_cnt++;
	}

	if (eng_s.test(pw)) {
		pwd_cnt++;
	}

	if (eng_l.test(pw)) {
		pwd_cnt++;
	}

	if (spe.test(pw)) {
		pwd_cnt++;
	}

	return pwd_cnt;
}
function CheckValidPw(str) {
	var pwd_cnt = passwordCheck(str);

	var check = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,16}$/;

	if (!check.test(str) || pwd_cnt < 3) {
		return false;
	}

	return true;
}
function CheckValidId(str) {
	/*
	var check1 = /^(?=.*[a-zA-Z])(?=.*[0-9]).{6,16}$/;

	if (!check1.test(str)) {
		return false;
	}

	var check2 = /[0-9a-zA-Z_]/; 

	for( var i=0; i<str.length; i++){
		if(str.charAt(i) != " " && check2.test(str.charAt(i)) == false ){
			//alert(str.charAt(i) + "는 입력불가능한 문자입니다");
			return false;
		}
	}  
	
	*/

	if (str.length < 5) {
		return false;
	}

	return true;
}

function CheckValidCode(obj, msg) {
	var str = $(obj).val();

	var check = /[0-9A-Z_]/;

	for (var i = 0; i < str.length; i++) {
		if (str.charAt(i) != ' ' && check.test(str.charAt(i)) == false) {
			alert(msg);
			//alert(str.charAt(i) + "는 입력불가능한 문자입니다");
			return false;
		}
	}

	return true;
}
function CheckStrLen(obj, maxlen) {
	var temp;
	var msglen;
	var tmpstr = '';
	var value = $(obj).val();
	var len = value.length;

	msglen = maxlen * 2;

	if (len == 0) {
		value = maxlen * 2;
	} else {
		for (var k = 0; k < len; k++) {
			temp = value.charAt(k);

			if (escape(temp).length > 4) msglen -= 2;
			else msglen--;

			if (msglen < 0) {
				alert(maxlen + qlimittextinput[lang_code]);
				$(obj).val(tmpstr);
				break;
			} else {
				tmpstr += temp;
			}
		}
	} //if(len == 0)
}
/*
 중복서브밋 방지
*/
var doubleSubmitFlag = false;
function doubleSubmitCheck() {
	if (doubleSubmitFlag) {
		return doubleSubmitFlag;
	} else {
		doubleSubmitFlag = true;
		return false;
	}
}
function UserSubOrgSet() {
	var org = $('#org_id').val();

	$('#dept_seq option').toggleOption('show');

	if (org != '') {
		$("#dept_seq option:gt(0)[org!='" + org + "']").toggleOption('hide');
	}
}

/*
function SetAdminMenuAuth() {
	SetAdminMenuAuthPreset(); return ;

	var admin_level = $('#admin_level').val();
	var emp_seq = $("#emp_seq").val();

	if(admin_level=="SUPER"){
		$("input[name='menu[]']").prop('checked', true);
		$("#admin_mng_menu .radio").hide();
		$("#btn_page_auth").hide();
		$("#btn_kabang_page_auth").hide();
		$('#admin_mng_menu').append("<span name='all'>" + alltext[lang_code] + '</span>');
	}else{

		$("#admin_mng_menu .radio").show();
		if(emp_seq > 0){
			$("#btn_page_auth").show();
		}
		$("#btn_kabang_page_auth").show();
		$("#admin_mng_menu span[name='all']").remove();

		if (_menuAuth[admin_level]) {
			var menus = _menuAuth[admin_level].split(',');

			$("input[name='menu[]']").removeAttr('disabled');
			$("input[name='menu[]']").prop('checked', false);

			$("input[name='menu[]']").each(function (idx) {
				if ($.inArray(this.value, menus) >= 0) {
					this.checked = true;
				} else {
					this.disabled = true;
				}
			});
		}
	}
}
*/

function SetAdminMenuAuthPreset() {
	var admin_level = $('#admin_level').val();

	$("#admin_auth_type").val('');
	$("#admin_auth_type option").hide();
	
	$("#admin_auth_type option[data-target-level='NONE']").show();
	$("#admin_auth_type option[data-target-level='"+admin_level+"']").show();

	if (admin_level != "SUPER") {
		$("#admin_auth_type option[data-target-level='']").show();
	}

	if ($("#emp_seq").val() != "") {
		$("#admin_auth_type option[value=CUSTOMIZE]").show();
	} else {
		$("#admin_auth_type option[value=CUSTOMIZE]").hide();
	}

	let auth_type = $("#admin_auth_type").data("selected-auth-type");
	let preset_seq = $("#admin_auth_type").data("selected-preset-seq");

	if (preset_seq) {
		let original = $(`#admin_auth_type option[data-preset-seq=${preset_seq}]`);

		if (original.css('display') == 'block') {
			$(`#admin_auth_type option[data-preset-seq=${preset_seq}]`).attr("selected", "selected");
		}
	} else {
		if (auth_type == "CUSTOMIZE") {
			$(`#admin_auth_type option[value=CUSTOMIZE]`).attr("selected", "selected");
		}
	}

	changeAdminAuthPresetType()
}

function changeAdminAuthType() {
	let auth_type = $('#admin_auth_type_order').val();
	let preset_seq = $('#admin_auth_type_order option:selected').data('preset-seq');

	$('#admin_auth_type').val(auth_type);
	$("#admin_auth_preset").val('')
	$('#admin_auth_preset_seq').val('');
	$("#admin_auth_preset_apply").html('');
	$("#admin_auth_preset_apply").hide();
	$("#btn_page_auth").hide();

	if (auth_type == "PRESET") {
		let preview = "<a href='javascript:void(0)' class='text_link' style='margin-left: 10px;' onclick='popAdminPageAuthDetail();' data-preset-seq='"+preset_seq+"'>["+pageauthpreview[lang_code]+"]</a>";
	
		$('#admin_auth_preset_seq').val(preset_seq);
		$("#admin_auth_preset_apply").html(preview).show();
	} else if (auth_type == "CUSTOMIZE") {
		$("#btn_page_auth").show();
	}
}

//세부권한보기 팝업
function popAdminPageAuthDetail(){
	let preset_seq = $("#admin_auth_type option:selected").data("preset-seq");

	$("#modal_admin_auth_detail .btn_wrap").hide();
	$("#modal_admin_auth_detail").show();

	$.post(
		SITE_NAME + '/manage/admin_reg_auth_detail.php',
		{ "preset_seq" : preset_seq },
		function (data) {
			$("#menu_auth_detail").html(data);
		},
		'text'
	);

	return false;
}

// 권한그룹 변경
function changeAdminAuthPresetType() {
	try {
		let admin_level = $("#admin_level").val();
		let admin_auth_type = $("#admin_auth_type").val();
		let mng_scan_center_disabled = (admin_auth_type != "CUSTOMIZE");
		
		$("input[name='mng_scan_center[]'").prop('checked', false);
		$("input[name='mng_scan_center[]'").prop('disabled', mng_scan_center_disabled);

		$("input[name='menu[]'").prop('checked', false);
		$("input[name='menu[]'").prop('indeterminate', false);

		$("#page_auth_preview").hide();
		$("#page_auth_detail").hide();

		if (admin_auth_type == "PRESET") {
			$("#page_auth_preview").show();

			let preset_seq = $("#admin_auth_type option:selected").data("preset-seq");

			$("#admin_auth_preset_seq").val(preset_seq);

			$.post(
				SITE_NAME + '/manage/get_admin_auth_preset_scan_center.php',
				{ "preset_seq": preset_seq },
				(data) => {
					if (data.status) {
						if (data.result != null) {
							let preset = data.result;

							for(let code in preset.menu_auth) {
								if (preset.menu_auth[code].all != undefined) {
									let exec = preset.menu_auth[code].all.split(",");

									if (exec.length == 5) {
										$(`#menu_${code}`).prop('checked', true);
									} else {
										$(`#menu_${code}`).prop('indeterminate', true);
									}
								} else {
									$(`#menu_${code}`).prop('indeterminate', true);
								}
							}

							if (preset.admin_level == "SUPER") {
								$("input[name='mng_scan_center[]'").prop('checked', true);
							} else {
								preset.scan_center.map((code) => {
									$(`#mng_scan_center_${code}`).prop('checked', true);
								})
							}
						}
					}
				},
				'json'
			);
		} else if (admin_auth_type == "CUSTOMIZE") {
			if (admin_level == "SUPER") {
				$("#page_auth_detail").hide();

				$("input[name='mng_scan_center[]'").prop('disabled', true);
				$("input[name='mng_scan_center[]'").prop('checked', true);
				$("input[name='menu[]'").prop('checked', true);
			} else {
				$("#page_auth_detail").show();

				$("input[name='mng_scan_center[]'").prop('checked', false);
				$("input[name='menu[]'").prop('checked', false);

				$("input.checked").prop('checked', true);
				$("input.indeterminate").prop('indeterminate', true);
			}

			$("#admin_auth_preset_seq").val('');

			if (admin_level == "MANAGER") {
				$(`#menu_M1000`).prop('checked', false);
			}
		} else {
			$("#admin_auth_preset_seq").val('');
		}
	} catch (e) {
		console.log(e)
	}
}

function SetAdminMngOrg() {
	var admin_level = $('#admin_level').val();

	if (admin_level == 'SUPER') {
		$('#admin_mng_org').append("<span name='all'>" + alltext[lang_code] + '</span>');
		$('#admin_mng_org .radio').hide();
	} else {
		$("#admin_mng_org span[name='all']").remove();
		$('#admin_mng_org .radio').show();
	}
}

function SetAdminMngScanCenter() {
	var admin_level = $('#admin_level').val();

	if (admin_level == 'SUPER') {
		$('#admin_mng_scan_center').append("<span name='all'>" + alltext[lang_code] + '</span>');
		$('#admin_mng_scan_center .radio').hide();
	} else {
		$("#admin_mng_scan_center span[name='all']").remove();
		$('#admin_mng_scan_center .radio').show();
	}
}

function SetAdminAuth() {
	var visible = $('#admin_level').val() != '';

	$('#admin_auth_tab').toggle(visible);
	SetAdminMenuAuthPreset();
	SetAdminMngOrg();
	// SetAdminMngScanCenter();
}



function CheckEmpinfoSubmit(proc) {
	var emp_seq = document.getElementById('emp_seq').value;
	var emp_name = document.getElementById('emp_name').value;
	var emp_no = document.getElementById('emp_no').value;
	var emp_pwd = document.getElementById('emp_pwd').value;
	var emp_pwd_confirm = document.getElementById('emp_pwd_confirm').value;

	var org_id = document.getElementById('org_id').value;
	var dept_seq = document.getElementById('dept_seq').value;
	var jgrade_code = document.getElementById('jgrade_code').value;
	var jduty_code = document.getElementById('jduty_code').value;
	var jpos_code = document.getElementById('jpos_code').value;

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#emp_name'), qnameinput[lang_code])) return false;
		if (!CheckBlankData($('#emp_no'), qemployeenumberinput[lang_code])) return false;

		if (proc == 'CREATE') {
			if (!CheckValidId($('#emp_no').val())) {
				alert(qadminidvalid[lang_code]);
				$('#emp_no').focus();
				return false;
			}

			if (!CheckBlankData($('#emp_pwd'), qpasswdinput[lang_code])) return false;
		}

		if (emp_pwd != '') {
			if (emp_pwd != emp_pwd_confirm) {
				alert(qpwdnotsamepwdconfirm[lang_code]);
				return false;
			}

			if (!CheckValidPw($('#emp_pwd').val())) {
				alert(qpasswdvalid2[lang_code]);
				$('#emp_pwd').focus();
				return false;
			}
		}

		/*if(org_id==""){
			if(!CheckBlankData($("#org_id"),qorganchoose[lang_code])) return false;
		}*/

		var admin_level = $('#admin_level').val();

		//관리기관 체크
		if (admin_level != '') {
			if (org_id != '') {
				if (admin_level != 'SUPER') {
					var user_org_check = $("input[name='mng_org[]'][value='" + org_id + "']:checked").length;

					if (user_org_check == 0) {
						alert(quserorgancheck[lang_code]);
						$("input[name='mng_org[]']").focus();
						return false;
					}
				}
			}
		} //if(admin_level != ""){
	}

	return true;
}
function CheckPasswordSubmit() {
	var emp_pwd = $('#new_pw').val();
	var emp_pwd_confirm = $('#new_pw_confirm').val();

	if (!CheckBlankData($('#new_pw'), qpasswdinput[lang_code])) return false;

	if (emp_pwd != '') {
		if (emp_pwd != emp_pwd_confirm) {
			alert(qpwdnotsamepwdconfirm[lang_code]);
			$('#new_pw_confirm').focus();
			return false;
		}

		if (!CheckValidPw($('#new_pw').val())) {
			alert(qpasswdvalid2[lang_code]);
			$('#new_pw').focus();
			return false;
		}
	}

	return true;
}

function PasswordSubmit() {
	if (!CheckPasswordSubmit()) return false;

	$.post(
		SITE_NAME + '/login/password_change_process.php',
		$('#frmPassword').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				if (data.result != null) {
					location.href = data.result;
				}
			}
		},
		'json'
	);

	return false;
}

function CheckLoginOtpSubmit() {
	var otp_code = $('#otp_code').val();

	if (!CheckBlankData($('#otp_code'), qotpcodeinput[lang_code])) return false;

	return true;
}
function LoginOtpSubmit() {
	if (!CheckLoginOtpSubmit()) return false;

	$.post(
		SITE_NAME + '/login/login_otp_process.php',
		$('#frmLoginOtp').serialize(),
		function (data) {
			if (data.status) {
				if (data.result != null) {
					SetMenuFolder('menu_folder', 'show');
					location.href = data.result;
				}
			} else {
				alert(data.msg);
			}
		},
		'json'
	);

	return false;
}

function Timer(fn, t) {
	var timerObj = setInterval(fn, t);

	this.stop = function () {
		if (timerObj) {
			clearInterval(timerObj);
			timerObj = null;
		}
		return this;
	};

	// start timer using current settings (if it's not already running)
	this.start = function () {
		if (!timerObj) {
			this.stop();
			timerObj = setInterval(fn, t);
		}
		return this;
	};

	// start with new interval, stop current interval
	this.reset = function (newT) {
		t = newT;
		return this.stop().start();
	};
}

function OtpTimeOutCounter() {
	var SetTime = $('#clock').val();

	if (typeof timer == 'object') {
		timer.stop();
	}

	timer = new Timer(function () {
		if (SetTime == 0) {
			timer.stop();
			alert(inputotptimeovertext[lang_code]);
			top.location.href = SITE_NAME;
		} else {
			SetTime--;
		}

		var m = Math.floor((SetTime % 3600) / 60);
		var s = Math.floor((SetTime % 3600) % 60);

		var mDisplay = m > 0 ? m + minutetext[lang_code] : '';
		var sDisplay = s + secondtext[lang_code];
		var strTimeDisply = mDisplay + ' ' + sDisplay;

		$('#time').text(strTimeDisply);
	}, 1000);
}

function EmpinfoSubmit(proc) {

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$('#proc').val(proc);

	if (!CheckEmpinfoSubmit(proc)) return;
	if (doubleSubmitCheck()) return;

	$.post(
		SITE_NAME + '/manage/m_user_reg_process.php',
		$('#UserFrm').serialize(),
		function (data) {
			alert(data.msg);

			var rs = data.result;

			if (rs.src == 'user_list') {
				if (data.status) {
					location.href = 'm_user_list.php';
				}
			} else if (rs.src == 'tree_list') {
				var emp_seq = rs.emp_seq;
				var callback;

				if (proc == 'DELETE') {
					ResetUser();
					callback = null;
				} else {
					document.UserFrm.emp_seq.value = emp_seq;
					nodeId = EmpNodeId_prefix + emp_seq;

					callback = function () {
						clickView(treeId, curMenu);
					};
				}

				getTreeList(callback);
			}

			doubleSubmitFlag = false;
		},
		'json'
	);
}

function CheckMyinfoSubmit() {
	var emp_seq = document.getElementById('emp_seq').value;
	var emp_name = document.getElementById('emp_name').value;
	var emp_no = document.getElementById('emp_no').value;
	var emp_pwd = document.getElementById('emp_pwd').value;
	var emp_pwd_confirm = document.getElementById('emp_pwd_confirm').value;

	if (!CheckBlankData($('#emp_name'), qnameinput[lang_code])) return false;
	if (!CheckBlankData($('#emp_no'), qemployeenumberinput[lang_code])) return false;

	if (emp_pwd != '') {
		if (emp_pwd != emp_pwd_confirm) {
			alert(qpwdnotsamepwdconfirm[lang_code]);
			return false;
		}

		if (!CheckValidPw($('#emp_pwd').val())) {
			alert(qpasswdvalid2[lang_code]);
			$('#emp_pwd').focus();
			return false;
		}
	}

	return true;
}

function MyinfoSubmit() {
	if (!CheckMyinfoSubmit()) return;

	$.post(
		SITE_NAME + '/user/my_info_process.php',
		$('#UserFrm').serialize(),
		function (data) {
			alert(data.msg);
		},
		'json'
	);
}

function SearchSubmit(frm) {

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);


	if (frm.searchkey.value != '' && frm.searchopt.value == '') {
		frm.searchopt.focus();
		alert(qsearchoptionchoose[lang_code]);
		return false;
	} else if (frm.searchopt.value != '' && frm.searchkey.value == '') {
		frm.searchkey.focus();
		alert(qsearchkeywordinput[lang_code]);
		//return false;
	} else {
		frm.submit();
	}
}
function SearchSubmitDash(frm) {

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);


	// if (frm.searchkey.value != '' && frm.searchopt.value == '') {
	// 	frm.searchopt.focus();
	// 	alert(qsearchoptionchoose[lang_code]);
	// 	return false;
	// } else if (frm.searchopt.value != '' && frm.searchkey.value == '') {
	// 	frm.searchkey.focus();
	// 	alert(qsearchkeywordinput[lang_code]);
	// 	//return false;
	// } else {
	// 	frm.submit();
	// }

	frm.submit();
}
function WorkLogSearchSubmit(frm) {

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);


	if (frm.searchkey.value != '' && frm.searchopt.value == '') {
		frm.searchopt.focus();
		alert(qsearchoptionchoose[lang_code]);
		return false;
	} else if (frm.searchopt.value != '' && frm.searchkey.value == '') {
		frm.searchkey.focus();
		alert(qsearchkeywordinput[lang_code]);
		//return false;
	} else {
		frm.submit();
	}
}

function LoginSubmit() {
	var login_id = document.getElementById('login_id').value;
	var login_pw = document.getElementById('login_pw').value;

	var url = $('#frmLogin').attr("action");

	if (login_id == '' || login_pw == '') {
		alert('Check the input value');
		return false;
	} else {
		$.post(
			url,
			$('#frmLogin').serialize(),
			function (data) {
				if (data.status) {
					if (data.result != null) {
						if (data.result == 'LOGIN_OTP') {
							document.all.frmLogin.action = 'login_otp.php';
							document.all.frmLogin.submit();
						} else {
							SetMenuFolder('menu_folder', 'show');
							location.href = data.result;
						}
					}
				} else {
					alert(data.msg);
				}
			},
			'json'
		);
	}

	return false;
}

//새로고침 차단
function doNotReload() {
	if (event.keyCode == 116) {
		event.keyCode = 2;
		return false;
	} else if (event.ctrlKey && (event.keyCode == 78 || event.keyCode == 82)) {
		return false;
	}
}

function DepartmentSubmit(proc) {
	
	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$('#proc').val(proc);

	if (!CheckDepartmentSubmit(proc)) return false;

	$.post(
		'dept_process.php',
		$('#formDept').serialize(),
		function (data) {
			alert(data.msg);

			var rs = data.result;

			if (rs.src == 'dept_list') {
				location.href = './dept_list.php';
			} else if (rs.src == 'tree_list') {
				var dept_seq = rs.dept_seq;
				var callback;

				if (proc == 'DELETE') {
					ResetDept();
					callback = null;
				} else {
					document.formDept.dept_seq.value = dept_seq;
					nodeId = DeptNodeId_prefix + dept_seq;

					callback = function () {
						clickView(treeId, curMenu);
					};
				}

				getTreeList(callback);
			}
		},
		'json'
	);
}

function ScanCenterSubmit(proc) {

	$('#proc').val(proc);
	var proc_name = getProcName();
	$("#frmCenter input[name='proc_name']").val(proc_name);

	if (!CheckScanCenterSubmit(proc)) return false;

	$.post(
		'./scan_center_process.php',
		$('#frmCenter').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './scan_center_list.php';
			}
		},
		'json'
	);
}

function CheckScanCenterSubmit(proc) {
	var scan_center_seq = $('#scan_center_seq').val();

	if (proc == 'CREATE' || proc == 'UPDATE') {
		if (!CheckBlankData($('#org_id'), qorganchoose[lang_code])) return false;
		if (!CheckCenterCode($('#scan_center_code'), centercodeinputguidetext[lang_code])) return false;
		if (!CheckBlankData($('#scan_center_name'), qcenternameinput[lang_code])) return false;
	} else if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	}

	return true;
}

function ScanCenterDeleteSubmit(scan_center_seq) {
	var proc = 'DELETE';
	var proc_name = getProcName();

	if (!CheckScanCenterSubmit(proc)) return false;

	$.post(
		'scan_center_process.php',
		{
			proc: proc,
			proc_name : proc_name,
			scan_center_seq: scan_center_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}

function DepartmentDeleteSubmit(dept_seq) {
	var proc = 'DELETE';

	if (!CheckDepartmentSubmit(proc)) return false;

	$.post(
		'dept_process.php',
		{
			proc: proc,
			dept_seq: dept_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}

function DeptSearchSubmit(frm) {
	frm.submit();
}

function CheckDepartmentSubmit(proc) {
	var dept_seq = $('#dept_seq').val();
	var p_dept_seq = $('#p_dept_seq').val();
	var dept_hierarchy = $("#p_dept_seq option[value='" + dept_seq + "']").attr('hierarchy');
	var p_dept_hierarchy = $('#p_dept_seq option:selected').attr('hierarchy');

	if (proc == 'CREATE' || proc == 'UPDATE') {
		if (!CheckBlankData($('#sel_org'), qorganchoose[lang_code])) return false;
		if (!CheckBlankData($('#dept_name'), qdepartmentnameinput[lang_code])) return false;

		if (proc == 'UPDATE') {
			if (dept_seq == p_dept_seq) {
				alert(departmentchooseguidetext[lang_code]);
				return false;
			}

			if (p_dept_hierarchy.indexOf(dept_hierarchy) >= 0) {
				alert(departmentchooseguidetext[lang_code]);
				return false;
			}
		}
	} else if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	}

	return true;
}

function MngDeptSubOrgSet() {
	var org = $('#sel_org').val();

	$('#p_dept_seq option').toggleOption('show');
	$('#dept_chief option').toggleOption('show');

	if (org != '') {
		$("#p_dept_seq option:gt(0)[org!='" + org + "']").toggleOption('hide');
		$("#dept_chief option:gt(0)[org!='" + org + "']").toggleOption('hide');
	}
}

function MngDeptListSubOrgSet() {
	var org = $('#sel_org2').val();

	$('#p_dept_seq2 option').toggleOption('show');

	if (org != '') {
		$("#p_dept_seq2 option:gt(0)[org!='" + org + "']").toggleOption('hide');
	}
}

function DepartmentClear(frm) {
	document.getElementById('sel_org').value = '';
	document.getElementById('p_dept_seq').value = '0';
	document.getElementById('dept_seq').value = '';
	document.getElementById('dept_name').value = '';
	document.getElementById('dept_chief').value = '';
	document.getElementById('use_yn').value = 'Y';
	document.getElementById('sort').value = '';
	document.getElementById('dept_auth1').value = '';
	document.getElementById('dept_auth2').value = '';
	document.getElementById('dept_auth3').value = '';

	document.getElementById('btnDeptReg').style.display = '';
	document.getElementById('btnPrev').style.display = 'none';
	document.getElementById('btnNext').style.display = 'none';
	document.getElementById('btnDeptEdit').style.display = 'none';
	document.getElementById('btnDeptDel').style.display = 'none';
}

function IPSubmit(proc) {
	$('#proc').val(proc);

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) return false;
	} else {
		if (!CheckBlankData($('#ip_addr_start'), qq129[lang_code])) return false;
		if (!CheckBlankData($('#ip_addr_end'), qq130[lang_code])) return false;
	}

	$.post(
		'ip_process.php',
		$('#formIP').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './ip_list.php';
			}
		},
		'json'
	);
}

function IPSet(obj) {
	var data = $(obj).attr('dataset').split('|');

	dataset = '<?=$ip_seq?>|<?=$org_id?>|<?=$use_yn?>|<?=$start_ip?>|<?=$end_ip?>|<?=$memo?>';

	document.getElementById('ip_seq').value = data[0];
	document.getElementById('org_id').value = data[1];
	document.getElementById('use_yn').value = data[2];
	document.getElementById('ip_addr_start').value = data[3];
	document.getElementById('ip_addr_end').value = data[4];
	document.getElementById('memo').value = data[5];
	document.getElementById('btnEditIP').style.display = '';
	document.getElementById('btnDelIP').style.display = '';
	document.getElementById('btnRegIP').style.display = 'none';
}

function IPClear() {
	document.getElementById('ip_seq').value = '';
	document.getElementById('org_id').value = '';
	document.getElementById('use_yn').value = 'Y';
	document.getElementById('ip_addr_start').value = '';
	document.getElementById('ip_addr_end').value = '';
	document.getElementById('memo').value = '';
	document.getElementById('btnEditIP').style.display = 'none';
	document.getElementById('btnDelIP').style.display = 'none';
	document.getElementById('btnRegIP').style.display = '';
}

function OrgSearch() {
	var search_key = $('#searchkey').val();
	var result = false;

	$('#sel_org option').each(function () {
		if (search_key.trim() == $(this).text()) {
			result = true;
			$('#sel_org').val($(this).val());
			return false;
		}
	});

	if (!result) {
		alert(notfounddatatext[lang_code]);
		$('#sel_org').val('');
	}

	$('#sel_org').trigger('change');

	return false;
}

function EmpSearch() {
	var search_key = $('#searchkey').val().trim();
	var result = false;

	$('#sel_org').val('');
	$('#sel_org').trigger('change');
	$('#sel_dept').val('');
	$('#sel_dept').trigger('change');
	$('#sel_emp').val('');

	$('#sel_emp option').each(function () {
		var emp_seq = $(this).val();
		var org_id = $(this).attr('org');
		var dept_seq = $(this).attr('dept');
		var emp_no = $(this).attr('no');
		var emp_name = $(this).attr('emp_name');

		if (search_key == emp_name || search_key == emp_no) {
			result = true;

			$('#sel_org').val(org_id);
			$('#sel_dept').val(dept_seq);
			$('#sel_emp').val(emp_seq);

			return false;
		}
	});

	if (!result) {
		alert(notfounddatatext[lang_code]);
		$('#sel_emp').val('');
	}

	$('#sel_emp').trigger('change');

	return false;
}

function SubOrgSet(gubun) {
	var org = $('#sel_org').val();
	var dept = $('#sel_dept').val();

	if (gubun == 'ORG') {
		$('#sel_dept option').toggleOption('show');
		$('#sel_emp option').toggleOption('show');

		if (org != '') {
			$("#sel_dept option:gt(0)[org!='" + org + "']").toggleOption('hide');
			$("#sel_emp option:gt(0)[org!='" + org + "']").toggleOption('hide');
		} else {
			$('#sel_dept').val('');
			$('#sel_emp').val('');
		}
	} else if (gubun == 'DEPT') {
		$('#sel_emp option').toggleOption('show');

		if (dept != '') {
			$("#sel_emp option:gt(0)[dept!='" + dept + "']").toggleOption('hide');
		} else {
			$('#sel_emp').val('');
		}
	}
}

function NoticeSubmit(proc) {
	if (proc == 'CREATE') {
		if (!CheckNoticeData()) return false;
	} else {
		var notice_seq = $('#n_seq').val();
		if (notice_seq == '') return false;

		if (proc == 'UPDATE') {
			if (!CheckNoticeData()) return false;
		} else if (proc == 'DELETE') {
			if (!confirm(qdeleteconfirm[lang_code])) return false;
		}
	}

	$('#proc').val(proc);

	var form = $('#frmNotice')[0];
	var data = new FormData(form);

	$.ajax({
		type: 'POST',
		enctype: 'multipart/form-data',
		url: 'notice_process.php',
		data: data,
		processData: false,
		contentType: false,
		cache: false,
		timeout: 600000,
		success: function (data) {
			alert(data);
			location.href = 'notice_list.php';
		},
		error: function (e) {
			alert(e);
		},
	});

	return false;
}

function CheckNoticeData() {
	var title = $('#n_title').val();
	var content = $('#n_contents').val();

	if (title == '') {
		alert(qtitleinput[lang_code]);
		return false;
	} else if (content == '') {
		alert(qcontentinput[lang_code]);
		return false;
	}

	return true;
}

function FaqSubmit(proc) {
	if (proc == 'CREATE') {
		if (!CheckFaqData()) return false;
	} else {
		var faq_seq = $('#f_seq').val();
		if (faq_seq == '') return false;

		if (proc == 'UPDATE') {
			if (!CheckFaqData()) return false;
		} else if (proc == 'DELETE') {
			if (!confirm(qdeleteconfirm[lang_code])) return false;
		}
	}

	$('#proc').val(proc);

	$.post(
		'faq_process.php',
		$('#frmFaq').serialize(),
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.href = './faq_list.php';
			}
		},
		'json'
	);

	return false;
}

function CheckFaqData() {
	var title = $('#f_title').val();
	var content = $('#f_contents').val();

	if (title == '') {
		alert(qtitleinput[lang_code]);
		return false;
	} else if (content == '') {
		alert(qcontentinput[lang_code]);
		return false;
	}

	return true;
}

function CheckGroupSubmit(proc) {
	var group_name = $('#group_name').val();
	var memo = $('#memo').val();

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) return false;
	} else {
		if (!CheckBlankData($('#group_name'), qgroupnameinput[lang_code])) return false;
		if (!CheckBlankData($('#memo'), qgroupdescriptioninput[lang_code])) return false;

		var chk_cnt = $("input[type='checkbox'][name='org_id[]']:checked").length;

		if (chk_cnt == 0) {
			alert(qorganchoose[lang_code]);
			return false;
		}
	}

	return true;
}
function GroupAddSubmit(proc) {
	$('#proc').val(proc);

	if (!CheckGroupSubmit(proc)) return false;

	$.post(
		'group_reg_process.php',
		$('#frmGroup').serialize(),
		function (data) {
			alert(data);
			location.href = './group_list.php';
		},
		'text'
	);

	return false;
}
function GroupDeleteSubmit(group_seq) {
	var proc = 'DELETE';

	if (!CheckGroupSubmit(proc)) return false;

	$.post(
		'group_reg_process.php',
		{
			proc: proc,
			group_seq: group_seq,
		},
		function (data) {
			alert(data);
			location.reload(true);
		},
		'text'
	);
}
function CheckJobCodeSubmit(proc) {
	var job_gb = $('#job_gubun').val();

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) return false;
	} else {
		if (!CheckBlankData($('#job_gubun'), qgubuninput[lang_code])) return false;
		if (!CheckBlankData($('#code_name'), qnameinput[lang_code])) return false;
		if (!CheckBlankData($('#sort'), qsortinput[lang_code])) return false;

		if (job_gb == 'S') {
			if (!CheckBlankData($('#grade'), qgradeinput[lang_code])) return false;
		}
	}

	return true;
}
function JobCodeSubmit(proc) {
	$('#proc').val(proc);

	if (!CheckJobCodeSubmit(proc)) return false;

	$.post(
		'jobpos_process.php',
		$('#frmCode').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './jobpos_list.php';
			}
		},
		'json'
	);

	return false;
}
function JobCodeDeleteSubmit(jb_gb, jb_seq) {
	var proc = 'DELETE';

	if (!CheckJobCodeSubmit(proc)) return false;

	$.post(
		'jobpos_process.php',
		{
			proc: proc,
			job_gubun: jb_gb,
			val_seq: jb_seq,
		},
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.reload();
			}
		},
		'json'
	);

	return false;
}
function MngCodeDeleteSubmit(code_seq) {
	var proc = 'DELETE';

	var proc_name = getProcName();

	if (!CheckMngCodeSubmit(proc)) return false;

	$.post(
		'code_process.php',
		{
			code_seq: code_seq,
			proc: proc,
			proc_name : proc_name
		},
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './code_list.php';
			}
		},
		'json'
	);

	return false;
}

function MngCodeSubmit(proc) {

	var proc_name = getProcName();
	$('#proc').val(proc);

	if (!CheckMngCodeSubmit(proc)) return false;

	var sel_group_key = '';
	var code_gubun = $('#code_gubun').val();
	var p_code_seq = '';
	var code_seq = '';
	var code_name = '';
	var code_key = '';
	var sort = '';
	var useyn = '';
	var scan_center_code = '';


	if (code_gubun == 'cate') {
		code_seq = $('#cate_code_seq').val();
		p_code_seq = $('#cate_p_code_seq').val();
		code_name = $('#cate_code_name').val();
		code_key = $('#cate_code_key').val();
		sort = $('#cate_sort').val();
		useyn = $('#cate_useyn').val();
	} else {
		code_seq = $('#code_seq').val();
		p_code_seq = $('#code_key option:selected').attr('p_code_seq');
		code_name = $('#code_name').val();
		code_key = $('#code_key').val();
		sort = $('#sort').val();
		useyn = $('#useyn').val();
		scan_center_code = $('#scan_center_code').val();
	}

	if (code_gubun == 'cate' && proc == 'CREATE') {
		sel_group_key = code_key;
	} else {
		sel_group_key = $('#sel_group_key').val();
	}

	$.post(
		'code_process.php',
		{
			p_code_seq: p_code_seq,
			code_seq: code_seq,
			code_name: code_name,
			code_key: code_key,
			sort: sort,
			useyn: useyn,
			proc: proc,
			proc_name : proc_name,
			scan_center_code : scan_center_code
		},
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './code_list.php';
			}
		},
		'json'
	);

	return false;
}

function CheckMngCodeSubmit(proc) {
	var code_gubun = $('#code_gubun').val();

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) return false;
	} else {
		if (code_gubun == 'cate') {
			if (!CheckBlankData($('#cate_code_name'), qcodecategorynameinput[lang_code])) return false;
			if (!CheckValidCode($('#cate_code_key'), qcodecategoryguidetext[lang_code])) return false;
		} else {
			if (!CheckBlankData($('#code_key'), qcodecategorychoose[lang_code])) return false;
			if (!CheckBlankData($('#code_name'), qcodevalueinput[lang_code])) return false;
		}
	}

	return true;
}

function AppUpdateSubmit(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) return false;
	} else {
		if (!CheckBlankData($('#app_name'), qappfilenameinput[lang_code])) return false;

		if (proc == 'CREATE') {
			if (!CheckBlankData($('#app_file'), qattachfile[lang_code])) return false;
		} else if (proc == 'UPDATE') {
			if ($('#app_file').val() == '' && $('#sp_old_file') == '') {
				alert(qattachfile[lang_code]);
				$('#app_file').focus();
				return false;
			}
		}
		
		if($("#patch_dt_div").val()=="fix"){
			if (!CheckBlankData($('#patch_dt'), qupdatetimeinput[lang_code])) return false;
		}
		//if (!CheckBlankData($('#install_path'), qinstallpathinput[lang_code])) return false;
	}

	$('#proc').val(proc);
	
	var proc_name = getProcName();
	$("#proc_name").val(proc_name);


	var form = $('#frmApp')[0];
	var data = new FormData(form);

	$.ajax({
		type: 'POST',
		enctype: 'multipart/form-data',
		url: 'app_update_process.php',
		data: data,
		processData: false,
		contentType: false,
		cache: false,
		timeout: 600000,
		success: function (data) {
			var rs = JSON.parse(data);

			alert(rs.msg);

			if (rs.status) {
				location.href = 'app_update.php';
			}
		},
		error: function (e) {
			alert(e);
		},
	});

	return false;
}

function CheckAdminInfoSubmit(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#emp_name'), qnameinput[lang_code])) return false;
		if (!CheckBlankData($('#emp_no'), qemployeenumberinput[lang_code])) return false;

		if (proc == 'CREATE') {
			if (!CheckValidId($('#emp_no').val())) {
				alert(qadminidvalid[lang_code]);
				$('#emp_no').focus();
				return false;
			}

			if (!CheckBlankData($('#emp_pwd'), qpasswdinput[lang_code])) return false;
		}

		var emp_pwd = document.getElementById('emp_pwd').value;
		var emp_pwd_confirm = document.getElementById('emp_pwd_confirm').value;

		if (emp_pwd != '') {
			if (emp_pwd != emp_pwd_confirm) {
				alert(qpwdnotsamepwdconfirm[lang_code]);
				return false;
			}

			if (!CheckValidPw($('#emp_pwd').val())) {
				alert(qpasswdvalid2[lang_code]);
				$('#emp_pwd').focus();
				return false;
			}
		}

		//if(!CheckBlankData($("#org_id"),qorganchoose[lang_code])) return false;
		if (!CheckBlankData($('#admin_level'), qadminlevelchoose[lang_code])) return false;

		var admin_level = $('#admin_level').val();
		var admin_auth_type = $("#admin_auth_type").val();

		if (!admin_auth_type) {
			alert(chooseaccessauthcheck[lang_code]);
			$("#admin_auth_type").focus();
			return false;
		}

		if ((admin_level != '') && (admin_level != 'SUPER')) {
			//관리기관 체크
			//if (org_id != '') {
				var user_org_check = $("input[name='mng_org[]']:checked").length;

				if (user_org_check == 0) {
					alert(chooseorgancheck[lang_code]);
					$("input[name='mng_org[]']").focus();
					return false;
				}
			//}
			
			//관리검사장 체크
			var user_center_check = $("input[name='mng_scan_center[]']:checked").length;
	
			if(user_center_check==0){
				alert(choosecentercheck[lang_code]);
				$("input[name='mng_scan_center[]']").focus();
				return false;
			}
		}
	}

	return true;
}

function AdminInfoSubmit(proc) {
	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$('#proc').val(proc);

	if (!CheckAdminInfoSubmit(proc)) return false;
	if (doubleSubmitCheck()) return;

	$.post(
		SITE_NAME + '/manage/admin_reg_process.php',
		$('#frmAdmin').serialize(),
		function (data) {
			alert(data.msg);
			if (data.status) {
				if(proc=='CREATE'){
					var emp_seq = data.result;
					location.href = "admin_reg.php?enc=" + ParamEnCoding('emp_seq=' + emp_seq);
				}else{
					location.reload();
				}
			}

			doubleSubmitFlag = false;
		},
		'json'
	);
}

function GetEmpList() {
	var org_id = $('#sel_org').val();
	var dept_seq = $('#sel_dept').val();
	var searchkey = $('#searchkey').val();

	var apprv_emp = $('#apprv_emplist option')
		.map(function () {
			return $(this).val();
		})
		.get()
		.join(',');

	var refer_emp = $('#refer_emplist option')
		.map(function () {
			return $(this).val();
		})
		.get()
		.join(',');

	var except_emp = apprv_emp + (apprv_emp != '' && refer_emp != '' ? ',' : '') + refer_emp;

	if (document.getElementById('trip_emp_seq') != null) {
		var trip_emp_seq = $('#trip_emp_seq').val();

		except_emp += (except_emp != '' && trip_emp_seq != '' ? ',' : '') + trip_emp_seq;
	}

	$('#emplist option').remove();

	$.post(
		'get_emp_list.php',
		{
			org_id: org_id,
			dept_seq: dept_seq,
			searchkey: searchkey,
			except_emp: except_emp,
		},
		function (data) {
			if (data.status) {
				var items = data.result;

				for (var i = 0; i < items.length; i++) {
					$('#emplist').append(
						$('<option>', {
							value: items[i].seq,
							text: items[i].name + items[i].jpos,
							emp_name: items[i].name + items[i].jpos,
						})
					);
				}
			}
		},
		'json'
	);

	return false;
}

function CheckVaccSubmit(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#v_name'), qvaccinenameinput[lang_code])) return false;
		if (!CheckBlankData($('#v_ver'), qvaccineversioninput[lang_code])) return false;
		if (!CheckBlankData($('#v_desc'), qvaccinedescriptioninput[lang_code])) return false;
		if (!CheckBlankData($('#p_name'), qfilenameinput[lang_code])) return false;
	}

	return true;
}
function VaccSubmit(proc) {
	$('#proc').val(proc);

	if (!CheckVaccSubmit(proc)) return false;

	$.post(
		'./vaccine_process.php',
		$('#FrmVacc').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './vaccine_list.php';
			}
		},
		'json'
	);

	return false;
}

function setFontCss(treeId, treeNode) {
	/*if(treeNode.use=="N"){
		return {color:"#ccc"};
	}*/
}

function SetDatePicker() {
	$('#begin_date').datepicker();
	$('#end_date').datepicker();
	$('#in_begin_date').datepicker();
	$('#in_end_date').datepicker();
	$('#out_begin_date').datepicker();
	$('#out_end_date').datepicker();
	$('#esc_begin_date').datepicker();
	$('#esc_end_date').datepicker();
}

function CheckOrgCode(obj, msg) {
	var str = $(obj).val();

	var check = /[0-9a-zA-Z_]/;

	for (var i = 0; i < str.length; i++) {
		if (str.charAt(i) != ' ' && check.test(str.charAt(i)) == false) {
			alert(msg);
			$(obj).focus();
			//alert(str.charAt(i) + "는 입력불가능한 문자입니다");
			return false;
		}
	}

	var strlen = str.length;

	if (strlen < 1 || strlen > 20) {
		alert(msg);
		$(obj).focus();
		return false;
	}

	return true;
}

function CheckOrgSubmit(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckOrgCode($('#org_id'), organcodeinputguidetext[lang_code])) return false;
		if (!CheckBlankData($('#org_name'), qorgannametext[lang_code])) return false;
	}

	return true;
}

function OrgSubmit(proc) {

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$('#proc').val(proc);

	if (!CheckOrgSubmit(proc)) return false;

	$.post(
		'./reg_org_process.php',
		$('#frmOrg').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				var org_id = $('#org_id').val();
				var callback;

				if (proc == 'DELETE') {
					ResetOrg();

					callback = null;
				} else {
					nodeId = OrgNodeId_prefix + org_id;

					callback = function () {
						clickView(treeId, curMenu);
					};
				}

				getTreeList(callback);
			}
		},
		'json'
	);
}

function CheckCenterCode(obj, msg) {
	var str = $(obj).val();

	var check = /[0-9a-zA-Z_]/;

	for (var i = 0; i < str.length; i++) {
		if (str.charAt(i) != ' ' && check.test(str.charAt(i)) == false) {
			alert(msg);
			$(obj).focus();
			//alert(str.charAt(i) + "는 입력불가능한 문자입니다");
			return false;
		}
	}

	var strlen = str.length;

	if (strlen < 1 || strlen > 20) {
		alert(msg);
		$(obj).focus();
		return false;
	}

	return true;
}

function getTreeList(callback) {
	$.post(
		'./get_tree_list.php',
		function (data) {
			if (data.status) {
				zNodes = data.result;

				$.fn.zTree.init($('#' + treeId), setting, zNodes);
				zTree_Menu = $.fn.zTree.getZTreeObj(treeId);

				if (nodeId == null) {
					var nodes = zTree_Menu.getNodes();

					if (nodes.length > 0) {
						curMenu = nodes[0];
					}
				} else {
					curMenu = zTree_Menu.getNodeByParam('id', nodeId, null);
				}

				zTree_Menu.selectNode(curMenu);

				if (typeof callback == 'function') callback();

				//alert("treeId = " + treeId + ", node = " + curMenu.gubun + ", "+ curMenu.id + ", " + curMenu.pId + ", " + curMenu.name);

				ResizeContainer();
			} else {
				alert(data.msg);
			}
		},
		'json'
	);
}

function ResizeContainer() {
	var h1 = $('#treeleft').height();
	var h2 = $('#main_contents').height();
	var h = h1 > h2 ? h1 : h2;

	if (h > _height) {
		$("#tree_list div[class='container']").height(h + 120);
	}
}

function onExpand() {
	ResizeContainer();
}
function beforeClick(treeId, node) {
	clickView(treeId, node);
}

function GetTreeContent(id, gubun, callback) {
	$.post(
		'chk_update_status.php',
		{
			id: id,
			gubun: gubun,
		},
		function (data) {
			document.getElementById('main_contents').innerHTML = data;
			controllPageExecAuth();

			if (gubun == 'org') {
				SetDatePicker();
			} else if (gubun == 'dept') {
				MngDeptSubOrgSet();
			} else if (gubun == 'user') {
				UserSubOrgSet();
				SetDatePicker();

				$('#admin_level').change(function () {
					ResizeContainer();
				});
			}

			if (typeof callback == 'function') callback();

			ResizeContainer();
		},
		'text'
	);
}

function clickView(treeId, node) {
	if (treeId == null || node == null) return;

	//alert("treeId = " + treeId + ", node = " + node.gubun + ", "+ node.id + ", " + node.pId + ", " + node.name);

	var id = node.id;
	var gubun = node.gubun;
	var title = node.name;
	var callback;

	if (gubun == 'org') {
		title += ' ' + organinfotext[lang_code];
	} else if (gubun == 'dept') {
		title += ' ' + deptinfotext[lang_code];
	} else if (gubun == 'user') {
		title += ' ' + empinfotext[lang_code];
	}

	$('#content_title').html(title);

	curMenu = zTree_Menu.getNodeByParam('id', id, null);

	GetTreeContent(id, gubun, callback);
}

function ResetOrg() {
	nodeId = null;

	RegOrg();
}

function RegOrg() {
	var id = '';
	var gubun = 'org';
	var title = organregisttext[lang_code];

	$('#content_title').html(title);

	GetTreeContent(id, gubun);
}

function ResetDept() {
	nodeId = curMenu.pId;
	curMenu = zTree_Menu.getNodeByParam('id', curMenu.pId, null);

	clickView(treeId, curMenu);
}

function regDept() {
	var id = '';
	var gubun = 'dept';
	var title = deptregisttext[lang_code];

	if (curMenu == null) {
		alert(qorganordepartmentchoose[lang_code]);
		return false;
	}

	var node = curMenu;
	var p_name = node.name;
	var p_gubun = node.gubun;
	var p_org_id = '',
		p_dept_id = '';

	if (p_gubun == 'user') {
		alert(qorganordepartmentchoose2[lang_code]);
		return;
	} else if (p_gubun == 'dept') {
		p_dept_id = node.id.replace(DeptNodeId_prefix, '');
	} else if (p_gubun == 'org') {
		p_org_id = node.id.substring(OrgNodeId_prefix.length, node.id.length);
	}

	$('#content_title').html(p_name + ' ' + title);

	var callback = function () {
		if (p_dept_id != '') {
			$('#p_dept_seq').val(p_dept_id);
			p_org_id = $('#p_dept_seq option:selected').attr('org');
		}

		$('#sel_org').val(p_org_id);
		$('#sel_org option').not(':selected').attr('disabled', 'disabled');
		$('#sel_org').addClass('readonly');
		$('#sel_org').trigger('onchange');

		$('#p_dept_seq option').not(':selected').attr('disabled', 'disabled');
		$('#p_dept_seq').addClass('readonly');
	};

	GetTreeContent(id, gubun, callback);
}

function ResetUser() {
	nodeId = curMenu.pId;
	curMenu = zTree_Menu.getNodeByParam('id', curMenu.pId, null);

	clickView(treeId, curMenu);
}

function regUser() {
	var id = '';
	var gubun = 'user';
	var title = empregisttext[lang_code];

	if (curMenu == null) {
		alert(qorganordepartmentchoose[lang_code]);
		return false;
	}

	var node = curMenu;
	var p_name = node.name;
	var p_gubun = node.gubun;
	var p_org_id = '',
		p_dept_id = '';

	if (p_gubun == 'user') {
		alert(qorganordepartmentchoose2[lang_code]);
		return;
	} else if (p_gubun == 'dept') {
		p_dept_id = node.id.replace(DeptNodeId_prefix, '');
	} else if (p_gubun == 'org') {
		p_org_id = node.id.substring(OrgNodeId_prefix.length, node.id.length);
	}

	$('#content_title').html(p_name + ' ' + title);

	var callback = function () {
		if (p_dept_id != '') {
			$('#dept_seq').val(p_dept_id);
			p_org_id = $('#dept_seq option:selected').attr('org');
		}

		$('#org_id').val(p_org_id);
		$('#org_id option').not(':selected').attr('disabled', 'disabled');
		$('#org_id').addClass('readonly');
		$('#org_id').trigger('onchange');

		$('#dept_seq option').not(':selected').attr('disabled', 'disabled');
		$('#dept_seq').addClass('readonly');
	};

	GetTreeContent(id, gubun, callback);
}

function CheckPolicySubmit() {
	if ($('#vacc_chk_yn').val() == 'Y') {
		if (!CheckBlankData($('#vacc_scan_type'), qvaccinechecktypechoose[lang_code])) return;
		if (!CheckBlankData($('#vacc_patch_term'), qvaccinepatchterminput[lang_code])) return;

		if($("#v3_use_yn").is(":checked")==false && $("#eset_use_yn").is(":checked")==false){
			alert(choosevaccine[lang_code]);
			return false;
		}
	}

	if (!CheckBlankData($('#chkin_availabled_date'), qchkinavailableddateinput[lang_code])) return;
	if (!CheckBlankData($('#admin_pwd_change_term'), qadminpwdchangeterminput[lang_code])) return;

	return true;
}

function PolicySubmit() {
	if (!CheckPolicySubmit()) return;

	if (doubleSubmitCheck()) return;

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$("#proc").val("UPDATE");

	$.post(
		'policy_process.php',
		$('#frmPolicy').serialize(),
		function (data) {
			alert(data.msg);

			doubleSubmitFlag = false;
		},
		'json'
	);

	return;
}

function PolicyWeaknessSubmit() {
	$.post(
		'policy_weakness_process.php',
		$('#frmWeakness').serialize(),
		function (data) {
			alert(data.msg);
		},
		'json'
	);

	return;
}
function UseCheck() {
	$("table.list input[type='checkbox']").each(function () {
		this.checked = !this.checked;
	});
}

function CheckUserInfoSubmit(proc) {
	if (!CheckBlankData($('#user_name'), qnameinput[lang_code])) return false;

	return true;
}

function UserInfoSubmit(proc) {
	$('#proc').val(proc);

	if (!CheckUserInfoSubmit(proc)) return;
	if (doubleSubmitCheck()) return;

	var v_user_seq = $('#v_user_seq').val();

	$.post(
		'user_info_process.php',
		$('#frmUser').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './user_info_view.php?enc=' + ParamEnCoding('v_user_seq=' + v_user_seq);
			}

			doubleSubmitFlag = false;
		},
		'json'
	);

	return;
}

function CheckComInfoSubmit(proc) {
	if (!CheckBlankData($('#com_name'), qcompanynameinput[lang_code])) return false;

	return true;
}

function ComInfoSubmit(proc) {
	$('#proc').val(proc);

	if (!CheckComInfoSubmit(proc)) return;

	var v_com_seq = $('#com_seq').val();

	$.post(
		'com_info_process.php',
		$('#frmCom').serialize(),
		function (data) {
			alert(data.msg);

			if (data.status) {
				location.href = './com_info_view.php?enc=' + ParamEnCoding('v_com_seq=' + v_com_seq);
			}
		},
		'json'
	);

	return;
}

function changeUserCompany() {
	var sel_com_seq = $('#sel_user_com').val();

	if (sel_com_seq == '') {
		ResetUserCompany();
		return;
	}

	$.post(
		'get_user_com_info.php',
		{ com_seq: sel_com_seq },
		function (data) {
			if (data.status) {
				var dt = data.result;

				$('#com_seq').val(sel_com_seq);
				$('#com_name').val(dt['com_name']);
				$('#com_use_yn').val(dt['com_use_yn']);
				$('#ceo_name').val(dt['ceo_name']);
				$('#com_code1').val(dt['com_code1']);
				$('#com_code2').val(dt['com_code2']);
				$('#com_code3').val(dt['com_code3']);
				$('#com_gubun1').val(dt['com_gubun1']);
				$('#com_gubun2').val(dt['com_gubun2']);
			} else {
				alert(data.msg);
			}
		},
		'json'
	);
}

function ResetUserCompany() {
	$('#sel_user_com').val('');

	$('#com_seq').val('');
	$('#com_name').val('');
	$('#com_use_yn').val('Y');
	$('#ceo_name').val('');
	$('#com_code1').val('');
	$('#com_code2').val('');
	$('#com_code3').val('');
	$('#com_gubun1').val('');
	$('#com_gubun2').val('');
}

function CheckResultSubmit(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	}

	return true;
}

function ResultSubmit(proc) {

	var proc_name = getProcName();
	$("#proc_name").val(proc_name);
	$('#proc').val(proc);

	if (!CheckResultSubmit(proc)) return false;

	$.post(
		SITE_NAME + '/result/result_process.php',
		$('#frmVCS').serialize(),
		function (data) {
			alert(data.msg);

			if (proc == 'DELETE') {
				location.href = SITE_NAME + '/result/result_list.php';
			}
		},
		'json'
	);

	return;
}
function ResultSubmit2(proc) {
	$('#proc').val(proc);

	var proc_name = getProcName();
	$("#frmVCS2 input[name='proc_name']").val(proc_name);
	$("#frmVCS2 input[name='proc']").val(proc);

	if (!CheckResultSubmit(proc)) return false;

	$.post(
		SITE_NAME + '/result/result_process.php',
		$('#frmVCS2').serialize(),
		function (data) {
			alert(data.msg);
		},
		'json'
	);

	return;
}
function ResultCheckInSubmit() {
	var v_wvcs_seq = $('#v_wvcs_seq').val();
	var in_available_date = $('#in_available_date').text();
	var in_date = $('#in_date').text();
	var new_vcs_status = in_date == '' ? 'IN' : 'CHECK';

	var in_available_dt = stringToDate(in_available_date, 'YYYY-MM-DD hh:mm');
	var today = new Date();

	if (new_vcs_status == 'IN') {
		if (in_available_dt.getTime() < today.getTime()) {
			alert(checkindateover[lang_code]); //반입가능기간이 경과하였습니다.
			return false;
		}
	}

	var command = new_vcs_status == 'IN' ? checkintext[lang_code] : checkincanceltext[lang_code];
	var msg = qaccess[lang_code].replace('##', command);

	if (!confirm(msg)) return false;

	$.post(
		'result_checkin_process.php',
		{
			v_wvcs_seq: v_wvcs_seq,
			vcs_status: new_vcs_status,
		},
		function (data) {
			if (data.status) {
				var content =
					new_vcs_status == 'IN' ? data.result.apprv_name + '(' + data.result.apprv_dt + ')' : '';
				var in_date = new_vcs_status == 'IN' ? data.result.apprv_dt.substring(0, 16) : '';
				var str_vcs_status =
					new_vcs_status == 'IN' ? checkinoktext[lang_code] : checkoktext[lang_code];

				$('#apprv_info').text(content);
				$('#vcs_status').text(str_vcs_status);
				$('#in_date').text(in_date);

				$('#out_date').text('');
				$('#btnApprvOut').text(checkouttext[lang_code]);

				if (new_vcs_status == 'IN') {
					$('#btnApprvIn').text(checkincanceltext[lang_code]);
				} else if (new_vcs_status == 'CHECK') {
					$('#btnApprvIn').text(checkintext[lang_code]);
				}
			}

			alert(data.msg);
		},
		'json'
	);

	return;
}

function ResultCheckInSubmit2(obj) {
	var $obj = $(obj).parent().parent().parent();

	var v_wvcs_seq = $obj.find("span[name='seq']").text();
	var in_available_date = $obj.find("span[name='in_available_date']").text();
	var in_date = $obj.find("span[name='in_date']").text();

	var in_available_dt = stringToDate(in_available_date, 'YYYY-MM-DD hh:mm');
	var today = new Date();

	var command = '',
		new_vcs_status = '';

	if (in_date == '') {
		command = checkintext[lang_code];
		new_vcs_status = 'IN';
	} else {
		command = checkincanceltext[lang_code];
		new_vcs_status = 'CHECK';
	}

	if (new_vcs_status == 'IN') {
		if (in_available_dt.getTime() < today.getTime()) {
			alert(checkindateover[lang_code]); //반입가능기간이 경과하였습니다.
			return false;
		}
	}

	var msg = qaccess[lang_code].replace('##', command);

	if (!confirm(msg)) return false;

	$.post(
		'./result_checkin_process.php',
		{
			v_wvcs_seq: v_wvcs_seq,
			vcs_status: new_vcs_status,
		},
		function (data) {
			if (data.status) {
				var in_date = '',
					str_vcs_status = '';

				if (new_vcs_status == 'IN') {
					in_date = data.result.apprv_dt.substring(0, 16);
					str_vcs_status = checkinoktext[lang_code];
				} else {
					in_date = '';
					str_vcs_status = checkoktext[lang_code];
				}

				$obj.find("span[name='in_date']").text(in_date);
				$obj.find("span[name='out_date']").text('');
				$obj.find("span[name='vcs_status']").text(str_vcs_status);

				var $btnin = $obj.find("span[name='btnin']");
				var $btnincancel = $obj.find("span[name='btnincancel']");
				var $btnout = $obj.find("span[name='btnout']");
				var $btnoutcancel = $obj.find("span[name='btnoutcancel']");

				if (new_vcs_status == 'IN') {
					$btnin.hide();
					$btnincancel.show();
					$btnout.show();
					$btnoutcancel.hide();
				} else {
					$btnin.show();
					$btnincancel.hide();
					$btnout.hide();
					$btnoutcancel.hide();
				}
			}

			alert(data.msg);
		},
		'json'
	);

	return false;
}

function ResultCheckOutSubmit() {
	var v_wvcs_seq = $('#v_wvcs_seq').val();
	var in_available_date = $('#in_available_date').text();
	var in_date = $('#in_date').text();
	var out_date = $('#out_date').text();
	var new_vcs_status = out_date == '' ? 'OUT' : 'IN';

	if (in_date == '') {
		alert(qneedcheckin[lang_code]);
		ResultCheckInSubmit();
		return false;
	}

	var command = new_vcs_status == 'OUT' ? checkouttext[lang_code] : checkoutcanceltext[lang_code];
	var msg = qaccess[lang_code].replace('##', command);

	if (!confirm(msg)) return false;

	$.post(
		'result_checkout_process.php',
		{
			v_wvcs_seq: v_wvcs_seq,
			vcs_status: new_vcs_status,
		},
		function (data) {
			if (data.status) {
				var out_date = new_vcs_status == 'OUT' ? data.result.out_dt.substring(0, 16) : '';
				var str_vcs_status =
					new_vcs_status == 'OUT' ? checkoutoktext[lang_code] : checkinoktext[lang_code];

				$('#vcs_status').text(str_vcs_status);
				$('#out_date').text(out_date);

				if (new_vcs_status == 'OUT') {
					$('#btnApprvOut').text(checkoutcanceltext[lang_code]);
				} else if (new_vcs_status == 'IN') {
					$('#btnApprvOut').text(checkouttext[lang_code]);
				}
			}

			alert(data.msg);
		},
		'json'
	);

	return;
}

function ResultCheckOutSubmit2(obj) {
	var $obj = $(obj).parent().parent().parent();

	var v_wvcs_seq = $obj.find("span[name='seq']").text();
	var out_date = $obj.find("span[name='out_date']").text();

	var command = '',
		new_vcs_status = '';

	if (out_date == '') {
		command = checkouttext[lang_code];
		new_vcs_status = 'OUT';
	} else {
		command = checkoutcanceltext[lang_code];
		new_vcs_status = 'IN';
	}

	var msg = qaccess[lang_code].replace('##', command);

	if (!confirm(msg)) return false;

	$.post(
		'result_checkout_process.php',
		{
			v_wvcs_seq: v_wvcs_seq,
			vcs_status: new_vcs_status,
		},
		function (data) {
			if (data.status) {
				var out_date = '',
					str_vcs_status = '';

				if (new_vcs_status == 'OUT') {
					out_date = data.result.out_dt.substring(0, 16);
					str_vcs_status = checkoutoktext[lang_code];
				} else {
					out_date = '';
					str_vcs_status = checkinoktext[lang_code];
				}

				$obj.find("span[name='out_date']").text(out_date);
				$obj.find("span[name='vcs_status']").text(str_vcs_status);

				var $btnout = $obj.find("span[name='btnout']");
				var $btnoutcancel = $obj.find("span[name='btnoutcancel']");

				if (new_vcs_status == 'OUT') {
					$btnout.hide();
					$btnoutcancel.show();
				} else {
					$btnout.show();
					$btnoutcancel.hide();
				}
			}

			alert(data.msg);
		},
		'json'
	);

	return false;
}

function popScanResize() {
	$("#mark div[class='wrapper2']").height(
		$("#mark div[class='content']").height() - $("#mark div[name='scanheader']").height() - 40
	);
}

function ScanBarcode(obj) {
	var event = window.event || e;

	if (event.keyCode == '13') {
		var barcode = $(obj).val();

		$('#str_barcode').text(barcode);

		$('#barcode').val('');

		if (isNaN(barcode)) return false;

		var callback = function () {
			var v_wvcs_seq = $('#v_wvcs_seq').val();

			LoadDiskInfo(v_wvcs_seq);
			LoadVaccineInfo(v_wvcs_seq);

			popScanResize();
		};

		LoadScanVcsInfo(barcode, callback);
	}
}

function ChangeVcsStatus() {
	var vcs_status = $('#vcs_status').val();

	$('#apprv_name').prop('disabled', vcs_status != 'PERMIT');
	$('#in_dt').prop('disabled', vcs_status != 'PERMIT');
	$('#in_available_dt').prop('disabled', vcs_status == 'CHECK');

	if (vcs_status == 'PERMIT') {
		$('#in_available_dt').val($('#in_available_dt').attr('temp_value'));
		$('#apprv_name').val($('#apprv_name').attr('temp_value'));
		$('#apprv_dt').val(new Date().dateformat('yyyy-mm-dd hh:mm:ss'));
		$('#in_dt').val($('#in_dt').attr('temp_value'));
	} else {
		if (vcs_status == 'CHECK') {
			$('#in_available_dt').val('');
		} else {
			$('#in_available_dt').val($('#in_available_dt').attr('temp_value'));
		}

		$('#apprv_name').val('');
		$('#apprv_dt').val('');
		$('#in_dt').val('');
	}
}
function ChangeVcsStorageStatus() {
	var vcs_status = $('#vcs_status').val();

	$('#in_dt').prop('disabled', vcs_status != 'PERMIT');
	$('#in_available_dt').prop('disabled', vcs_status == 'CHECK');

	if (vcs_status == 'PERMIT') {
		$('#in_available_dt').val($('#in_available_dt').attr('temp_value'));
		$('#in_dt').val($('#in_dt').attr('temp_value'));
	} else {
		if (vcs_status == 'CHECK') {
			$('#in_available_dt').val('');
		} else {
			$('#in_available_dt').val($('#in_available_dt').attr('temp_value'));
		}

		$('#in_dt').val('');
	}
}
function LoadPcInfo(v_wvcs_seq) {
	if ($('#pc_info').text() != '') return;

	$.post(
		SITE_NAME + '/result/get_pc_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#pc_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}
function LoadDiskInfo(v_wvcs_seq) {
	//if($("#disk_info").text() != "") return;

	$.post(
		SITE_NAME + '/result/get_disk_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#disk_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}
function LoadScanTimeLog(v_wvcs_seq) {
	//if($("#disk_info").text() != "") return;

	$.post(
		SITE_NAME + '/result/get_scan_time_log.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#scan_time_log').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}
function LoadScanVcsInfo(barcode, callback) {
	//if($("#vcs_info").text() != "") return;

	var checkinout_flag = $('#checkinout_flag').val();

	$.post(
		SITE_NAME + '/result/get_scan_vcs_info.php',
		{
			barcode: barcode,
			checkinout_flag: checkinout_flag,
		},
		function (data) {
			$('#scan_vcs_info').html(data);
			controllPageExecAuth();

			if (typeof callback == 'function') callback();

			setTimeout(function () {
				$('#scan_alert_msg').fadeOut(500);
			}, 4000);
		},
		'text'
	);
}
function LoadInstallProgramInfo(v_wvcs_seq) {
	//if($("#install_program_info").text() != "") return;

	$.post(
		SITE_NAME + '/result/get_installprogram_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#install_program_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadWeaknessInfo(v_wvcs_seq) {
	//if($("#weakness_info").text() != "") return;

	$.post(
		SITE_NAME + '/result/get_weakness_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#weakness_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadVaccineInfo(v_wvcs_seq) {
	//if($("#vaccine_info").text() != "") return;

	$.post(
		SITE_NAME + '/result/get_vaccine_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#vaccine_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadWindowUpdateInfo(v_wvcs_seq) {
	if ($('#window_update_info').text() != '') return;

	$.post(
		SITE_NAME + '/result/get_windowupdate_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#window_update_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

//**[[popup vcs_result_view
function LoadWeaknessInfo2(v_wvcs_seq) {
	if ($('#weakness_info2').text() != '') return;

	$.post(
		SITE_NAME + '/result/get_weakness_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#weakness_info2').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadVaccineInfo2(v_wvcs_seq) {
	if ($('#vaccine_info2').text() != '') return;

	$.post(
		SITE_NAME + '/result/get_vaccine_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#vaccine_info2').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function LoadWindowUpdateInfo2(v_wvcs_seq) {
	if ($('#window_update_info2').text() != '') return;

	$.post(
		SITE_NAME + '/result/get_windowupdate_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#window_update_info2').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}
//popup vcs_result_view]]**

function LoadPageDataList(obj_id, url, param) {
	//alert(url+"?"+param);

	$.post(
		url + '?' + param,
		function (data) {
			$('#' + obj_id).html(data);
			controllPageExecAuth();
		},
		'text'
	);

}

var oChart = {
	barData: function (x_labels, ds_lables, data_labels, values, bgcolors, bordercolors) {
		if (x_labels == null || ds_lables == null || values == null) return;

		var ChartData = {
			labels: x_labels,
			datasets: [
				{
					label: ds_lables,
					backgroundColor: bgcolors,
					borderColor: bordercolors,
					borderWidth: 1,
					data: values,
					data_label: data_labels,
				},
			],
		};

		return ChartData;
	},

	doughnutData: function (ids, labels, values, data_links, colors) {
		if (labels == null || values == null) return;

		if (colors == null) colors = dynamicChartColors(window.chartColors, 1, labels.length);

		var ChartData = {
			labels: labels,
			datasets: [
				{
					id: ids,
					data: values,
					data_link: data_links,
					backgroundColor: colors,
				},
			],
		};

		return ChartData;
	},
};

function bindBarChart(id, Chartdata) {
	if (typeof window['myBar_' + id] != 'undefined') {
		window['myBar_' + id].destroy();
	}

	var ctx = document.getElementById(id).getContext('2d');
	window['myBar_' + id] = new Chart(ctx, {
		type: 'bar',
		data: Chartdata,
		options: {
			scales: {
				xAxes: [
					{
						maxBarThickness: 40,
					},
				],
				yAxes: [
					{
						ticks: {
							beginAtZero: true,
							//stepSize : 1,
							min: 0,
							callback: function (value, index, values) {
								if (Math.floor(value) === value) {
									return value;
								}
							},
						},
					},
				],
			},
			responsive: true,
			maintainAspectRatio: false,
			legend: {
				position: 'top',
			},
			title: {
				display: true,
				//text: "Chart.js Bar Chart"
			},
		},
	});

	//차트 클릭 이벤트
	var myChart = window['myBar_' + id];

	document.getElementById(id).onclick = function (evt) {
		var activePoints = myChart.getElementsAtEvent(evt);
		var activeDataset = myChart.getDatasetAtEvent(evt);

		if (activePoints == '') return;

		var clickedElementindex = activePoints[0]._index;
		var clickedDatasetindex = activeDataset[0]._datasetIndex;

		//var xAxis = myChart.data.labels[clickedElementindex];
		var value = myChart.data.datasets[clickedDatasetindex].data[clickedElementindex];
		var label = '',
			data_label = '',
			link = '';

		if (myChart.data.datasets[clickedDatasetindex].data_link != undefined 
			&& myChart.data.datasets[clickedDatasetindex].data_link != "") {
			link = myChart.data.datasets[clickedDatasetindex].data_link[clickedElementindex];

			location.href = link;
			return;
		}
	};
}
var chart_tooltip_position = { x: 0, y: 0 };
function bindDoughnutChart(id, Chartdata) {
	if (typeof window['myDoughnut_' + id] != 'undefined') {
		window['myDoughnut_' + id].destroy();
	}

	$('#' + id).bind('mousemove', function (event) {
		event = event || window.event;
		event = jQuery.event.fix(event);
		chart_tooltip_position.x = event.pageX;
		chart_tooltip_position.y = event.pageY;
	});

	var ctx = document.getElementById(id).getContext('2d');

	if (typeof Chartdata == 'undefined') {
		ctx.font = '12px Arial';
		ctx.textAlign = 'center';
		ctx.fillText(nodatatext[lang_code], 150, 100);
		document.getElementById(id + '_legend').innerHTML = '';
	} else {
		window['myDoughnut_' + id] = new Chart(ctx, {
			type: 'doughnut',
			data: Chartdata,
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					position: 'right',
					display: false,
					labels: {
						padding: 15,
						fontFamily: 'MalgunGothic',
						fontColor: 'rgb(120, 120, 120)',
					},
				},
				title: {
					display: false,
				},
				animation: {
					animateScale: true,
					animateRotate: true,
				},
				layout: {
					padding: {
						left: 0,
						right: 0,
						top: 0,
						bottom: 0,
					},
				},
				legendCallback: function (chart) {
					var text = [];
					text.push('<ul class="' + chart.id + '-legend">');

					var data = chart.data;
					var datasets = data.datasets;
					var labels = data.labels;

					if (datasets.length) {
						var total = 0;
						var percentage = 0;

						for (var i = 0; i < datasets[0].data.length; ++i) {
							total += datasets[0].data[i];
						}

						for (var i = 0; i < datasets[0].data.length; ++i) {
							text.push(
								'<li><span style="background-color:' + datasets[0].backgroundColor[i] + '"></span>'
							);

							if (labels[i]) {
								var percentage = 0;
								var cnt = datasets[0].data[i];

								if (total > 0) {
									percentage = Math.round((cnt / total) * 100);
								}

								text.push(labels[i] + ' (' + addCommas(cnt) + ')');

								//text.push(labels[i] + ' (' + addCommas(cnt)+" of "+addCommas(total)+' ,' + percentage + '%' + ')');
							}
							text.push('</li>');
						}
					}
					text.push('</ul>');
					return text.join('');
				},
				tooltips: {
					enabled: false,
					custom: function (tooltipModel) {
						// Tooltip Element
						var tooltipEl = document.getElementById('chartjs-tooltip');

						// Create element on first render
						if (!tooltipEl) {
							tooltipEl = document.createElement('div');
							tooltipEl.id = 'chartjs-tooltip';
							tooltipEl.innerHTML = '<table></table>';
							document.body.appendChild(tooltipEl);
						}

						// Hide if no tooltip
						if (tooltipModel.opacity === 0) {
							tooltipEl.style.opacity = 0;
							return;
						}

						// Set caret Position
						tooltipEl.classList.remove('above', 'below', 'no-transform');
						if (tooltipModel.yAlign) {
							tooltipEl.classList.add(tooltipModel.yAlign);
						} else {
							tooltipEl.classList.add('no-transform');
						}

						function getBody(bodyItem) {
							return bodyItem.lines;
						}

						// Set Text
						if (tooltipModel.body) {
							var titleLines = tooltipModel.title || [];
							var bodyLines = tooltipModel.body.map(getBody);

							var innerHtml = '<thead>';

							titleLines.forEach(function (title) {
								innerHtml += '<tr><th>' + title + '</th></tr>';
							});
							innerHtml += '</thead><tbody>';

							bodyLines.forEach(function (body, i) {
								var colors = tooltipModel.labelColors[i];
								var style = 'background:' + colors.backgroundColor;
								style += '; border-color:' + colors.borderColor;
								style += '; border-width: 2px';
								var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
								innerHtml +=
									'<tr><td>' + span + "<span style='color:white'> " + body + '</span></td></tr>';
							});
							innerHtml += '</tbody>';

							var tableRoot = tooltipEl.querySelector('table');
							tableRoot.innerHTML = innerHtml;
						}

						// `this` will be the overall tooltip
						//var position = this._chart.canvas.getBoundingClientRect();

						var x = chart_tooltip_position.x;
						var y = chart_tooltip_position.y;

						// Display, position, and set styles for font
						tooltipEl.style.opacity = 1;
						tooltipEl.style.zIndex = '999999999999';
						tooltipEl.style.position = 'absolute';
						//tooltipEl.style.left = position.left + tooltipModel.caretX + 'px';
						//tooltipEl.style.top = position.top + tooltipModel.caretY + 'px';
						tooltipEl.style.left = x + 'px';
						tooltipEl.style.top = y + 'px';
						tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
						tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
						tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
						tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
					},
					callbacks: {
						title: function (tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function (tooltipItem, data) {
							var total = 0;
							for (i = 0; i < data['datasets'][0]['data'].length; i++) {
								total += data['datasets'][0]['data'][i];
							}
							var percentage = Math.round(
								(data['datasets'][0]['data'][tooltipItem['index']] / total) * 100
							);

							var cnt = data['datasets'][0]['data'][tooltipItem['index']];

							return addCommas(cnt) + ' of ' + addCommas(total) + ' (' + percentage + '%)';
						},
					},
					backgroundColor: '#000',
					titleFontSize: 10,
					titleFontColor: '#fff',
					bodyFontColor: '#fff',
					bodyFontSize: 10,
					displayColors: true,
				},
			}, // options:
		});

		document.getElementById(id + '_legend').innerHTML = window['myDoughnut_' + id].generateLegend();

		$('#' + id + '_legend > ul > li').bind('click', function (e) {
			var index = $(this).index();
			$(this).toggleClass('strike');
			var ci = e.view.window['myDoughnut_' + id];
			var meta = ci.getDatasetMeta(0);

			if (meta.dataset) {
				meta.hidden = !meta.hidden;
			} else {
				meta.data[index].hidden = !meta.data[index].hidden;
			}

			ci.update();
		});
	} //**if(typeof Chartdata == 'undefined'){

	//차트 클릭 이벤트
	var myChart = window['myDoughnut_' + id];

	if (myChart == undefined) return;

	document.getElementById(id).onclick = function (evt) {
		var activePoints = myChart.getElementsAtEvent(evt);

		if (activePoints == '') return;

		var clickedElementindex = activePoints[0]._index;
		var clickedDatasetindex = activePoints[0]._datasetIndex;

		var link = '';

		if (myChart.data.datasets[activePoints[0]._datasetIndex].data_link != undefined
			&& myChart.data.datasets[activePoints[0]._datasetIndex].data_link != "") {
			link = myChart.data.datasets[clickedDatasetindex].data_link[clickedElementindex];

			location.href = link;
			return;
		}
	};
}
function dynamicChartColors(color, alpha, size) {
	if (color.length >= size) {
		for (var i = 0; i < color.length; i++) {
			var _color = color[i];
			_color = _color.replace(/,1\)/i,','+alpha+')');
			color[i] = _color;
		}
	} else {
		var cnt = size - color.length;
		for (var i = 0; i < cnt; i++) {
			var r = Math.floor(Math.random() * 255);
			var g = Math.floor(Math.random() * 255);
			var b = Math.floor(Math.random() * 255);
			var rgba = 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';

			if ($.inArray(rgba, color) >= 0) {
				rgba = dynamicChartColors(color, alpha, size);
			}
			color.push(rgba);
		}
		
	}
	return color;
}
function StatisticsDayVcsData(date) {
	$.post(
		SITE_NAME + '/stat/vcs_stat_process.php',
		{
			date: date,
		},
		function (data) {
			if (data.status) {
				var pc_check_data = addCommas(data.result.pc_check_data);
				var pc_weak_data = addCommas(data.result.pc_weak_data);
				var pc_virus_data = addCommas(data.result.pc_virus_data);
				var storage_check_data = addCommas(data.result.storage_check_data);
				var storage_virus_data = addCommas(data.result.storage_virus_data);

				var url = SITE_NAME + '/result/result_list.php';
				var param1 =
					'src=chart&asset_type=NOTEBOOK&check_result1=all&checkdate1=' +
					date +
					'&checkdate2=' +
					date;
				var param2 =
					'src=chart&asset_type=RemovableDevice&check_result1=all&checkdate1=' +
					date +
					'&checkdate2=' +
					date;

				$('#ChartPcCheckData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param1 + '&check_result2=') +
						'\'">' +
						pc_check_data +
						'</span>'
				);
				$('#ChartPcWeakData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param1 + '&check_result2=weak') +
						'\'">' +
						pc_weak_data +
						'</span>'
				);
				$('#ChartPcVirusData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param1 + '&check_result2=virus') +
						'\'">' +
						pc_virus_data +
						'</span>'
				);
				$('#ChartStorageCheckData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param2 + '&check_result2=') +
						'\'">' +
						storage_check_data +
						'</span>'
				);
				$('#ChartStorageVirusData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param2 + '&check_result2=virus') +
						'\'">' +
						storage_virus_data +
						'</span>'
				);

				var ids = [];
				var labels = [];
				var values = new Array('1', '2');
				var data_links = [];

				bindDoughnutChartMain(
					'ChartPcCheck',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#29b0d0'))
				);
				bindDoughnutChartMain(
					'ChartPcWeak',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#ffab34'))
				);
				bindDoughnutChartMain(
					'ChartPcVirus',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
				);
				bindDoughnutChartMain(
					'ChartStorageCheck',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				bindDoughnutChartMain(
					'ChartStorageVirus',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
				);
			} else {
				if (typeof window.myDoughnut != 'undefined') {
					window.myDoughnut.destroy();
				}

				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function GetUserCheckList(gubun, param) {
	var obj_id = 'user_check_list';
	var url = SITE_NAME + '/result/get_user_check_list.php';

	var param_enc = 'enc=' + ParamEnCoding(param);
	var str_gubun = '';

	switch (gubun) {
		case 'weak':
			str_gubun = weaknessdectiontext[lang_code];
			break;
		case 'virus':
			str_gubun = virusdectiontext[lang_code];
			break;
		case 'notebook':
			str_gubun = laptoptext[lang_code];
			break;
		case 'hdd':
			str_gubun = hddtext[lang_code];
			break;
		case 'removable':
			str_gubun = removabletext[lang_code];
			break;
		case 'cddvd':
			str_gubun = cddvdtext[lang_code];
			break;
		case 'etc':
			str_gubun = etctext[lang_code];
			break;
	}

	$('#vcs_title').text(str_gubun);

	LoadPageDataList(obj_id, url, param_enc);
}

function StatisticsComVcsData(v_com_seq) {
	$.post(
		SITE_NAME + '/stat/com_vcs_stat_process.php',
		{
			v_com_seq: v_com_seq,
		},
		function (data) {
			if (data.status) {
				var vcs_data = addCommas(data.result.vcs_cnt);
				var weak_data = addCommas(data.result.weak_cnt);
				var virus_data = addCommas(data.result.virus_cnt);

				var device_notebook_data = data.result.notebook;
				var device_hdd_data = data.result.hdd;
				var device_removeable_data = data.result.removable;
				//var device_cddvd_data =  data.result.cddvd;
				var device_etc_data = data.result.etc;

				var obj_id = 'user_check_list';
				var url = SITE_NAME + '/result/get_user_check_list.php';

				var param = 'src=COM_INFO_VIEW&v_com_seq=' + v_com_seq;
				var param1 = param;
				var param2 = param + '&check_result2=weak';
				var param3 = param + '&check_result2=virus';
				var param4 = param + '&v_asset_type=NOTEBOOK';
				var param5 = param + '&v_asset_type=RemovableDevice&storage_device_type=HDD';
				var param6 = param + '&v_asset_type=RemovableDevice&storage_device_type=Removable';
				//var param7 = param+"&v_asset_type=RemovableDevice&storage_device_type=CD/DVD";
				var param8 = param + '&v_asset_type=RemovableDevice&storage_device_type=DEVICE_ETC';

				$('#ChartVcsData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('all','" +
						param1 +
						'\')">' +
						vcs_data +
						'</span>'
				);
				$('#ChartWeakData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('weak','" +
						param2 +
						'\')">' +
						weak_data +
						'</span>'
				);
				$('#ChartVirusData').html(
					"<span style='cursor:pointer;'onclick=\"GetUserCheckList('virus','" +
						param3 +
						'\')">' +
						virus_data +
						'</span>'
				);

				$('#ChartNotebookData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('notebook','" +
						param4 +
						'\')">' +
						device_notebook_data.vcs_cnt +
						'</span>'
				);
				$('#ChartHddData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('hdd','" +
						param5 +
						'\')">' +
						device_hdd_data.vcs_cnt +
						'</span>'
				);
				$('#ChartRemovableData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('removable','" +
						param6 +
						'\')">' +
						device_removeable_data.vcs_cnt +
						'</span>'
				);
				//$("#ChartCdDvdData").html("<span style='cursor:pointer;' onclick=\"GetUserCheckList('cddvd','"+param7+"')\">"+device_cddvd_data.vcs_cnt+"</span>");
				$('#ChartEtcData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('etc','" +
						param8 +
						'\')">' +
						device_etc_data.vcs_cnt +
						'</span>'
				);

				$('#NoteBookCnt').text(device_notebook_data.device_cnt);
				$('#HddCnt').text(device_hdd_data.device_cnt);
				$('#RemovableCnt').text(device_removeable_data.device_cnt);
				//$("#CdDvdCnt").text(device_cddvd_data.device_cnt);
				$('#EtcCnt').text(device_etc_data.device_cnt);

				var ids = [];
				var labels = [];
				var values = new Array('1', '2');
				var data_links = [];

				bindDoughnutChartUser(
					'ChartVcs',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#29b0d0'))
				);
				bindDoughnutChartUser(
					'ChartWeak',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#ffab34'))
				);
				bindDoughnutChartUser(
					'ChartVirus',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
				);

				bindDoughnutChartUser(
					'ChartNotebook',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				bindDoughnutChartUser(
					'ChartHdd',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				bindDoughnutChartUser(
					'ChartRemovable',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				//bindDoughnutChartUser("ChartCdDvd",oChart.doughnutData(ids,labels,values,data_links,new Array("#dedfe8","#397ecc")))
				bindDoughnutChartUser(
					'ChartEtc',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
			} else {
				if (typeof window.myDoughnut != 'undefined') {
					window.myDoughnut.destroy();
				}

				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function StatisticsUserVcsData(v_user_seq) {
	$.post(
		SITE_NAME + '/stat/user_vcs_stat_process.php',
		{
			v_user_seq: v_user_seq,
		},
		function (data) {
			if (data.status) {
				var vcs_data = addCommas(data.result.vcs_cnt);
				var weak_data = addCommas(data.result.weak_cnt);
				var virus_data = addCommas(data.result.virus_cnt);

				var device_notebook_data = data.result.notebook;
				var device_hdd_data = data.result.hdd;
				var device_removeable_data = data.result.removable;
				//var device_cddvd_data =  data.result.cddvd;
				var device_etc_data = data.result.etc;

				var obj_id = 'user_check_list';
				var url = SITE_NAME + '/result/get_user_check_list.php';

				var param = 'src=USER_INFO_VIEW&v_user_seq=' + v_user_seq;
				var param1 = param;
				var param2 = param + '&check_result2=weak';
				var param3 = param + '&check_result2=virus';
				var param4 = param + '&v_asset_type=NOTEBOOK';
				var param5 = param + '&v_asset_type=RemovableDevice&storage_device_type=HDD';
				var param6 = param + '&v_asset_type=RemovableDevice&storage_device_type=Removable';
				//var param7 = param+"&v_asset_type=RemovableDevice&storage_device_type=CD/DVD";
				var param8 = param + '&v_asset_type=RemovableDevice&storage_device_type=DEVICE_ETC';

				$('#ChartVcsData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('all','" +
						param1 +
						'\')">' +
						vcs_data +
						'</span>'
				);
				$('#ChartWeakData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('weak','" +
						param2 +
						'\')">' +
						weak_data +
						'</span>'
				);
				$('#ChartVirusData').html(
					"<span style='cursor:pointer;'onclick=\"GetUserCheckList('virus','" +
						param3 +
						'\')">' +
						virus_data +
						'</span>'
				);

				$('#ChartNotebookData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('notebook','" +
						param4 +
						'\')">' +
						device_notebook_data.vcs_cnt +
						'</span>'
				);
				$('#ChartHddData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('hdd','" +
						param5 +
						'\')">' +
						device_hdd_data.vcs_cnt +
						'</span>'
				);
				$('#ChartRemovableData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('removable','" +
						param6 +
						'\')">' +
						device_removeable_data.vcs_cnt +
						'</span>'
				);
				//$("#ChartCdDvdData").html("<span style='cursor:pointer;' onclick=\"GetUserCheckList('cddvd','"+param7+"')\">"+device_cddvd_data.vcs_cnt+"</span>");
				$('#ChartEtcData').html(
					"<span style='cursor:pointer;' onclick=\"GetUserCheckList('etc','" +
						param8 +
						'\')">' +
						device_etc_data.vcs_cnt +
						'</span>'
				);

				$('#NoteBookCnt').text(device_notebook_data.device_cnt);
				$('#HddCnt').text(device_hdd_data.device_cnt);
				$('#RemovableCnt').text(device_removeable_data.device_cnt);
				//$("#CdDvdCnt").text(device_cddvd_data.device_cnt);
				$('#EtcCnt').text(device_etc_data.device_cnt);

				var ids = [];
				var labels = [];
				var values = new Array('1', '2');
				var data_links = [];

				bindDoughnutChartUser(
					'ChartVcs',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#29b0d0'))
				);
				bindDoughnutChartUser(
					'ChartWeak',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#ffab34'))
				);
				bindDoughnutChartUser(
					'ChartVirus',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
				);

				bindDoughnutChartUser(
					'ChartNotebook',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				bindDoughnutChartUser(
					'ChartHdd',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				bindDoughnutChartUser(
					'ChartRemovable',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				//bindDoughnutChartUser("ChartCdDvd",oChart.doughnutData(ids,labels,values,data_links,new Array("#dedfe8","#397ecc")))
				bindDoughnutChartUser(
					'ChartEtc',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
			} else {
				if (typeof window.myDoughnut != 'undefined') {
					window.myDoughnut.destroy();
				}

				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function StatisticsUserDeviceVcsData(v_user_seq) {
	var data_id = 'user_device_vcs_list';
	var url = SITE_NAME + '/user/get_user_device_vcs_list.php';
	var param = 'src=pop_user_vcs_summary&v_user_seq=' + v_user_seq + '&device_gubun=';
	var param_enc0 = 'enc=' + ParamEnCoding(param);
	var param_enc1 = 'enc=' + ParamEnCoding(param + 'NOTEBOOK');
	var param_enc2 = 'enc=' + ParamEnCoding(param + 'HDD');
	var param_enc3 = 'enc=' + ParamEnCoding(param + 'Removable');
	var param_enc4 = 'enc=' + ParamEnCoding(param + 'ETC');

	var all_cnt = $('#ChartAllData').text();
	var notebook_cnt = $('#ChartNotebookData').text();
	var hdd_cnt = $('#ChartHddData').text();
	var removable_cnt = $('#ChartRemovableData').text();
	var etc_cnt = $('#ChartEtcData').text();

	$('#ChartAllData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			data_id +
			"','" +
			url +
			"','" +
			param_enc0 +
			'\')">' +
			all_cnt +
			'</span>'
	);
	$('#ChartNotebookData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			data_id +
			"','" +
			url +
			"','" +
			param_enc1 +
			'\')">' +
			notebook_cnt +
			'</span>'
	);
	$('#ChartHddData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			data_id +
			"','" +
			url +
			"','" +
			param_enc2 +
			'\')">' +
			hdd_cnt +
			'</span>'
	);
	$('#ChartRemovableData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			data_id +
			"','" +
			url +
			"','" +
			param_enc3 +
			'\')">' +
			removable_cnt +
			'</span>'
	);
	$('#ChartEtcData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			data_id +
			"','" +
			url +
			"','" +
			param_enc4 +
			'\')">' +
			etc_cnt +
			'</span>'
	);

	var ids = [];
	var labels = [];
	var values = new Array('1', '2');
	var data_links = [];

	bindDoughnutChartUser(
		'ChartAll',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartNotebook',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartHdd',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartRemovable',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartEtc',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);

	bindDoughnutChartUser2(
		'ChartWeak',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#ffab34'))
	);
	bindDoughnutChartUser2(
		'ChartVirus',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
	);
}

function StatisticsComDeviceVcsData(v_com_seq) {
	var list_id = 'com_device_vcs_list';
	var url = SITE_NAME + '/user/get_com_device_vcs_list.php';
	var param = 'src=pop_com_vcs_summary&v_com_seq=' + v_com_seq;
	var param_enc0 = 'enc=' + ParamEnCoding(param);
	var param_enc1 = 'enc=' + ParamEnCoding(param + '&device_gubun=NOTEBOOK');
	var param_enc2 = 'enc=' + ParamEnCoding(param + '&device_gubun=HDD');
	var param_enc3 = 'enc=' + ParamEnCoding(param + '&device_gubun=Removable');
	var param_enc4 = 'enc=' + ParamEnCoding(param + '&device_gubun=ETC');

	var url2 = SITE_NAME + '/user/get_com_user_list.php';

	var user_cnt = $('#ChartUserData').text();
	var all_cnt = $('#ChartAllData').text();
	var notebook_cnt = $('#ChartNotebookData').text();
	var hdd_cnt = $('#ChartHddData').text();
	var removable_cnt = $('#ChartRemovableData').text();
	var etc_cnt = $('#ChartEtcData').text();

	$('#ChartUserData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			list_id +
			"','" +
			url2 +
			"','" +
			param_enc0 +
			'\')">' +
			user_cnt +
			'</span>'
	);

	$('#ChartAllData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			list_id +
			"','" +
			url +
			"','" +
			param_enc0 +
			'\')">' +
			all_cnt +
			'</span>'
	);
	$('#ChartNotebookData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			list_id +
			"','" +
			url +
			"','" +
			param_enc1 +
			'\')">' +
			notebook_cnt +
			'</span>'
	);
	$('#ChartHddData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			list_id +
			"','" +
			url +
			"','" +
			param_enc2 +
			'\')">' +
			hdd_cnt +
			'</span>'
	);
	$('#ChartRemovableData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			list_id +
			"','" +
			url +
			"','" +
			param_enc3 +
			'\')">' +
			removable_cnt +
			'</span>'
	);
	$('#ChartEtcData').html(
		"<span style='cursor:pointer;' onclick=\"LoadPageDataList('" +
			list_id +
			"','" +
			url +
			"','" +
			param_enc4 +
			'\')">' +
			etc_cnt +
			'</span>'
	);

	var ids = [];
	var labels = [];
	var values = new Array('1', '2');
	var data_links = [];

	//bindDoughnutChartUser("ChartUser",oChart.doughnutData(ids,labels,values,data_links,new Array("#dedfe8","#9E69FE")));

	bindDoughnutChartUser(
		'ChartAll',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartNotebook',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartHdd',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartRemovable',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);
	bindDoughnutChartUser(
		'ChartEtc',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
	);

	bindDoughnutChartUser2(
		'ChartWeak',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#ffab34'))
	);
	bindDoughnutChartUser2(
		'ChartVirus',
		oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
	);
}

function bindDoughnutChartUser(id, Chartdata) {
	if (typeof window['myDoughnut_' + id] != 'undefined') {
		window['myDoughnut_' + id].destroy();
	}

	var ctx = document.getElementById(id).getContext('2d');

	if (typeof Chartdata == 'undefined') {
		ctx.font = '12px Arial';
		ctx.fillText(nodatatext[lang_code], 150, 150);
	} else {
		window['myDoughnut_' + id] = new Chart(ctx, {
			type: 'doughnut',
			data: Chartdata,
			options: {
				cutoutPercentage: 65,
				animation: {
					animateScale: true,
					animateRotate: true,
				},
				tooltips: {
					enabled: false,
				},
				legend: {
					display: false,
				},
				layout: {
					padding: {
						left: 0,
						right: 0,
						top: 0,
						bottom: 0,
					},
				},
			},
		});
	} //**if(typeof Chartdata == 'undefined'){
}

function bindDoughnutChartUser2(id, Chartdata) {
	if (typeof window['myDoughnut_' + id] != 'undefined') {
		window['myDoughnut_' + id].destroy();
	}

	var ctx = document.getElementById(id).getContext('2d');

	if (typeof Chartdata == 'undefined') {
		ctx.font = '12px Arial';
		ctx.fillText(nodatatext[lang_code], 150, 150);
	} else {
		window['myDoughnut_' + id] = new Chart(ctx, {
			type: 'doughnut',
			data: Chartdata,
			options: {
				cutoutPercentage: 65,
				animation: {
					animateScale: true,
					animateRotate: true,
				},
				tooltips: {
					enabled: false,
				},
				layout: {
					padding: {
						left: 0,
						right: 0,
						top: 0,
						bottom: 0,
					},
				},
				legend: {
					display: false,
				},
				/*
				,plugins: {
				  labels: {
					render: function (args) {
						if(args.label){
							return args.label+ ":" + args.value;
						}else{
							return "";
						}
					},
					arc: true,
					fontSize: 11,
					fontStyle: 'bold',
					fontColor: '#000',
					fontFamily: '"Lucida Console", Monaco, monospace'

				  }

				}//,plugins: {
				*/
			},
		});
	} //**if(typeof Chartdata == 'undefined'){
}

function bindDoughnutChartMain(id, Chartdata) {
	if (typeof window['myDoughnut_' + id] != 'undefined') {
		window['myDoughnut_' + id].destroy();
	}

	var ctx = document.getElementById(id).getContext('2d');

	if (typeof Chartdata == 'undefined') {
		ctx.font = '12px Arial';
		ctx.fillText(nodatatext[lang_code], 150, 150);
	} else {
		window['myDoughnut_' + id] = new Chart(ctx, {
			type: 'doughnut',
			data: Chartdata,
			options: {
				cutoutPercentage: 70,
				animation: {
					animateScale: true,
					animateRotate: true,
				},
				tooltips: {
					enabled: false,
				},
				layout: {
					padding: {
						left: 0,
						right: 200,
						top: 0,
						bottom: 0,
					},
				},
			},
		});
	} //**if(typeof Chartdata == 'undefined'){
}

function ChartResizeMain() {
	var w = $('#main').width();

	//alert(w);

	//**Chart Resize
	if (w > 1300) {
		$('.section canvas').width(500);
		$('.section .txt').css('bottom', '-30px');
		$('.section .txt2').css('top', '100px');
	} else {
		$('.section canvas').width(400);
		$('.section .txt').css('bottom', '0px');
		$('.section .txt2').css('top', '80px');
	}
}

function CallStatisticsComCheckData(v_com_seq) {
	$('#v_com_seq').val(v_com_seq);

	var v_com_name = v_com_seq ? $('#str_com_' + v_com_seq).text() : alltext[lang_code];

	$("div h1 span[name='tit_com_name']").text(v_com_name);

	var onTab = $('#onTab').val();

	if (onTab == 'DAYLIST') {
		StatisticsComCheckData('DAY');
	} else if (onTab == 'MONTHLIST') {
		StatisticsComCheckData('MONTH');
	}

	StatisticsComCheckData('WEAK');
	StatisticsComCheckData('VIRUS');
}

function StatisticsComCheckData(gubun) {
	var v_com_seq = $('#v_com_seq').val();
	var year = '';
	var month = '';
	var asset_type = '';

	if (gubun == 'DAY') {
		year = $(".tab>li[name='DAYLIST'] div select[name='year']").val();
		month = $(".tab>li[name='DAYLIST'] div select[name='month']").val();
		asset_type = $(".tab>li[name='DAYLIST'] div select[name='asset_type']").val();
	} else if (gubun == 'MONTH') {
		year = $(".tab>li[name='MONTHLIST'] div select[name='year']").val();
		month = '';
		asset_type = $(".tab>li[name='MONTHLIST'] div select[name='asset_type']").val();
	} else if (gubun == 'WEAK') {
		year = $(".section02 select[name='year']").val();
		month = $(".section02 select[name='month']").val();
		asset_type = $(".section02 select[name='asset_type']").val();
	} else if (gubun == 'VIRUS') {
		year = $(".section03 select[name='year']").val();
		month = $(".section03 select[name='month']").val();
		asset_type = $(".section03 select[name='asset_type']").val();
	}

	$('#chart_year').val(year);
	$('#chart_month').val(month);
	$('#asset_type').val(asset_type);

	GetStatisticsComCheckData(gubun, year, month, asset_type, v_com_seq);
}

function GetStatisticsComCheckData(gubun, year, month, asset_type, v_com_seq) {
	var vcs_status = $('#vcs_status').val();

	$.post(
		SITE_NAME + '/stat/vcs_stat_period_process.php',
		{
			gubun: gubun,
			year: year,
			month: month,
			asset_type: asset_type,
			vcs_status: vcs_status,
			v_com_seq: v_com_seq,
		},
		function (data) {
			if (data.status) {
				var arr_date_check_data = data.result.date_check_data;
				var arr_date_weak_data = data.result.date_weak_data;
				var arr_date_virus_data = data.result.date_virus_data;
				var arr_date_unit = data.result.date_unit;

				if (data.result.link != null) {
					var arr_date_check_link = data.result.link.date_check;
					var arr_date_weak_link = data.result.link.date_weak;
					var arr_date_virus_link = data.result.link.date_virus;
				}

				var arr_weak_data = data.result.weak_data;
				var arr_virus_data = data.result.virus_data;

				var arr_labels = [];

				if (gubun == 'DAY') {
					for (var i = 1; i <= daysInMonth(year, month); i++) arr_labels.push(i);
				} else if (gubun == 'MONTH') {
					arr_labels = Samples.utils.months();
				}

				var barChartData = {
					labels: arr_labels,
					datasets: [],
				};

				var color = Chart.helpers.color;

				var arr1 = {
					label: checktext[lang_code],
					backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
					borderColor: '#fecd6e',
					borderWidth: 1,
					data: arr_date_check_data,
					data_label: arr_date_unit,
					data_link: arr_date_check_link,
				};
				var arr2 = {
					label: weaknessdectiontext[lang_code],
					backgroundColor: color('#abdedf').alpha(0.5).rgbString(),
					borderColor: '#5cc3c2',
					borderWidth: 1,
					data: arr_date_weak_data,
					data_label: arr_date_unit,
					data_link: arr_date_weak_link,
				};
				var arr3 = {
					label: virusdectiontext[lang_code],
					backgroundColor: color('#ddaadc').alpha(0.5).rgbString(),
					borderColor: '#c699c6',
					borderWidth: 1,
					data: arr_date_virus_data,
					data_label: arr_date_unit,
					data_link: arr_date_virus_link,
				};

				barChartData['datasets'].push(arr1);
				barChartData['datasets'].push(arr2);
				barChartData['datasets'].push(arr3);

				var colors = null;
				var weakChartData = oChart.doughnutData(
					arr_weak_data.id,
					arr_weak_data.label,
					arr_weak_data.value,
					arr_weak_data.link,
					colors
				);
				var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				if (gubun == 'WEAK') {
					bindDoughnutChart('chartPcCheckWEAK', weakChartData);
				} else if (gubun == 'VIRUS') {
					bindDoughnutChart('chartPcCheckVIRUS', virusChartData);
				} else {
					//DAY,MONTH
					bindBarChart('chartPcCheck' + gubun, barChartData);
				}
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function CallStatisticsPcCheckData() {
	var onTab = $('#onTab').val();

	if (onTab == 'DAYLIST') {
		StatisticsPcCheckData('DAY');
	} else if (onTab == 'MONTHLIST') {
		StatisticsPcCheckData('MONTH');
	}

	StatisticsPcCheckData('ORG');
	StatisticsPcCheckData('DEPT');
	StatisticsPcCheckData('WEAK');
	StatisticsPcCheckData('VIRUS');
}

function ReportStatisticsPcCheckData(gubun) {
	var year = '';
	var month = '';
	var asset_type = '';

	if (gubun == 'DAY') {
		year = $('#daily_vcs_status_year').val();
		month = $('#daily_vcs_status_month').val();
	} else if (gubun == 'MONTH') {
		year = $('#monthly_vcs_status_year').val();
	} else if (gubun == 'DAY_DEVICE') {
		year = $('#daily_dvcs_status_year').val();
		month = $('#daily_dvcs_status_month').val();
	} else if (gubun == 'MONTH_DEVICE') {
		year = $('#monthly_dvcs_status_year').val();
	} else if (gubun == 'WEAK') {
		year = $('#weak_status_year').val();
		month = $('#weak_status_month').val();
	} else if (gubun == 'VIRUS') {
		year = $('#virus_status_year').val();
		month = $('#virus_status_month').val();
	}

	//alert(year+month);

	GetStatisticsPcCheckData(gubun, year, month, asset_type);
}

function StatisticsPcCheckData(gubun) {
	var year = '';
	var month = '';
	var asset_type = '';

	if (gubun == 'DAY') {
		year = $(".tab>li[name='DAYLIST'] div select[name='year']").val();
		month = $(".tab>li[name='DAYLIST'] div select[name='month']").val();
		asset_type = $(".tab>li[name='DAYLIST'] div select[name='asset_type']").val();
	} else if (gubun == 'MONTH') {
		year = $(".tab>li[name='MONTHLIST'] div select[name='year']").val();
		month = '';
		asset_type = $(".tab>li[name='MONTHLIST'] div select[name='asset_type']").val();
	} else if (gubun == 'ORG') {
		year = $(".section02 select[name='year']").val();
		month = $(".section02 select[name='month']").val();
		asset_type = $(".section02 select[name='asset_type']").val();
	} else if (gubun == 'DEPT') {
		year = $(".section03 select[name='year']").val();
		month = $(".section03 select[name='month']").val();
		asset_type = $(".section03 select[name='asset_type']").val();
	} else if (gubun == 'WEAK') {
		year = $(".section04 select[name='year']").val();
		month = $(".section04 select[name='month']").val();
		asset_type = $(".section04 select[name='asset_type']").val();
	} else if (gubun == 'VIRUS') {
		year = $(".section05 select[name='year']").val();
		month = $(".section05 select[name='month']").val();
		asset_type = $(".section05 select[name='asset_type']").val();
	}

	$('#chart_year').val(year);
	$('#chart_month').val(month);
	$('#asset_type').val(asset_type);

	GetStatisticsPcCheckData(gubun, year, month, asset_type);
}
function GetStatisticsPcCheckData(gubun, year, month, asset_type) {
	var vcs_status = $('#vcs_status').val();
	var org_check_result = $('#org_check_result').val();
	var dept_check_result = $('#dept_check_result').val();

	$.post(
		SITE_NAME + '/stat/vcs_stat_period_process.php',
		{
			gubun: gubun,
			year: year,
			month: month,
			asset_type: asset_type,
			vcs_status: vcs_status,
			org_check_result: org_check_result,
			dept_check_result: dept_check_result,
		},
		function (data) {
			if (data.status) {
				var colors = null;

				if (gubun == 'DAY' || gubun == 'MONTH') {
					var arr_date_check_data = data.result.date_check_data;
					var arr_date_weak_data = data.result.date_weak_data;
					var arr_date_virus_data = data.result.date_virus_data;
					var arr_date_unit = data.result.date_unit;

					if (data.result.link != null) {
						var arr_date_check_link = data.result.link.date_check;
						var arr_date_weak_link = data.result.link.date_weak;
						var arr_date_virus_link = data.result.link.date_virus;
					}

					var arr_labels = [];

					if (gubun == 'DAY') {
						for (var i = 1; i <= daysInMonth(year, month); i++) arr_labels.push(i);
					} else if (gubun == 'MONTH') {
						arr_labels = Samples.utils.months();
					}

					var barChartData = {
						labels: arr_labels,
						datasets: [],
					};

					var color = Chart.helpers.color;

					var arr1 = {
						label: checktext[lang_code],
						backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
						borderColor: '#fecd6e',
						borderWidth: 1,
						data: arr_date_check_data,
						data_label: arr_date_unit,
						data_link: arr_date_check_link,
					};
					var arr2 = {
						label: weaknessdectiontext[lang_code],
						backgroundColor: color('#abdedf').alpha(0.5).rgbString(),
						borderColor: '#5cc3c2',
						borderWidth: 1,
						data: arr_date_weak_data,
						data_label: arr_date_unit,
						data_link: arr_date_weak_link,
					};
					var arr3 = {
						label: virusdectiontext[lang_code],
						backgroundColor: color('#ddaadc').alpha(0.5).rgbString(),
						borderColor: '#c699c6',
						borderWidth: 1,
						data: arr_date_virus_data,
						data_label: arr_date_unit,
						data_link: arr_date_virus_link,
					};

					barChartData['datasets'].push(arr1);
					barChartData['datasets'].push(arr2);
					barChartData['datasets'].push(arr3);

						
					bindBarChart('chartPcCheck' + gubun, barChartData);

					//chart datatable
					var $_charttable = $('#chartPcCheck' + gubun + '_ChartDataTable');

					if ($_charttable.length > 0) {
						$_charttable.html(ChartdataToTable(barChartData));
					}
				} else if (gubun == 'DAY_DEVICE' || gubun == 'MONTH_DEVICE') {
					var arr_date_notebook_data = data.result.device_date_notebook_data;
					var arr_date_hdd_data = data.result.device_date_hdd_data;
					var arr_date_removable_data = data.result.device_date_removable_data;
					var arr_date_etc_data = data.result.device_date_etc_data;
					var arr_date_unit = data.result.device_date_unit;

					var arr_labels = [];

					if (gubun == 'DAY_DEVICE') {
						for (var i = 1; i <= daysInMonth(year, month); i++) arr_labels.push(i);
					} else if (gubun == 'MONTH_DEVICE') {
						arr_labels = Samples.utils.months();
					}

					var barChartData = {
						labels: arr_labels,
						datasets: [],
					};

					var color = Chart.helpers.color;

					var arr1 = {
						label: laptoptext[lang_code],
						backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
						borderColor: '#fecd6e',
						borderWidth: 1,
						data: arr_date_notebook_data,
						data_label: arr_date_unit,
					};
					var arr2 = {
						label: hddtext[lang_code],
						backgroundColor: color('#abdedf').alpha(0.5).rgbString(),
						borderColor: '#5cc3c2',
						borderWidth: 1,
						data: arr_date_hdd_data,
						data_label: arr_date_unit,
					};
					var arr3 = {
						label: removabletext[lang_code],
						backgroundColor: color('#ddaadc').alpha(0.5).rgbString(),
						borderColor: '#c699c6',
						borderWidth: 1,
						data: arr_date_removable_data,
						data_label: arr_date_unit,
					};
					var arr4 = {
						label: etctext[lang_code],
						backgroundColor: color('#ccddaa').alpha(0.5).rgbString(),
						borderColor: '#b2c195',
						borderWidth: 1,
						data: arr_date_etc_data,
						data_label: arr_date_unit,
					};

					barChartData['datasets'].push(arr1);
					barChartData['datasets'].push(arr2);
					barChartData['datasets'].push(arr3);
					barChartData['datasets'].push(arr4);

					bindBarChart('chartPcCheck' + gubun, barChartData);

					//chart datatable
					var $_charttable = $('#chartPcCheck' + gubun + '_ChartDataTable');

					if ($_charttable.length > 0) {
						$_charttable.html(ChartdataToTable(barChartData));
					}
				} else if (gubun == 'ORG') {
					var arr_org_data = data.result.org_data;
					var orgChartData = oChart.doughnutData(
						arr_org_data.id,
						arr_org_data.label,
						arr_org_data.value,
						arr_org_data.link,
						colors
					);
					bindDoughnutChart('chartPcCheckORG', orgChartData);
				} else if (gubun == 'DEPT') {
					var arr_dept_data = data.result.dept_data;
					var deptChartData = oChart.doughnutData(
						arr_dept_data.id,
						arr_dept_data.label,
						arr_dept_data.value,
						arr_dept_data.link,
						colors
					);
					
					
					bindDoughnutChart('chartPcCheckDEPT', deptChartData);
				} else if (gubun == 'WEAK') {
					var arr_weak_data = data.result.weak_data;
					var weakChartData = oChart.doughnutData(
						arr_weak_data.id,
						arr_weak_data.label,
						arr_weak_data.value,
						arr_weak_data.link,
						colors
					);
					bindDoughnutChart('chartPcCheckWEAK', weakChartData);
				} else if (gubun == 'VIRUS') {
					var arr_virus_data = data.result.virus_data;
					var virusChartData = oChart.doughnutData(
						arr_virus_data.id,
						arr_virus_data.label,
						arr_virus_data.value,
						arr_virus_data.link,
						colors
					);
					bindDoughnutChart('chartPcCheckVIRUS', virusChartData);
				}
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function StatisticsUserWVCheckData(v_user_seq) {
	var org_check_result = $('#org_check_result').val();
	var dept_check_result = $('#dept_check_result').val();

	$.post(
		SITE_NAME + '/stat/user_vcs_wv_stat_process.php',
		{
			v_user_seq: v_user_seq,
		},
		function (data) {
			if (data.status) {
				var arr_weak_data = data.result.weak_data;
				var arr_virus_data = data.result.virus_data;

				var colors = null;
				var weakChartData = oChart.doughnutData(
					arr_weak_data.id,
					arr_weak_data.label,
					arr_weak_data.value,
					arr_weak_data.link,
					colors
				);
				var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				bindDoughnutChart('chartUserCheckWEAK', weakChartData);
				bindDoughnutChart('chartUserCheckVIRUS', virusChartData);
			} else {
				alert(data.msg);
			} //if(data.status){

			//Chart SetSize
			ChartResizeUserWVCheck();
		},
		'json'
	);
}

function StatisticsComWVCheckData(v_com_seq) {
	var org_check_result = $('#org_check_result').val();
	var dept_check_result = $('#dept_check_result').val();

	$.post(
		SITE_NAME + '/stat/com_vcs_wv_stat_process.php',
		{
			v_com_seq: v_com_seq,
		},
		function (data) {
			if (data.status) {
				var arr_weak_data = data.result.weak_data;
				var arr_virus_data = data.result.virus_data;

				var colors = null;
				var weakChartData = oChart.doughnutData(
					arr_weak_data.id,
					arr_weak_data.label,
					arr_weak_data.value,
					arr_weak_data.link,
					colors
				);
				var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				bindDoughnutChart('chartUserCheckWEAK', weakChartData);
				bindDoughnutChart('chartUserCheckVIRUS', virusChartData);
			} else {
				alert(data.msg);
			} //if(data.status){

			//Chart SetSize
			ChartResizeUserWVCheck();
		},
		'json'
	);
}

function ChartResizeUserWVCheck() {
	var w1 = $('#mark .section01 div').width() - $('#mark .ch_legend').width() - 5;
	var w2 = $('#mark .wrapper2 div').width() - $('#mark .section01').outerWidth() - 20;

	$('#mark .ch').width(w1).height(w1);
	$('#mark .section01_data').outerWidth(w2);
	$('#mark .section02_data').outerWidth(w2);
}

function StatisticsUserVcsSummaryData(v_user_seq) {
	var org_check_result = $('#org_check_result').val();
	var dept_check_result = $('#dept_check_result').val();

	$.post(
		SITE_NAME + '/stat/user_vcs_wv_stat_process.php',
		{
			v_user_seq: v_user_seq,
		},
		function (data) {
			if (data.status) {
				var arr_weak_data = data.result.weak_data;
				var arr_virus_data = data.result.virus_data;

				var colors = null;
				var weakChartData = oChart.doughnutData(
					arr_weak_data.id,
					arr_weak_data.label,
					arr_weak_data.value,
					arr_weak_data.link,
					colors
				);
				var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				bindDoughnutChart('chartUserCheckWEAK', weakChartData);
				bindDoughnutChart('chartUserCheckVIRUS', virusChartData);
			} else {
				alert(data.msg);
			} //if(data.status){

			//Chart SetSize
			ChartResizeUserWVCheck();
		},
		'json'
	);
}

function StatisticsStorageCheckData(gubun) {
	var year = '';
	var month = '';

	if (gubun == 'ALL' || gubun == 'DAY') {
		year = $(".tab>li[name='DAYLIST'] div select[name='year']").val();
		month = $(".tab>li[name='DAYLIST'] div select[name='month']").val();
	} else if (gubun == 'MONTH') {
		year = $(".tab>li[name='MONTHLIST'] div select[name='year']").val();
		month = '';
	} else if (gubun == 'ORG') {
		year = $(".section02 select[name='year']").val();
		month = $(".section02 select[name='month']").val();
	} else if (gubun == 'DEPT') {
		year = $(".section03 select[name='year']").val();
		month = $(".section03 select[name='month']").val();
	} else if (gubun == 'TYPE') {
		year = $(".section04 select[name='year']").val();
		month = $(".section04 select[name='month']").val();
	} else if (gubun == 'VIRUS') {
		year = $(".section05 select[name='year']").val();
		month = $(".section05 select[name='month']").val();
	}

	$('#chart_year').val(year);
	$('#chart_month').val(month);

	GetStatisticsStorageCheckData(gubun, year, month);
}
function GetStatisticsStorageCheckData(gubun, year, month) {
	var org_check_result = $('#org_check_result').val();
	var dept_check_result = $('#dept_check_result').val();
	var type_check_result = $('#type_check_result').val();

	$.post(
		SITE_NAME + '/stat/vcs_storage_stat_process.php',
		{
			gubun: gubun,
			year: year,
			month: month,
			org_check_result: org_check_result,
			dept_check_result: dept_check_result,
			type_check_result: type_check_result,
		},
		function (data) {
			if (data.status) {
				var arr_date_check_data = data.result.date_check_data;
				var arr_date_virus_data = data.result.date_virus_data;
				var arr_date_unit = data.result.date_unit;

				if (data.result.link != null) {
					var arr_date_check_link = data.result.link.date_check;
					var arr_date_virus_link = data.result.link.date_virus;
				}

				var arr_org_data = data.result.org_data;
				var arr_dept_data = data.result.dept_data;
				var arr_type_data = data.result.type_data;
				var arr_virus_data = data.result.virus_data;

				var arr_labels = [];

				if (gubun == 'DAY' || gubun == 'ALL') {
					for (var i = 1; i <= daysInMonth(year, month); i++) arr_labels.push(i);
				} else if (gubun == 'MONTH') {
					arr_labels = Samples.utils.months();
				}

				var barChartData = {
					labels: arr_labels,
					datasets: [],
				};

				var color = Chart.helpers.color;

				var arr1 = {
					label: checktext[lang_code],
					backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
					borderColor: '#fecd6e',
					borderWidth: 1,
					data: arr_date_check_data,
					data_label: arr_date_unit,
					data_link: arr_date_check_link,
				};
				var arr2 = {
					label: virusdectiontext[lang_code],
					backgroundColor: color('#ddaadc').alpha(0.5).rgbString(),
					borderColor: '#c699c6',
					borderWidth: 1,
					data: arr_date_virus_data,
					data_label: arr_date_unit,
					data_link: arr_date_virus_link,
				};

				barChartData['datasets'].push(arr1);
				barChartData['datasets'].push(arr2);

				var colors = null;
				var orgChartData = oChart.doughnutData(
					arr_org_data.id,
					arr_org_data.label,
					arr_org_data.value,
					arr_org_data.link,
					colors
				);
				var deptChartData = oChart.doughnutData(
					arr_dept_data.id,
					arr_dept_data.label,
					arr_dept_data.value,
					arr_dept_data.link,
					colors
				);
				var typeChartData = oChart.doughnutData(
					arr_type_data.id,
					arr_type_data.label,
					arr_type_data.value,
					arr_type_data.link,
					colors
				);
				var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);

				if (gubun == 'ALL') {
					bindBarChart('chartStorageCheckDAY', barChartData);
					bindDoughnutChart('chartStorageCheckORG', orgChartData);
					bindDoughnutChart('chartStorageCheckDEPT', deptChartData);
					bindDoughnutChart('chartStorageCheckTYPE', typeChartData);
					bindDoughnutChart('chartStorageCheckVIRUS', virusChartData);
				} else if (gubun == 'ORG') {
					bindDoughnutChart('chartStorageCheckORG', orgChartData);
				} else if (gubun == 'DEPT') {
					bindDoughnutChart('chartStorageCheckDEPT', deptChartData);
				} else if (gubun == 'TYPE') {
					bindDoughnutChart('chartStorageCheckTYPE', typeChartData);
				} else if (gubun == 'VIRUS') {
					bindDoughnutChart('chartStorageCheckVIRUS', virusChartData);
				} else {
					bindBarChart('chartStorageCheck' + gubun, barChartData);
				}
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function StatisticsUserPcData() {
	$.post(
		SITE_NAME + '/stat/user_pc_stat_process.php',
		function (data) {
			if (data.status) {
				var arr_os_data = data.result.os_data;
				var arr_maker_data = data.result.maker_data;

				var colors = null;
				var osChartData = oChart.doughnutData(
					arr_os_data.id,
					arr_os_data.label,
					arr_os_data.value,
					arr_os_data.link,
					colors
				);
				var makerChartData = oChart.doughnutData(
					arr_maker_data.id,
					arr_maker_data.label,
					arr_maker_data.value,
					arr_maker_data.link,
					colors
				);

				bindDoughnutChart('StatisticsUserPcOS', osChartData);
				bindDoughnutChart('StatisticsUserPcMAKER', makerChartData);
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

var ChartdataToTable = function (dataset) {
	var html = "<table class='list' style='margin-top:30px'>";
	html += '<tr> <th style="min-width:60px;">' + gubuntext[lang_code] + '</th>';

	var columnCount = 0;

	jQuery.each(dataset.labels, function (idx, item) {
		html += '<th>' + item + '</th>';
		columnCount += 1;
	});

	html += '<th style="min-width:40px;">Total</th>';
	
	var total_row = [];
	jQuery.each(dataset.datasets, function (idx, item) {
		html += '<tr><td>' + item.label + '</td>';
		
		var row_sum=0;

		if(idx==0){
			for (i = 0; i <= columnCount; i++) total_row.push(0);
		}
		
		for (i = 0; i < columnCount; i++) {
			
			var count = dataset.datasets[idx].data[i];
			html +=
				'<td>' +
				(count === '0' ? '-' : addCommas(count)) +
				'</td>';

			if($.isNumeric(count)){
				row_sum += Number(count);
				total_row[i] +=  Number(count);
			}

		}

		total_row[i] +=  row_sum;
		html += '<td>'+addCommas(row_sum)+'</td>';
		html += '</tr>'; //'<td></td>';
	});

	if(dataset.datasets.length > 1){
		html += '<tr style="background-color:#e7e7e7"><td>'+sumtext[lang_code]+'</td>';
		for(j = 0 ; j <= columnCount ; j++){
			html += '<td>'+total_row[j]+'</td>';
		}
		html +='</tr>';
	}

	html += '</tr>';
	html += '</table>';

	return html;
};

function setAssetGubun(obj) {
	var vcs_type = $(obj).val();

	$('#storage_device_type').show();
	$('#asset_type option').toggleOption('show');

	if (vcs_type == '') {
		//$("#asset_type").val('');
	} else {
		if (vcs_type == 'DOWNLOAD') {
			$('#asset_type').val('NOTEBOOK');
			$("#asset_type option[value='RemovableDevice']").toggleOption('hide');
			$('#storage_device_type').val('');
			$('#storage_device_type').hide();
		} else {
			$('#asset_type').val('RemovableDevice');
			$("#asset_type option[value='NOTEBOOK']").toggleOption('hide');
		}
	}
}

function setStoargeDeviceType(obj) {
	var asset_type = $(obj).val();

	if (asset_type == 'NOTEBOOK') {
		$('#storage_device_type').val('');
		$('#storage_device_type').hide();
	} else {
		$('#storage_device_type').show();
	}
}

function popUserVcsLog(user_seq, user_name, notebook_key, v_asset_type) {
	$.post(
		SITE_NAME + '/result/pop_user_check_log.php',
		{
			v_user_seq: user_seq,
			v_user_name: user_name,
			v_asset_type: v_asset_type,
			v_notebook_key: notebook_key,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function popUserVcsView(v_wvcs_seq) {
	$.post(
		SITE_NAME + '/result/pop_user_check_view.php',
		{
			v_wvcs_seq: v_wvcs_seq,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function popScan(checkinout_flag) {
	$.post(
		SITE_NAME + '/result/pop_scan.php?enc=' + ParamEnCoding('checkinout_flag=' + checkinout_flag),
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();

			$('#barcode').focus();
		},
		'text'
	);

	return false;
}

function BarcodeModeToggle() {
	$("#barcode_mode div[name='btn_mode']").toggle();

	var cur_mode = $('#btnchangetocheckin').is(':visible') ? 'search' : 'checkin';

	if (cur_mode == 'search') {
		$('#checkinout_flag').val('N');
		$('#barcode_search_txt').show();
		$('#barcode_checkin_txt').hide();
	} else {
		$('#checkinout_flag').val('Y');
		$('#barcode_search_txt').hide();
		$('#barcode_checkin_txt').show();
	}

	$("div[class='scan_box'] span[name='scan_box_title']")
		.removeClass()
		.addClass('label1 ' + cur_mode);

	//Reset!!
	$('#str_barcode').text('');
	LoadScanVcsInfo('', '');
	LoadDiskInfo('');
	LoadVaccineInfo('');
}

function popUserVcsWeakVirus(v_user_seq, src) {
	$.post(
		SITE_NAME + '/user/pop_user_vcs_wv.php',
		{
			v_user_seq: v_user_seq,
			src: src,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function popComVcsWeakVirus(v_com_seq, src) {
	$.post(
		SITE_NAME + '/user/pop_com_vcs_wv.php',
		{
			v_com_seq: v_com_seq,
			src: src,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function popUserVcsDevice(v_user_seq, device_gubun, src) {
	$.post(
		SITE_NAME + '/user/pop_user_vcs_device.php',
		{
			v_user_seq: v_user_seq,
			device_gubun: device_gubun,
			src: src,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function popComVcsDevice(v_com_seq, device_gubun, src) {
	$.post(
		SITE_NAME + '/user/pop_com_vcs_device.php',
		{
			v_com_seq: v_com_seq,
			device_gubun: device_gubun,
			src: src,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function popUserVcsSummary(v_user_seq) {
	$.post(
		SITE_NAME + '/user/pop_user_vcs_summary.php',
		{
			v_user_seq: v_user_seq,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function popCompanyVcsSummary(v_com_seq) {
	$.post(
		SITE_NAME + '/user/pop_com_vcs_summary.php',
		{
			v_com_seq: v_com_seq,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function ClosepopContent() {
	$('#popContent').html('');
	$('#popContent').hide();

	EnableScroll(true);
}

function ClosepopScanContent() {
	var elem = window.top.document;
	closeFullscreen(elem);

	$('#popContent').html('');
	$('#popContent').hide();

	EnableScroll(true);
}

function ReportPreview() {
	var frm = document.frmPrint;

	frm.action = SITE_NAME + '/stat/report.php';
	frm.target = '';
	frm.method = 'post';
	frm.submit();
}
function CallReportPrint() {
	var popup = window.open(
		'about:blank',
		'ReportPrint',
		'width=1100,height=700,resizable=no, scrollbars=yes, status=no;'
	);
	var frm = document.frmPrint;
	frm.action = SITE_NAME + '/stat/report_print.php';
	frm.target = 'ReportPrint';
	frm.method = 'post';
	frm.submit();
}

function EnableScroll(_enablescroll) {
	if (_enablescroll) {
		$('body, html').css('overflow-y', 'auto');
	} else {
		$('body, html').css('overflow', 'hidden');
	}
}

function openFullscreen(elem) {
	if (elem.requestFullscreen) {
		elem.requestFullscreen();
	} else if (elem.mozRequestFullScreen) {
		/* Firefox */
		elem.mozRequestFullScreen();
	} else if (elem.webkitRequestFullscreen) {
		/* Chrome, Safari & Opera */
		elem.webkitRequestFullscreen();
	} else if (elem.msRequestFullscreen) {
		/* IE/Edge */
		elem.msRequestFullscreen();
	}
}

function closeFullscreen(elem) {
	if (elem.exitFullscreen) {
		elem.exitFullscreen();
	} else if (elem.mozCancelFullScreen) {
		/* Firefox */
		elem.mozCancelFullScreen();
	} else if (elem.webkitExitFullscreen) {
		/* Chrome, Safari and Opera */
		elem.webkitExitFullscreen();
	} else if (elem.msExitFullscreen) {
		/* IE/Edge */
		elem.msExitFullscreen();
	}
}

function isFullScreen(elem) {
	var _isFullScreen =
		(elem.fullScreenElement && elem.fullScreenElement !== null) ||
		elem.mozFullScreen ||
		elem.webkitIsFullScreen ||
		elem.msFullscreenElement;

	return _isFullScreen ? true : false;
}

function isFullscreenAvailable(elem) {
	var _isFullscreenAvailable =
		(elem.fullscreenEnabled && elem.fullscreenEnabled !== null) ||
		elem.webkitFullscreenEnabled ||
		elem.mozFullScreenEnabled ||
		elem.msFullscreenEnabled;

	return _isFullscreenAvailable;
}

function popScanToggleFullScreen() {
	var elem = window.top.document;

	var _isFullScreen = isFullScreen(elem);

	if (_isFullScreen) {
		closeFullscreen(elem);
	} else {
		var _isFullscreenAvailable = isFullscreenAvailable(elem);

		if (!_isFullscreenAvailable) {
			alert("FullScreen doesn't support");
			return;
		}

		openFullscreen(elem.documentElement);
	}

	fullScreenButtonToggle(!_isFullScreen);
}

function fullScreenButtonToggle(_isFullScreen) {
	if (typeof _isFullScreen == 'undefined') {
		var elem = window.top.document;

		_isFullScreen = isFullScreen(elem);
	}

	if (_isFullScreen) {
		$("#fullscreenbutton span[name='exit']").show();
	} else {
		$("#fullscreenbutton span[name='exit']").hide();
	}
}

function popViewBarcodeLog() {
	var barcode = $('#str_barcode').text();
	var url =
		SITE_NAME + '/result/pop_checkin_scan_log.php?enc=' + ParamEnCoding('barcode=' + barcode);

	//팝업창에 focus 줄 수 있도록 focusout 이벤트 삭제
	$('#barcode').unbind('focusout');

	popupOpen(url, 'BarcodeScanLog', 500, 600);
}

function popVcsScanResultPrint(v_wvcs_seq) {
	var url =
		SITE_NAME +
		'/result/pop_scan_result_print.php?enc=' +
		ParamEnCoding('v_wvcs_seq=' + v_wvcs_seq);

	popupOpen(url, 'VCSScanResult', 380, 600);
}

function printArea(area_id) {
	var initBody = document.body.innerHTML;

	window.onbeforeprint = function () {
		document.body.innerHTML = document.getElementById(area_id).innerHTML;
	};
	window.onafterprint = function () {
		document.body.innerHTML = initBody;
	};
	window.print();
}

function generateBarcode(oBarcode, display_value) {
	var value = oBarcode.value;
	var btype = oBarcode.btype;
	var renderer = oBarcode.renderer;

	var quietZone = false;

	var settings = {
		output: renderer,
		bgColor: oBarcode.settings.bgcolor,
		color: oBarcode.settings.color,
		barWidth: oBarcode.settings.barWidth,
		barHeight: oBarcode.settings.barHeight,
		moduleSize: 0,
		posX: 0,
		posY: 0,
		showHRI: display_value,
		addQuietZone: 1,
	};

	$('#' + oBarcode.id).barcode(value, btype, settings);
}

function LoadMenuFolder(name) {
	var mCookie = getCookie(name);

	if (mCookie) {
		if (mCookie == 'show') {
			$('#menu_folder').show();
		} else {
			$('#menu_folder').hide();
			$("img[name='arrow_" + name + "']").toggle();
		}
	}
}

function SetMenuFolder(name, value) {
	setCookie(name, value, 1);
	$("img[name='arrow_" + name + "']").toggle();
	$('#' + name).toggle();
}

function ExcelDown(url, flag) {

	var proc_name = getProcName();

	if (flag != '') {
		$('#viewLoading').css('position', 'absolute');
		$('#viewLoading').css('margin', '2px');
		$('#viewLoading').css('left', $('#' + flag).offset().left);
		$('#viewLoading').css('top', $('#' + flag).offset().top);
		$('#viewLoading').css('width', $('#' + flag).css('width'));
		$('#viewLoading').css('height', $('#' + flag).css('height'));
		$('#viewLoading').fadeIn(500);
	}
	//document.location = url;
	sendPostForm(url+"&proc_name="+proc_name);
	if (flag != '') {
		$('#viewLoading').fadeOut(500);
	}
}

function closeWin(winName, expiredays) {
	setCookie(winName, 'done', expiredays);
	$('#' + winName).hide();
}

function openWin(winName) {
	var blnCookie = getCookie(winName);
	if (!blnCookie) {
		$('#' + winName).show();
	}
}

function getCookie(name) {
	var nameOfCookie = name + '=';
	var x = 0;
	while (x <= document.cookie.length) {
		var y = x + nameOfCookie.length;
		if (document.cookie.substring(x, y) == nameOfCookie) {
			if ((endOfCookie = document.cookie.indexOf(';', y)) == -1)
				endOfCookie = document.cookie.length;
			return unescape(document.cookie.substring(y, endOfCookie));
		}
		x = document.cookie.indexOf(' ', x) + 1;
		if (x == 0) break;
	}
	return '';
}

function setCookie(name, value, expiredays) {
	var todayDate = new Date();
	todayDate.setDate(todayDate.getDate() + expiredays);
	document.cookie =
		name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + ';';
}
var popup = null;
function popupOpen(url, name, width, height) {
	if (popup != null && !popup.closed) {
		//팝업이 떠 있는 경우.
		popup.close();
	}

	popup = window.open(
		url,
		name,
		'width=' + width + ',height=' + height + ',resizable=no, scrollbars=no, status=no;'
	);
	popup.focus();
}

function popUserInFileList(v_wvcs_seq, src) {
	$.post(
		SITE_NAME + '/result/pop_user_file_list.php',
		{
			v_wvcs_seq: v_wvcs_seq,
			src: src,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}

function CheckCenterInfoSubmit(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#center_code'), qcentercodeinput[lang_code])) return false;
		if (!CheckBlankData($('#center_name'), qcenternameinput[lang_code])) return false;
	}

	return true;
}
function checkIpVaild(ip_addr) {
	var filter =
		/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/;

	if (filter.test(ip_addr) == true) {
		return true;
	} else {
		alert(qunvalidip[lang_code]);
	
		return false;
	}
}
function CheckAccessIPInfoSubmit(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	} else {
		var ip_addr = $('#ip_addr').val();

		if (!checkIpVaild(ip_addr)) {
			$('#ip_addr').focus();
			return false;
		}

		if (!CheckBlankData($('#ip_addr'), 'input')) return false;
		if (!CheckBlankData($('#allow_id'), 'select')) return false;
	}

	return true;
}
function AccessIPSubmit(proc) {
	var proc_name = getProcName();

	$('#proc').val(proc);
	$('#proc_name').val(proc_name);


	if (!CheckAccessIPInfoSubmit(proc)) return;

	$.post(
		SITE_NAME + '/manage/access_ip_reg_process.php',
		$('#frmAccessIP').serialize(),
		function (data) {
			alert(data.msg);

			if (proc == 'CREATE') {
				location.href =
					// './access_ip_reg.php?enc=' + ParamEnCoding('login_ip_mgt_seq=' + data.result);
					'./access_ip_config.php';
			} else if (proc == 'DELETE') {
				location.href = './access_ip_config.php';
			}
		},
		'json'
	);
}

function CheckAccessIPInfoSubmit2(proc) {
	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	} else {
		var ip_addr_values = $('input[name="ip_addr[]"]').map(function () {
			return $(this).val();
		}).get();

		for (var i = 0; i < ip_addr_values.length; i++) {
			var ip_addr = ip_addr_values[i];

			if (!checkIpVaild(ip_addr)) {
				$('input[name="ip_addr[]"]').eq(i).focus();
				return false;
			}

			if (!CheckBlankData($('input[name="ip_addr[]"]').eq(i), 'input')) return false;
			if (!CheckBlankData($('input[name="allow_id[]"]').eq(i), 'input')) return false;
		}

		var allowIds = $('select[name="allow_id[]"]');
		for (var j = 0; j < allowIds.length; j++) {
			if ($(allowIds[j]).val() === "") {
				alert(selectAllowID[lang_code]);
				return false;
			}
		}

	}

	return true;
}



function AccessIPSubmit2(proc) {	


	var proc_name = getProcName();

	$('#proc').val(proc);
	$('#proc_name').val(proc_name);

	if (!CheckAccessIPInfoSubmit2(proc)) return;

	$.post(
		SITE_NAME + '/manage/access_ip_reg_process_multiple.php',

		$('#frmAccessIP').serialize(),
		function (data) {
			alert(data.msg);

			if (proc == 'CREATE') {
				location.href =

					'./access_ip_config.php';
			} else if (proc == 'DELETE') {
				location.href = './access_ip_config.php';
			}
		},
		'json'
	);
}
function AccessIPDeleteSubmit(login_ip_mgt_seq) {
	var proc = 'DELETE';

	var proc_name = getProcName();

	if (!CheckCenterInfoSubmit(proc)) return false;

	$.post(
		'./access_ip_reg_process.php',
		// SITE_NAME+'/manage/access_ip_reg_process.php',
		{
			proc: proc,
			proc_name: proc_name,
			login_ip_mgt_seq: login_ip_mgt_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}

/*작업로그 - 작업명 가져오기*/
function getProcName() {
	var proc_name = '';
	var obj = null;
	var e = window.event.target || window.event.srcElement;

	var page_title;

	if ($('#pop_page_title2').is(':visible')==true){
		page_title = $('#pop_page_title2').text();
	}else if ($('#pop_page_title').is(':visible')==true){
		page_title = $('#pop_page_title').text();
	} else {
		page_title = $('#page_title').text();
	}

	var event_name = $.trim(e.title);
	
	if(event_name==""){

		if (e.nodeName == 'A') {
			proc_name = page_title + ' - ' + $.trim(e.text);
		} else if (e.nodeName == 'SPAN' || e.nodeName == 'BUTTON') {
			proc_name = page_title + ' - ' + $.trim(e.innerText);
		} else if(e.nodeName=="INPUT" && $(e).attr("type").toUpperCase()=="SUBMIT"){
			proc_name = page_title + ' - ' + $.trim(e.value);
		} else if (e.nodeName == 'IMG'	 || e.nodeName=='LI') {
			proc_name = page_title + ' - ' + $.trim(e.alt);

			/*}else if(typeof e.nodeName =='undefined'){
				proc_name = $("#proc_name").val();
			*/
		} else {
			proc_name = page_title;
		}

	}else{
		proc_name = page_title + ' - ' + event_name;
	}

	return proc_name;
}
//레이어 보이기 안보이기
function viewlayer(opt, layer) {
	if ($('#' + layer).length == 0) return;

	var thisStyle = eval('document.all.' + layer + '.style');

	if (opt) {
		thisStyle.display = 'block';
	} else {
		thisStyle.display = 'none';
	}
}
function resetLoginAttempt(admin_seq) {
	var proc_name = getProcName();

	$.post(
		'./login_attempt_reset.php',
		//SITE_NAME + '/manage/login_attempt_reset.php',
		{
			admin_seq: admin_seq,
			proc_name: proc_name,
		},
		function (data) {
			alert(data.msg);
			location.reload(true);
		},
		'json'
	);
}

function FileSignatureSubmit(proc) {
	var proc_name = getProcName(); //shu function ni topa olmadim

	$('#proc').val(proc);
	$('#proc_name').val(proc_name);

	$.post(
		'./file_signature_reg_process.php',
		$('#fromFileSig').serialize(),
		function (data) {
			alert(data.msg);

			if (proc == 'CREATE') {
				// location.href =
				// 	'./file_signature_reg.php?enc=' + ParamEnCoding('sign_id_seq=' + data.result);
				sendPostForm(
					'./file_signature_reg.php?enc=' + ParamEnCoding('sign_id_seq=' + data.result));
			} else if (proc == 'DELETE') {
				location.href = './file_signature.php';
			}
		},
		'json'
	);
}
function FileSignatureDelete(sign_id_seq) {
	var proc = 'DELETE';
	var proc_name = getProcName();

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		} else {
			
			// if (!CheckCenterInfoSubmit(proc)) return false;

			$.post(
				'./file_signature_reg_process.php',
				{
					proc: proc,
					proc_name: proc_name,
					sign_id_seq: sign_id_seq,
				},
				function (data) {
					alert(data.msg);
					if (data.status) {
						location.reload();
					}
				},
				'json'
			);
		}
	}
}

function SignatureMappingSubmit(proc) {
	var proc_name = getProcName();

	$('#proc').val(proc);
	$('#proc_name').val(proc_name);

	$.post(
		'./signature_mapping_reg_process.php',
		$('#fromSigMap').serialize(),
		function (data) {
			alert(data.msg);

			if (proc == 'CREATE') {
				// location.href =
				// 	'./signature_mapping_reg.php?enc=' + ParamEnCoding('sign_map_seq=' + data.result);
				sendPostForm(
					'./signature_mapping_reg.php?enc=' + ParamEnCoding('sign_map_seq=' + data.result));
			} else if (proc == 'DELETE') {
				location.href = './signature_mapping.php';
			}
		},
		'json'
	);
}
function SignatureMappingDelete(sign_map_seq) {
	var proc = 'DELETE';
	var proc_name = getProcName();

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		} else {
			//if (!CheckCenterInfoSubmit(proc)) return false;

			$.post(
				'./signature_mapping_reg_process.php',
				{
					proc: proc,
					proc_name: proc_name,
					sign_map_seq: sign_map_seq,
				},
				function (data) {
					alert(data.msg);
					if (data.status) {
						location.reload();
					}
				},
				'json'
			);
		}
	}
}
function UsbListSubmit(proc) {
	console.log(proc);
	var proc_name = getProcName();

	$('#proc').val(proc);
	$('#proc_name').val(proc_name);

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		}
	}

	$.post(
		'./usb_list_reg_process.php',
		$('#fromUsbList').serialize(),
		function (data) {
			alert(data.msg);

			if (proc == 'CREATE') {
				
				sendPostForm(
					'./usb_reg.php?enc=' + ParamEnCoding('usb_seq=' + data.result));
			} else if (proc == 'DELETE') {
				location.href = './usb_list.php';
			}
		},
		'json'
	);
}
function UsbListDelete(usb_seq) {
	
	var proc = 'DELETE';
	var proc_name = getProcName();

	if (proc == 'DELETE') {
		if (!confirm(qdeleteconfirm[lang_code])) {
			return false;
		} else {

			$.post(
				'./usb_list_reg_process.php',
				{
					proc: proc,
					proc_name: proc_name,
					usb_seq: usb_seq,
				},
				function (data) {
					alert(data.msg);
					if (data.status) {
						location.reload();
					}
				},
				'json'
			);
		}
	}
}

// excel

function getHTMLSplit(record_count, url, excel_name, button) {

	if(record_count > _excel_download_max_size){
		var mag =  limtmaxexceldownload[lang_code].replace("{#}",_excel_download_max_size);
		alert(mag);
		return false;
	}

	var proc_name = getProcName();

	//querystring post로 전송하기
	var _url = "";
	var _querystring = "";


	if(url.indexOf('?') > -1){
		_url = url.split('?')[0];
		_querystring = url.split('?')[1];
	}else{
		_url  = url;
	}

	var params1 = [];
	var params2 = [];

	if(_querystring != ""){
		params1 = getUrlParams(_querystring);
	}

	if(params1['enc'] != 'undefined'){
		var params_dec = ParamDeCoding( params1['enc'] );
		params2 = getUrlParams(params_dec);
		delete params1.enc;
	}


	var params = $.extend(params1,params2);

	params['record_count'] = record_count;
	params['proc_name'] = proc_name;

	//console.log(params);

	//button class
	$(button).addClass('loading');
	var originalButtonText = $(button).text();
	$(button).text('Downloading...');

	$.ajax({
		type: 'POST',
		url: _url,
		dataType: 'JSON',
		data: params,
		success: function (response) {
			
			if(response==null){
				alert(qnodata[lang_code]);
				$(button).text(originalButtonText);
				return false;
			}
			//console.log('Response received:', response);
			try {
				exportHTMLSplit(response, excel_name);
			} catch (error) {
				//console.error('Error exporting HTML: ', error);
			} finally {
				// Re-enable the button
				$(button).removeClass('loading');
				$(button).text(originalButtonText); // Reset the button text
			}
		},
		error: function (xhr, status, error) {
			//console.error('AJAX request error:', error);
			//console.log('Response Text:', xhr.responseText); // Log the response for debugging.

			// Re-enable the button in case of an error
			$(button).removeClass('loading');
			$(button).text(originalButtonText);
		},
	});
}

function exportHTMLSplit(response, excel_name) {
	var random = new Date().dateformat('yyyymmddhhmmss');

	$(response).each(function (index) {
		var excelContent = response[index];


		var excelFileData = "";
		excelFileData += "<html xmlns:o='urn:schemas-microsoft-com:office:office' ";
		excelFileData += "xmlns:x='urn:schemas-microsoft-com:office:excel' ";
		excelFileData += "xmlns='http://www.w3.org/TR/REC-html40'>";

		excelFileData += '<head>';
		excelFileData += '<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
		excelFileData += '<!--[if gte mso 9]>';
		excelFileData += '<xml>';
		excelFileData += '<x:ExcelWorkbook>';
		excelFileData += '<x:ExcelWorksheets>';
		excelFileData += '<x:ExcelWorksheet>';
		excelFileData += '<x:Name>';
		excelFileData += excel_name;
		excelFileData += '</x:Name>';
		excelFileData += '<x:WorksheetOptions>';
		excelFileData += '<x:DisplayGridlines/>';
		excelFileData += '</x:WorksheetOptions>';
		excelFileData += '</x:ExcelWorksheet>';
		excelFileData += '</x:ExcelWorksheets>';
		excelFileData += '</x:ExcelWorkbook>';
		excelFileData += '</xml>';
		excelFileData += '<![endif]-->';
		excelFileData += '<style>td {mso-number-format:"\@";}</style>';
		excelFileData += '</head>';
		excelFileData += '<body>';
		excelFileData += excelContent;
		excelFileData += '</body>';
		excelFileData += '</html>';


		var sourceHTML = excelFileData + response[index];
		var source = 'data:application/vnd.ms-excel; charset=utf-8,' + encodeURIComponent(sourceHTML);
		var fileDownload = document.createElement('a');
		document.body.appendChild(fileDownload);
		fileDownload.href = source;


		fileDownload.download = excel_name + '_' + random + '.xls';
		fileDownload.click();
		document.body.removeChild(fileDownload);
	});
}

// $(document).ready(function () {
// 	$('.viewlayer').parent().css('position', 'relative');
// });

$(document).ready(function () {
	$('#toggleButton').click(function () {
		$('form.hidden').toggle(); // Toggle the visibility of the table
	});
});

$(document).ready(function () {
	$('.toggle-td').click(function () {
		var tr = $(this).closest('.toggle-row').next();
		tr.toggleClass('hidden');
	});

	$('.save-button').click(function () {
		var inputField = $(this).parent().find('input');
		var inputValue = inputField.val();

		console.log('Input value:', inputValue);
		// Hide the input field and button
		$(this).parent().parent().addClass('hidden');
	});
});

/*메모전송*/
function appendRow_Memo(seq, seq_val) {
	var target = window.event.target;
	var row = $(target).closest('tr');
	var column_count = $(row).find('td').length;
	var memo = $(row).find('.viewlayer').text();

	if ($('#memo_row_' + seq).length == 0) {
		var appendTag = '';
		appendTag +=
			"<tr id='memo_row_" + seq + "'><td colspan='" + column_count + "' style='text-align:left'>";
		appendTag += "<input type='hidden'  name='" + seq_val + "'  value='" + seq + "'>";
		appendTag +=
			"<input type='text' class='frm_input'  style='width:85%'  name='memo' maxlength='100' value='" +
			memo +
			"'>";
		appendTag += "<button type='button' class='btn' onclick='sendMemo()' >"+memosavetext[lang_code]+"</button>";
		appendTag += '</td></tr>';

		$(row).after(appendTag);
	} else {
		$(row).next().remove();
	}
}

function sendMemo() {
	var target = window.event.target;
	var row = $(target).closest('tr');

	var proc_name = getProcName();

	$('#frmMemo').html('');
	$('#frmMemo').append(row);
	$('#frmMemo').append("<input type='hidden' name='proc_name' value='"+proc_name+"'>");
	$('#frmMemo').append("<input type='hidden' name='proc' value='UPDATE'>");

	VisitorProcessSubmit();
}
/*자산반입 메모전송*/
function appendRow_ImportGoodsMemo(seq) {
	var target = window.event.target;
	var row = $(target).closest('tr');
	var column_count = $(row).find('td').length;
	var memo = $(row).find('.viewlayer').text();

	if ($('#memo_row_' + seq).length == 0) {
		var appendTag = '';
		appendTag +=
			"<tr id='memo_row_" + seq + "'><td colspan='" + column_count + "' style='text-align:left'>";
		appendTag += "<input type='hidden'  name='v_user_list_goods_seq'  value='" + seq + "'>";
		appendTag +=
			"<input type='text' class='frm_input'  style='width:85%'  name='memo' maxlength='100' value='" +
			memo +
			"'>";
		appendTag += "<button type='button' class='btn' onclick='sendImportGoodsMemo()'>"+memosavetext[lang_code]+"</botton>";
		appendTag += '</td></tr>';

		$(row).after(appendTag);
	} else {
		$(row).next().remove();
	}
}

function sendImportGoodsMemo() {
	var target = window.event.target;
	var row = $(target).closest('tr');
	var proc_name = getProcName();

	$('#frmGoodsMemo').html('');
	$('#frmGoodsMemo').append(row);
	$('#frmGoodsMemo').append("<input type='hidden' name='proc_names' value='"+proc_name+"'> ");
	$('#frmGoodsMemo').append("<input type='hidden' name='proc' value='UPDATE'> ");
	
	userImportGoodsSubmit();
}

function LoadDiskInfoDetails(v_wvcs_seq) {
	$.post(
		SITE_NAME + '/result/check_result_get_disk_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#disk_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function CheckLoadVaccineInfo(v_wvcs_seq) {
	$.post(
		SITE_NAME + '/result/check_result_get_vaccine_info.php',
		{ v_wvcs_seq: v_wvcs_seq },
		function (data) {
			$('#vaccine_info').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

function VisitorProcessSubmit() {
	$.post(
		'rental_visitor_reg_process.php',
		$('#frmMemo').serialize(),
		function (data) {
			alert(data.msg);
			location.reload();
		},
		'json'
	);
}
//잔산반입 업데이트
function userImportGoodsSubmit() {

	$.post(
		'user_import_goods_reg_process.php',
		$('#frmGoodsMemo').serialize(),
		function (data) {
			alert(data.msg);
			location.reload();
		},
		'json'
	);
}
function VisitorRegProcess() {

	var v_user_list_seq = $("#v_user_list_seq").val();
	var rent_list_seq = $("#rent_list_seq").val();

	if(v_user_list_seq > 0 || rent_list_seq > 0){

		var in_time = $("#in_time").val();
		var rent_date = $("#rent_date").val();
		var dateFormat = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/;
		if (!dateFormat.test(in_time) && !dateFormat.test(rent_date)) {
			alert(qunvaliddate[lang_code]);
			return false;
		} 
	}

	var proc_name = getProcName();
	var proc="UPDATE"
	$("#frmMemo input[name='proc_name']").val(proc_name);
	$("#frmMemo input[name='proc']").val("UPDATE");



	$.post(
		'rental_details_update_process.php',
		$('#frmMemo').serialize(),
		function (data) {
			alert(data.msg);
			location.reload();
		},
		'json'
	);
}

function VisitorRegProcess_IDC() {

	var proc_name = getProcName();
	$("#frmMemo input[name='proc_name']").val(proc_name);
	$("#frmMemo input[name='proc']").val('UPDATE');

	$.post(
		'access_info_idc_process.php',
		$('#frmMemo').serialize(),
		function (data) {
			alert(data.msg);
			location.reload();
		},
		'json'
	);
}
function usbInfoUpdate() {

	var proc_name = getProcName();
	$("#frmUsb input[name='proc_name']").val(proc_name);
	$("#frmUsb input[name='proc']").val("UPDATE");

	$.post(
		'user_usb_info_process.php',
		$('#frmUsb').serialize(),
		function (data) {
			alert(data.msg);
			location.reload();
		},
		'json'
	);
}

function RentRecovery(rent_list_seq) {
	var proc = 'RECOVERY';

	var proc_name = getProcName();

	if (!CheckRecoverySubmit(proc)) return false;
	$.post(
		'rent_details_recovery.php',
		{
			// proc: proc,
			proc: proc,
			proc_name: proc_name,
			rent_list_seq: rent_list_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}
function cancelRecovery(rent_list_seq) {
	var proc = 'CANCELATION';

	var proc_name = getProcName();

	if (!CheckcancelRecovery(proc)) return false;
	$.post(
		'rent_details_recovery.php',
		{
			proc: proc,
			proc_name: proc_name,
			rent_list_seq: rent_list_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}
function CheckRecoverySubmit(proc) {
	if (proc == 'RECOVERY') {
		if (!confirm(qcollectconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#center_code'), qcentercodeinput[lang_code])) return false;
		if (!CheckBlankData($('#center_name'), qcenternameinput[lang_code])) return false;
	}

	return true;
}
function CheckcancelRecovery(proc) {
	if (proc == 'CANCELATION') {
		if (!confirm(qcancelconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#center_code'), qcentercodeinput[lang_code])) return false;
		if (!CheckBlankData($('#center_name'), qcenternameinput[lang_code])) return false;
	}

	return true;
}

//반납처리

function returnProcess(v_user_list_seq) {
	var proc = 'RETURN_PROCESS';

	var proc_name = getProcName();

	if (!CheckReturnProcess(proc)) return false;
	$.post(
		'rent_details_recovery.php',
		{
			proc: proc,
			proc_name: proc_name,
			v_user_list_seq: v_user_list_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}
function cancelReturnProcess(v_user_list_seq) {
	var proc = 'RETURN_CANCELATION';

	var proc_name = getProcName();

	if (!CheckcancelReturnProcess(proc)) return false;
	$.post(
		'rent_details_recovery.php',
		{
			proc: proc,
			proc_name: proc_name,
			v_user_list_seq: v_user_list_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}
function CheckReturnProcess(proc) {
	if (proc == 'RETURN_PROCESS') {
		if (!confirm(qreturnconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#center_code'), qcentercodeinput[lang_code])) return false;
		if (!CheckBlankData($('#center_name'), qcenternameinput[lang_code])) return false;
	}

	return true;
}
function CheckcancelReturnProcess(proc) {
	if (proc == 'RETURN_CANCELATION') {
		if (!confirm(qreturncancelconfirm[lang_code])) {
			return false;
		}
	} else {
		if (!CheckBlankData($('#center_code'), qcentercodeinput[lang_code])) return false;
		if (!CheckBlankData($('#center_name'), qcenternameinput[lang_code])) return false;
	}

	return true;
}

//반출처리

function takeOutProc(v_user_list_goods_seq) {
	var proc = 'TAKE_OUT_PROCESS';

	var proc_name = getProcName();

	if (!confirm(qtakeoutconfirm[lang_code])) {
		return false;
	}

	$.post(
		'access_info_takeout_process.php',
		{
			proc: proc,
			proc_name: proc_name,
			v_user_list_goods_seq: v_user_list_goods_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}
function canceltakeOutProc(v_user_list_goods_seq) {
	var proc = 'TAKE_OUT_CANCELATION';

	var proc_name = getProcName();

	if (!confirm(qtakeoutcancelconfirm[lang_code])) {
		return false;
	}
	$.post(
		'access_info_takeout_process.php',
		{
			proc: proc,
			proc_name: proc_name,
			v_user_list_goods_seq: v_user_list_goods_seq,
		},
		function (data) {
			alert(data.msg);
			if (data.status) {
				location.reload();
			}
		},
		'json'
	);
}

function popVisitorVcsLog(data1, data2, data3, data4) {
	$.post(
		SITE_NAME + '/user/pop_visitor_check_log.php',
		{
			data1: data1,
			data2: data2,
			data3: data3,
			data4: data4,
		},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}
function queryDeleteSubmit(custom_query_seq) {
	if (!confirm(qdeleteconfirm[lang_code])) return false;

	var proc_name = getProcName();

	$.post(
		'./query_save_process.php',
		{
			proc : 'DELETE',
			proc_name : proc_name,
			custom_query_seq: custom_query_seq,
		},
		function (data) {
			alert(data.msg);
			if(data.status){
				location.reload();
			}
		},
		'json'
	);
}
$(function () {
	$('#start_date').datepicker(pickerOpts);
	$('#end_date').datepicker(pickerOpts);
});

function submitQuery(frm) {
	 
	 var proc_name = getProcName();
	$("#proc_name").val(proc_name);

	
	if ($("#query").val() == '') {
		$("#query").focus();
		alert(qquerysearchkeywordinput[lang_code]);
		return false;
	}

	var query_enc = btoa(unescape(encodeURIComponent($("#query").val())));

	$.post(
		'./query_get_result.php',
		{
			"query_enc" : query_enc
		},
		function (data) {
			$('#query_result').html(data);
			controllPageExecAuth();
		},
		'text'
	);
}

$(document).ready(function () {
	$('#openModalBtn').click(function () {
		$('.modal').css('display', 'block');
	});

	$('.close').click(function () {
		$(this).closest('.modal').css('display', 'none');
	});

	$('.modal-content').click(function (event) {
		event.stopPropagation();
	});

});

// 쿼리 저장하기
function saveQuery() {

	var query = $('#query').val();
	var query_title = $('#query_title').val();
	if (query.trim() === '') {
		alert(qquerysearchkeywordinput[lang_code]);
		$('.modal').css('display', 'none');
		return false; // Prevent form submission
	} else if (query_title.trim() === '') {
		alert(qquerytitle[lang_code]);
		return false;
	}
	
	var proc_name = getProcName();
	$("#frmSaveQuery input[name='proc_name']").val(proc_name);

	var query_enc = btoa(unescape(encodeURIComponent($("#query").val())));
	$("#query_enc").val(query_enc);

	var proc = $("#custom_query_seq").val()=="" ? "CREATE" : "UPDATE";
	$("#proc").val(proc);


	$.post(
		SITE_NAME + '/manage/query_save_process.php',
		$("#frmSaveQuery").serialize(),
		function (data) {
			if(data.status){
				$('.modal').css('display', 'none');
			}
			alert(data.msg);
		},
		'json'
	);
}

function fileDownLoadProg(v_wvcs_seq) {

	var proc_name = getProcName();

	$.post(
		SITE_NAME + '/result/get_file.php',
		{
			v_wvcs_seq: v_wvcs_seq
			,proc_name : proc_name
		},
		function (data) {
			if (data.status) {
				var file_path = data.result.file_path;
				var file_down_name = data.result.file_down_name;
        showProgressPopup()
				_fileDownLoadProg(file_path, file_down_name);
			} else {
				alert(data.msg);
			}
		},
		'json'
	);

	return false;
}
function showProgressPopup() {
	$('#progressPopup').css('display', 'block');
}

function updateProgress(progress) {
	$('#downloadProgress').val(progress * 100);

}
 
function _fileDownLoadProg(file, filename){
  var url = SITE_NAME + '/common/download.php?file='+file+"&filename="+filename;
   fetch(url).then(response => {
    const reader = response.body.getReader();
    const contentLength = +response.headers.get('Content-Length');
    let receivedLength = 0;
    let chunks = [];

    function processResult(result) {
      if (result.done) {
        console.log('Download complete');
					$('#progressPopup').css('display', 'none');// hide the popup
        const blob = new Blob(chunks);
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        return;
      }

      chunks.push(result.value);
      receivedLength += result.value.length;

      // Calculate progress
      const progress = receivedLength / contentLength;
      updateProgress(progress); // Update progress bar
      console.log(`Progress: ${progress * 100}%`);

      return reader.read().then(processResult);
    }

    return reader.read().then(processResult);
  });
}



function WorkLogDetail(log_seq) {
	// var param = ParamEnCoding('log_seq=' + log_seq);

	$.post(
		'./pop_work_log_detail.php',
		{log_seq:log_seq},
		function (data) {
			$('#popContent').html(data);
			$('#popContent').show();
			EnableScroll(false);
			controllPageExecAuth();
		},
		'text'
	);

	return false;
}
// main_dahsboard chart
function CallStatisticsMainCheckData() {
	var onTab = $('#onTab').val();

	if (onTab == 'DAYLIST') {
		StatisticsMainCheckData('DAY');
	} else if (onTab == 'MONTHLIST') {
		StatisticsMainCheckData('MONTH');
	}

	StatisticsMainCheckData('ORG');
	StatisticsMainCheckData('DEPT');
	StatisticsMainCheckData('WEAK');
	StatisticsMainCheckData('VIRUS');
}

function ReportStatisticsMainCheckData(gubun) {
	var year = '';
	var month = '';
	var asset_type = '';

	if (gubun == 'DAY') {
		year = $('#daily_vcs_status_year').val();
		month = $('#daily_vcs_status_month').val();
	} else if (gubun == 'MONTH') {
		year = $('#monthly_vcs_status_year').val();
	} else if (gubun == 'DAY_DEVICE') {
		year = $('#daily_dvcs_status_year').val();
		month = $('#daily_dvcs_status_month').val();
	} else if (gubun == 'MONTH_DEVICE') {
		year = $('#monthly_dvcs_status_year').val();
	} else if (gubun == 'WEAK') {
		year = $('#weak_status_year').val();
		month = $('#weak_status_month').val();
	} else if (gubun == 'VIRUS') {
		year = $('#virus_status_year').val();
		month = $('#virus_status_month').val();
	}

	//alert(year+month);

	GetStatisticsMainCheckData(gubun, year, month, asset_type);
}

function StatisticsMainCheckData(gubun) {
	var year = '';
	var month = '';
	var asset_type = '';

	if (gubun == 'DAY') {
		year = $("#year").val();
		month = $("#month").val();
		asset_type = $(".tab>li[name='DAYLIST'] div select[name='asset_type']").val();
	} else if (gubun == 'MONTH') {
		year = $(".tab>li[name='MONTHLIST'] div select[name='year']").val();
		month = '';
		asset_type = $(".tab>li[name='MONTHLIST'] div select[name='asset_type']").val();
	} else if (gubun == 'ORG') {
		year = $(".section02 select[name='year']").val();
		month = $(".section02 select[name='month']").val();
		asset_type = $(".section02 select[name='asset_type']").val();
	} else if (gubun == 'DEPT') {
		year = $(".section03 select[name='year']").val();
		month = $(".section03 select[name='month']").val();
		asset_type = $(".section03 select[name='asset_type']").val();
	} else if (gubun == 'WEAK') {
		year = $(".section04 select[name='year']").val();
		month = $(".section04 select[name='month']").val();
		asset_type = $(".section04 select[name='asset_type']").val();
	} else if (gubun == 'VIRUS') {
		year = $(".section05 select[name='year']").val();
		month = $(".section05 select[name='month']").val();
		asset_type = $(".section05 select[name='asset_type']").val();
	}

	$('#chart_year').val(year);
	$('#chart_month').val(month);
	$('#asset_type').val(asset_type);

	GetStatisticsMainCheckData(gubun, year, month, asset_type);
}
function GetStatisticsMainCheckData(gubun, year, month, asset_type) {
	var vcs_status = $('#vcs_status').val();
	var org_check_result = $('#org_check_result').val();
	var dept_check_result = $('#dept_check_result').val();

	$.post(
		SITE_NAME + '/stat/vcs_stat_period_process.php',
		{
			gubun: gubun,
			year: year,
			month: month,
			asset_type: asset_type,
			vcs_status: vcs_status,
			org_check_result: org_check_result,
			dept_check_result: dept_check_result,
		},
		function (data) {
			if (data.status) {
				var colors = null;

				if (gubun == 'DAY' || gubun == 'MONTH') {
					var arr_date_check_data = data.result.date_check_data;
					var arr_date_weak_data = data.result.date_weak_data;
					var arr_date_virus_data = data.result.date_virus_data;
					var arr_date_unit = data.result.date_unit;

					if (data.result.link != null) {
						var arr_date_check_link = data.result.link.date_check;
						var arr_date_weak_link = data.result.link.date_weak;
						var arr_date_virus_link = data.result.link.date_virus;
					}

					var arr_labels = [];

					if (gubun == 'DAY') {
						for (var i = 1; i <= daysInMonth(year, month); i++) arr_labels.push(i);
					} else if (gubun == 'MONTH') {
						arr_labels = Samples.utils.months();
					}

					var barChartData = {
						labels: arr_labels,
						datasets: [],
					};

					var color = Chart.helpers.color;

					var arr1 = {
						label: visitorvisit[lang_code],
						backgroundColor: color('#0274eb').alpha(0.5).rgbString(),
						borderColor: '#0274eb',
						borderWidth: 1,
						data: arr_date_check_data,
						data_label: arr_date_unit,
						data_link: arr_date_check_link,
					};
					var arr2 = {
						label: fileimport[lang_code],
						backgroundColor: color('#43c55b').alpha(0.5).rgbString(),
						borderColor: '#43c55b',
						borderWidth: 1,
						data: arr_date_weak_data,
						data_label: arr_date_unit,
						data_link: arr_date_weak_link,
					};
					// var arr3 = {
					// 	label: virusdectiontext[lang_code],
					// 	backgroundColor: color('#ddaadc').alpha(0.5).rgbString(),
					// 	borderColor: '#c699c6',
					// 	borderWidth: 1,
					// 	data: arr_date_virus_data,
					// 	data_label: arr_date_unit,
					// 	data_link: arr_date_virus_link,
					// };

					barChartData['datasets'].push(arr1);
					barChartData['datasets'].push(arr2);
					// barChartData['datasets'].push(arr3);

					bindBarChart('chartPcCheck' + gubun, barChartData);

					//chart datatable
					var $_charttable = $('#chartPcCheck' + gubun + '_ChartDataTable');

					if ($_charttable.length > 0) {
						$_charttable.html(ChartdataToTable(barChartData));
					}
				} else if (gubun == 'DAY_DEVICE' || gubun == 'MONTH_DEVICE') {
					var arr_date_notebook_data = data.result.device_date_notebook_data;
					var arr_date_hdd_data = data.result.device_date_hdd_data;
					var arr_date_removable_data = data.result.device_date_removable_data;
					var arr_date_etc_data = data.result.device_date_etc_data;
					var arr_date_unit = data.result.device_date_unit;

					var arr_labels = [];

					if (gubun == 'DAY_DEVICE') {
						for (var i = 1; i <= daysInMonth(year, month); i++) arr_labels.push(i);
					} else if (gubun == 'MONTH_DEVICE') {
						arr_labels = Samples.utils.months();
					}

					var barChartData = {
						labels: arr_labels,
						datasets: [],
					};

					var color = Chart.helpers.color;

					var arr1 = {
						label: laptoptext[lang_code],
						backgroundColor: color('#ffe5b4').alpha(0.5).rgbString(),
						borderColor: '#fecd6e',
						borderWidth: 1,
						data: arr_date_notebook_data,
						data_label: arr_date_unit,
					};
					var arr2 = {
						label: hddtext[lang_code],
						backgroundColor: color('#abdedf').alpha(0.5).rgbString(),
						borderColor: '#5cc3c2',
						borderWidth: 1,
						data: arr_date_hdd_data,
						data_label: arr_date_unit,
					};
					var arr3 = {
						label: removabletext[lang_code],
						backgroundColor: color('#ddaadc').alpha(0.5).rgbString(),
						borderColor: '#c699c6',
						borderWidth: 1,
						data: arr_date_removable_data,
						data_label: arr_date_unit,
					};
					var arr4 = {
						label: etctext[lang_code],
						backgroundColor: color('#ccddaa').alpha(0.5).rgbString(),
						borderColor: '#b2c195',
						borderWidth: 1,
						data: arr_date_etc_data,
						data_label: arr_date_unit,
					};

					barChartData['datasets'].push(arr1);
					barChartData['datasets'].push(arr2);
					barChartData['datasets'].push(arr3);
					barChartData['datasets'].push(arr4);

					bindBarChart('chartPcCheck' + gubun, barChartData);

					//chart datatable
					var $_charttable = $('#chartPcCheck' + gubun + '_ChartDataTable');

					if ($_charttable.length > 0) {
						$_charttable.html(ChartdataToTable(barChartData));
					}
				} else if (gubun == 'ORG') {
					var arr_org_data = data.result.org_data;
					var orgChartData = oChart.doughnutData(
						arr_org_data.id,
						arr_org_data.label,
						arr_org_data.value,
						arr_org_data.link,
						colors
					);
					bindDoughnutChart('chartPcCheckORG', orgChartData);
				} else if (gubun == 'DEPT') {
					var arr_dept_data = data.result.dept_data;
					var deptChartData = oChart.doughnutData(
						arr_dept_data.id,
						arr_dept_data.label,
						arr_dept_data.value,
						arr_dept_data.link,
						colors
					);
					bindDoughnutChart('chartPcCheckDEPT', deptChartData);
				} else if (gubun == 'WEAK') {
					var arr_weak_data = data.result.weak_data;
					var weakChartData = oChart.doughnutData(
						arr_weak_data.id,
						arr_weak_data.label,
						arr_weak_data.value,
						arr_weak_data.link,
						colors
					);
					bindDoughnutChart('chartPcCheckWEAK', weakChartData);
				} else if (gubun == 'VIRUS') {
					var arr_virus_data = data.result.virus_data;
					var virusChartData = oChart.doughnutData(
						arr_virus_data.id,
						arr_virus_data.label,
						arr_virus_data.value,
						arr_virus_data.link,
						colors
					);
					bindDoughnutChart('chartPcCheckVIRUS', virusChartData);
				}
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

// //////////////////////////////////////////////////////////////////
function StatisticsDayVcsData2(date) {
	$.post(
		SITE_NAME + '/stat/vcs_stat_process.php',
		{
			date: date,
		},
		function (data) {
			if (data.status) {
				var pc_check_data = addCommas(data.result.pc_check_data);
				var pc_weak_data = addCommas(data.result.pc_weak_data);
				var pc_virus_data = addCommas(data.result.pc_virus_data);
				var storage_check_data = addCommas(data.result.storage_check_data);
				var storage_virus_data = addCommas(data.result.storage_virus_data);

				var url = SITE_NAME + '/result/result_list.php';
				var param1 =
					'src=chart&asset_type=NOTEBOOK&check_result1=all&checkdate1=' +
					date +
					'&checkdate2=' +
					date;
				var param2 =
					'src=chart&asset_type=RemovableDevice&check_result1=all&checkdate1=' +
					date +
					'&checkdate2=' +
					date;

				$('#chartPcCheckDAY').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param1 + '&check_result2=') +
						'\'">' +
						pc_check_data +
						'</span>'
				);
				$('#ChartPcCheckData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param1 + '&check_result2=') +
						'\'">' +
						pc_check_data +
						'</span>'
				);
				$('#ChartPcWeakData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param1 + '&check_result2=weak') +
						'\'">' +
						pc_weak_data +
						'</span>'
				);
				$('#ChartPcVirusData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param1 + '&check_result2=virus') +
						'\'">' +
						pc_virus_data +
						'</span>'
				);
				$('#ChartStorageCheckData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param2 + '&check_result2=') +
						'\'">' +
						storage_check_data +
						'</span>'
				);
				$('#ChartStorageVirusData').html(
					"<span style='cursor:pointer;' onclick=\"location.href='" +
						url +
						'?enc=' +
						ParamEnCoding(param2 + '&check_result2=virus') +
						'\'">' +
						storage_virus_data +
						'</span>'
				);

				var ids = [];
				var labels = [];
				var values = new Array('1', '2');
				var data_links = [];

				bindDoughnutChartMain(
					'ChartPcCheck',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#29b0d0'))
				);
				bindDoughnutChartMain(
					'ChartPcWeak',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#ffab34'))
				);
				bindDoughnutChartMain(
					'ChartPcVirus',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
				);
				bindDoughnutChartMain(
					'ChartStorageCheck',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#397ecc'))
				);
				bindDoughnutChartMain(
					'ChartStorageVirus',
					oChart.doughnutData(ids, labels, values, data_links, new Array('#dedfe8', '#fd5500'))
				);
			} else {
				if (typeof window.myDoughnut != 'undefined') {
					window.myDoughnut.destroy();
				}

				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}


function ChartResizeMain2() {
	var w = $('#main2').width();

	//alert(w);

	//**Chart Resize
	if (w > 1300) {
		$('.section canvas').width(500);
		$('.section .txt').css('bottom', '-30px');
		$('.section .txt2').css('top', '100px');
	} else {
		$('.section canvas').width(400);
		$('.section .txt').css('bottom', '0px');
		$('.section .txt2').css('top', '80px');
	}
}


function loadStatisticsVisitPeriodChart() {	
	$.post(
		SITE_NAME + '/dashboard/get_main_vcs_status.php',
		$("#chartsearchForm").serialize(),
		function (data) {

			if (data.status) {
				
				bindStatisticsVisitPeriodChart('chartVisitPeriod', data);
									
			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function bindStatisticsVisitPeriodChart(chart_id,data){
	
	var data_value = data.result.data_value;

	var data_label = data.result.data_label;

	var data_file_value = data.result.data_file_value;
	if (data.result.link != null ) { 
		
		var data_visit_link = data.result.link.visit_user;
		var data_file_link = data.result.link.file_in;

	}
	

	var data_file_label = data.result.data_file_label;

	var barChartData = {
		labels: data_label,
		datasets: [],
	};

	var color = Chart.helpers.color;

	var arr1 = {
		label: visitorvisit[lang_code],
		backgroundColor: color('#0773e4').alpha(0.5).rgbString(),
		borderColor: '#0773e4',
		borderWidth: 1,
		data: data_value,
		data_label: data_label,
		data_link: data_visit_link,
	};
	var arr2 = {
		label: fileimport[lang_code],
		backgroundColor: color('#4ec065').alpha(0.5).rgbString(),
		borderColor: '#4ec065',
		borderWidth: 1,
		data: data_file_value,
		data_label: data_file_label,
		data_link: data_file_link,
	};	

	
	
	barChartData['datasets'].push(arr1);
	barChartData['datasets'].push(arr2);
	

	bindBarChart(chart_id, barChartData);

}
function bindDoughnutChartExpire(id, Chartdata) {
	if (typeof Chartdata != 'undefined') {
		var dataArray = Chartdata.datasets[0].data;
	
	}
	
	if (typeof window['myDoughnut_' + id] != 'undefined') {
		window['myDoughnut_' + id].destroy();
	}

	$('#' + id).bind('mousemove', function (event) {
		event = event || window.event;
		event = jQuery.event.fix(event);
		chart_tooltip_position.x = event.pageX;
		chart_tooltip_position.y = event.pageY;
	});

	var ctx = document.getElementById(id).getContext('2d');

if (typeof Chartdata == 'undefined' || dataArray.every(value => value === 0) ) {
    ctx.font = '12px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(nodatatext[lang_code], 150, 100);
    document.getElementById(id + '_legend').innerHTML = '';

    // default doughnut chart with legend and customized options
    var defaultData = {
        labels: [nodatatext[lang_code]],
        datasets: [{
            data: [1],
            backgroundColor: ['#bfbfbf'],
        }],
    };

    window['myDoughnut_' + id] = new Chart(ctx, {
        type: 'doughnut',
        data: defaultData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right',
                display: false,
                labels: {
                    padding: 15,
                    fontFamily: 'MalgunGothic',
                    fontColor: 'rgb(120, 120, 120)',
                },
            },
            title: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true,
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0,
                },
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        padding: 15,
                        fontFamily: 'MalgunGothic',
                        fontColor: 'rgb(120, 120, 120)',
                    },
                },
            },
        },
    });

    document.getElementById(id + '_legend').innerHTML = window['myDoughnut_' + id].generateLegend();
}else {
		window['myDoughnut_' + id] = new Chart(ctx, {
			type: 'doughnut',
			 data: Chartdata,
			
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					position: 'right',
					display: false,
					labels: {
						padding: 15,
						fontFamily: 'MalgunGothic',
						fontColor: 'rgb(120, 120, 120)',
					},
				},
				title: {
					display: false,
				},
				animation: {
					animateScale: true,
					animateRotate: true,
				},
				layout: {
					padding: {
						left: 0,
						right: 0,
						top: 0,
						bottom: 0,
					},
				},
				legendCallback: function (chart) {
					var text = [];
					text.push('<ul class="' + chart.id + '-legend">');

					var data = chart.data;
					var datasets = data.datasets;
					var labels = data.labels;

					if (datasets.length) {
						var total = 0;
						var percentage = 0;

						for (var i = 0; i < datasets[0].data.length; ++i) {
							total += datasets[0].data[i];
						}

						for (var i = 0; i < datasets[0].data.length; ++i) {
							text.push(
								'<li><span style="background-color:' + datasets[0].backgroundColor[i] + '"></span>'
							);

							if (labels[i]) {
								var percentage = 0;
								var cnt = datasets[0].data[i];

								if (total > 0) {
									percentage = Math.round((cnt / total) * 100);
								}

								text.push(labels[i] + ' (' + addCommas(cnt) + ')');

								//text.push(labels[i] + ' (' + addCommas(cnt)+" of "+addCommas(total)+' ,' + percentage + '%' + ')');
							}
							text.push('</li>');
						}
					}
					text.push('</ul>');
					return text.join('');
				},
				tooltips: {
					enabled: false,
					custom: function (tooltipModel) {
						// Tooltip Element
						var tooltipEl = document.getElementById('chartjs-tooltip');

						// Create element on first render
						if (!tooltipEl) {
							tooltipEl = document.createElement('div');
							tooltipEl.id = 'chartjs-tooltip';
							tooltipEl.innerHTML = '<table></table>';
							document.body.appendChild(tooltipEl);
						}

						// Hide if no tooltip
						if (tooltipModel.opacity === 0) {
							tooltipEl.style.opacity = 0;
							return;
						}

						// Set caret Position
						tooltipEl.classList.remove('above', 'below', 'no-transform');
						if (tooltipModel.yAlign) {
							tooltipEl.classList.add(tooltipModel.yAlign);
						} else {
							tooltipEl.classList.add('no-transform');
						}

						function getBody(bodyItem) {
							return bodyItem.lines;
						}

						// Set Text
						if (tooltipModel.body) {
							var titleLines = tooltipModel.title || [];
							var bodyLines = tooltipModel.body.map(getBody);

							var innerHtml = '<thead>';

							titleLines.forEach(function (title) {
								innerHtml += '<tr><th>' + title + '</th></tr>';
							});
							innerHtml += '</thead><tbody>';

							bodyLines.forEach(function (body, i) {
								var colors = tooltipModel.labelColors[i];
								var style = 'background:' + colors.backgroundColor;
								style += '; border-color:' + colors.borderColor;
								style += '; border-width: 2px';
								var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
								innerHtml +=
									'<tr><td>' + span + "<span style='color:white'> " + body + '</span></td></tr>';
							});
							innerHtml += '</tbody>';

							var tableRoot = tooltipEl.querySelector('table');
							tableRoot.innerHTML = innerHtml;
						}

						// `this` will be the overall tooltip
						//var position = this._chart.canvas.getBoundingClientRect();

						var x = chart_tooltip_position.x;
						var y = chart_tooltip_position.y;

						// Display, position, and set styles for font
						tooltipEl.style.opacity = 1;
						tooltipEl.style.zIndex = '999999999999';
						tooltipEl.style.position = 'absolute';
						//tooltipEl.style.left = position.left + tooltipModel.caretX + 'px';
						//tooltipEl.style.top = position.top + tooltipModel.caretY + 'px';
						tooltipEl.style.left = x + 'px';
						tooltipEl.style.top = y + 'px';
						tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
						tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
						tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
						tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
					},
					callbacks: {
						title: function (tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function (tooltipItem, data) {
							var total = 0;
							for (i = 0; i < data['datasets'][0]['data'].length; i++) {
								total += data['datasets'][0]['data'][i];
							}
							var percentage = Math.round(
								(data['datasets'][0]['data'][tooltipItem['index']] / total) * 100
							);

							var cnt = data['datasets'][0]['data'][tooltipItem['index']];

							return addCommas(cnt) + ' of ' + addCommas(total) + ' (' + percentage + '%)';
						},
					},
					backgroundColor: '#000',
					titleFontSize: 10,
					titleFontColor: '#fff',
					bodyFontColor: '#fff',
					bodyFontSize: 10,
					displayColors: true,
				},
			}, // options:
		});

		document.getElementById(id + '_legend').innerHTML = window['myDoughnut_' + id].generateLegend();

		$('#' + id + '_legend > ul > li').bind('click', function (e) {
			var index = $(this).index();
			$(this).toggleClass('strike');
			var ci = e.view.window['myDoughnut_' + id];
			var meta = ci.getDatasetMeta(0);

			if (meta.dataset) {
				meta.hidden = !meta.hidden;
			} else {
				meta.data[index].hidden = !meta.data[index].hidden;
			}

			ci.update();
		});
	} //**if(typeof Chartdata == 'undefined'){

	//차트 클릭 이벤트
	var myChart = window['myDoughnut_' + id];

	if (myChart == undefined) return;

	document.getElementById(id).onclick = function (evt) {
		var activePoints = myChart.getElementsAtEvent(evt);

		if (activePoints == '') return;

		var clickedElementindex = activePoints[0]._index;
		var clickedDatasetindex = activePoints[0]._datasetIndex;

		var link = '';

		if (myChart.data.datasets[activePoints[0]._datasetIndex].data_link != undefined
			&& myChart.data.datasets[activePoints[0]._datasetIndex].data_link != "") {
			link = myChart.data.datasets[clickedDatasetindex].data_link[clickedElementindex];

			location.href = link;
			return;
		}
	};
}
function loadgetNotReturnChart(){

	$.post(
		SITE_NAME + '/dashboard/get_main_expired_status.php',
		$("#chartsearchForm").serialize(),
		function (data) {
			if (data.status) {

				var not_return_stat_data = data.result.not_return_stat_data;

				var colors = null;
					var notReturnStatChartData = oChart.doughnutData(
					not_return_stat_data.id,
					not_return_stat_data.label,
					not_return_stat_data.value,
					not_return_stat_data.link,
					colors
				);
			
				 bindDoughnutChartExpire('chartPcCheckWEAK', notReturnStatChartData);


			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}
function loadMainServerStatusChart(){
console.log("main server");
	$("#chart_main_server_status").serialize();
	var disk_total_value = $("#disk_total_value").val();
	var disk_free_value = $("#disk_free_value").val();
	gaugeChartData('3', disk_total_value, disk_free_value);

// $.post(
// 	SITE_NAME + '/dashboard/main_server_status_inc_process.php',
// 	$("#chart_main_server_status").serialize(),
// 	function (data) {
// 		if (data.status) {
			
// 			var main_server_status = data.result.main_server_status;
// 			console.log("main server data", data.result.main_server_status);




// 				var colors = null;
// 					var mainServerStatusChartData = oChart.doughnutData(
// 					main_server_status.id,
// 					main_server_status.label,
// 					main_server_status.value,
// 					main_server_status.link,
// 					colors
// 				);
    
		
// 				//  bindDoughnutChartExpire('chartPcCheckWEAK', mainServerStatusChartData);
// 				gaugeChartData('3', mainServerStatusChartData);


// 			} else {
// 				alert(data.msg);
// 			} //if(data.status){
// 		},
// 		'json'
// 	);
}
function gaugeChartData(id, disk_total_value, disk_free_value) { 
	if ( disk_total_value == '') {
		labels = ['no data'];
		data = [1];
		colors = ["#dddddd"];
	} else { 
	data = [disk_total_value, disk_free_value];
		labels = ['used space in %: ', 'free space in %: '];
		colors = ['#159CFA', '#e5e5e5'];
		for (i = 0; i < data; i++) {
			colors[i];
		}
	}

	Chart.defaults.global.legend.display = false;
	var ctx2 = document.getElementById(id).getContext('2d');
	var myChart = new Chart(ctx2, {
		type: 'doughnut',
		data: {
			datasets: [{
				
				//  backgroundColor: <? php echo json_encode($colors); ?>,
				 backgroundColor: colors,
				borderWidth: 0,
				hoverOffset: 2,
				// data: <? php echo json_encode($data); ?>,
				data: data,
	}],
		// labels: <? php echo json_encode($labels) ?>,
		labels: labels,
								},
options: {
	rotation: -1.0 * Math.PI, // start angle in radians
		circumference: Math.PI, // sweep angle in radians
			responsive: true,
				plugins: {
		tooltip: {
			enabled: true,
				titleAlign: 'center',
					bodyAlign: 'center',
						displayColors: false,
										},
		legend: {
			display: false
		}
	},
	maintainAspectRatio: false,
		cutoutPercentage: 55,
								}
							});

}



function bindDoughnutChartVirus(id, Chartdata) {

	if (typeof window['myDoughnut_' + id] != 'undefined') {
		window['myDoughnut_' + id].destroy();
	}

	$('#' + id).bind('mousemove', function (event) {
		event = event || window.event;
		event = jQuery.event.fix(event);
		chart_tooltip_position.x = event.pageX;
		chart_tooltip_position.y = event.pageY;
	});

	var ctx = document.getElementById(id).getContext('2d');

if (typeof Chartdata == 'undefined') {
    ctx.font = '12px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(nodatatext[lang_code], 150, 100);
    document.getElementById(id + '_legend').innerHTML = '';

    // Create a default doughnut chart with legend and customized options
    var defaultData = {
        labels: [nodatatext[lang_code]],
        datasets: [{
            data: [1],
            backgroundColor: ['#bfbfbf'],
        }],
    };

    window['myDoughnut_' + id] = new Chart(ctx, {
        type: 'doughnut',
        data: defaultData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right',
                display: false,
                labels: {
                    padding: 15,
                    fontFamily: 'MalgunGothic',
                    fontColor: 'rgb(120, 120, 120)',
                },
            },
            title: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true,
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0,
                },
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        padding: 15,
                        fontFamily: 'MalgunGothic',
                        fontColor: 'rgb(120, 120, 120)',
                    },
                },
            },
        },
    });

    document.getElementById(id + '_legend').innerHTML = window['myDoughnut_' + id].generateLegend();
}else {
		window['myDoughnut_' + id] = new Chart(ctx, {
			type: 'doughnut',
			 data: Chartdata,
			
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					position: 'right',
					display: false,
					labels: {
						padding: 15,
						fontFamily: 'MalgunGothic',
						fontColor: 'rgb(120, 120, 120)',
					},
				},
				title: {
					display: false,
				},
				animation: {
					animateScale: true,
					animateRotate: true,
				},
				layout: {
					padding: {
						left: 0,
						right: 0,
						top: 0,
						bottom: 0,
					},
				},
				legendCallback: function (chart) {
					var text = [];
					text.push('<ul class="' + chart.id + '-legend">');

					var data = chart.data;
					var datasets = data.datasets;
					var labels = data.labels;

					if (datasets.length) {
						var total = 0;
						var percentage = 0;

						for (var i = 0; i < datasets[0].data.length; ++i) {
							total += datasets[0].data[i];
						}

						for (var i = 0; i < datasets[0].data.length; ++i) {
							text.push(
								'<li><span style="background-color:' + datasets[0].backgroundColor[i] + '"></span>'
							);

							if (labels[i]) {
								var percentage = 0;
								var cnt = datasets[0].data[i];

								if (total > 0) {
									percentage = Math.round((cnt / total) * 100);
								}

								text.push(labels[i] + ' (' + addCommas(cnt) + ')');

								//text.push(labels[i] + ' (' + addCommas(cnt)+" of "+addCommas(total)+' ,' + percentage + '%' + ')');
							}
							text.push('</li>');
						}
					}
					text.push('</ul>');
					return text.join('');
				},
				tooltips: {
					enabled: false,
					custom: function (tooltipModel) {
						// Tooltip Element
						var tooltipEl = document.getElementById('chartjs-tooltip');

						// Create element on first render
						if (!tooltipEl) {
							tooltipEl = document.createElement('div');
							tooltipEl.id = 'chartjs-tooltip';
							tooltipEl.innerHTML = '<table></table>';
							document.body.appendChild(tooltipEl);
						}

						// Hide if no tooltip
						if (tooltipModel.opacity === 0) {
							tooltipEl.style.opacity = 0;
							return;
						}

						// Set caret Position
						tooltipEl.classList.remove('above', 'below', 'no-transform');
						if (tooltipModel.yAlign) {
							tooltipEl.classList.add(tooltipModel.yAlign);
						} else {
							tooltipEl.classList.add('no-transform');
						}

						function getBody(bodyItem) {
							return bodyItem.lines;
						}

						// Set Text
						if (tooltipModel.body) {
							var titleLines = tooltipModel.title || [];
							var bodyLines = tooltipModel.body.map(getBody);

							var innerHtml = '<thead>';

							titleLines.forEach(function (title) {
								innerHtml += '<tr><th>' + title + '</th></tr>';
							});
							innerHtml += '</thead><tbody>';

							bodyLines.forEach(function (body, i) {
								var colors = tooltipModel.labelColors[i];
								var style = 'background:' + colors.backgroundColor;
								style += '; border-color:' + colors.borderColor;
								style += '; border-width: 2px';
								var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
								innerHtml +=
									'<tr><td>' + span + "<span style='color:white'> " + body + '</span></td></tr>';
							});
							innerHtml += '</tbody>';

							var tableRoot = tooltipEl.querySelector('table');
							tableRoot.innerHTML = innerHtml;
						}

						// `this` will be the overall tooltip
						//var position = this._chart.canvas.getBoundingClientRect();

						var x = chart_tooltip_position.x;
						var y = chart_tooltip_position.y;

						// Display, position, and set styles for font
						tooltipEl.style.opacity = 1;
						tooltipEl.style.zIndex = '999999999999';
						tooltipEl.style.position = 'absolute';
						//tooltipEl.style.left = position.left + tooltipModel.caretX + 'px';
						//tooltipEl.style.top = position.top + tooltipModel.caretY + 'px';
						tooltipEl.style.left = x + 'px';
						tooltipEl.style.top = y + 'px';
						tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
						tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
						tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
						tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
					},
					callbacks: {
						title: function (tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function (tooltipItem, data) {
							var total = 0;
							for (i = 0; i < data['datasets'][0]['data'].length; i++) {
								total += data['datasets'][0]['data'][i];
							}
							var percentage = Math.round(
								(data['datasets'][0]['data'][tooltipItem['index']] / total) * 100
							);

							var cnt = data['datasets'][0]['data'][tooltipItem['index']];

							return addCommas(cnt) + ' of ' + addCommas(total) + ' (' + percentage + '%)';
						},
					},
					backgroundColor: '#000',
					titleFontSize: 10,
					titleFontColor: '#fff',
					bodyFontColor: '#fff',
					bodyFontSize: 10,
					displayColors: true,
				},
			}, // options:
		});

		document.getElementById(id + '_legend').innerHTML = window['myDoughnut_' + id].generateLegend();

		$('#' + id + '_legend > ul > li').bind('click', function (e) {
			var index = $(this).index();
			$(this).toggleClass('strike');
			var ci = e.view.window['myDoughnut_' + id];
			var meta = ci.getDatasetMeta(0);

			if (meta.dataset) {
				meta.hidden = !meta.hidden;
			} else {
				meta.data[index].hidden = !meta.data[index].hidden;
			}

			ci.update();
		});
	} //**if(typeof Chartdata == 'undefined'){

	//차트 클릭 이벤트
	var myChart = window['myDoughnut_' + id];

	if (myChart == undefined) return;

	document.getElementById(id).onclick = function (evt) {
		var activePoints = myChart.getElementsAtEvent(evt);

		if (activePoints == '') return;

		var clickedElementindex = activePoints[0]._index;
		var clickedDatasetindex = activePoints[0]._datasetIndex;

		var link = '';

		if (myChart.data.datasets[activePoints[0]._datasetIndex].data_link != undefined
			&& myChart.data.datasets[activePoints[0]._datasetIndex].data_link != "") {
			link = myChart.data.datasets[clickedDatasetindex].data_link[clickedElementindex];

			location.href = link;
			return;
		}
	};
}
function loadMainStatisticsVcsResultChart(){

	$.post(
		SITE_NAME + '/dashboard/get_main_virus_status.php',
		$("#chartsearchForm").serialize(),
		function (data) {
			if (data.status) {
				var arr_virus_data = data.result.virus_data;
				var colors = null;
					var virusChartData = oChart.doughnutData(
					arr_virus_data.id,
					arr_virus_data.label,
					arr_virus_data.value,
					arr_virus_data.link,
					colors
				);


				// bindDoughnutChart('chartPcCheckVIRUS', virusChartData);
				bindDoughnutChartVirus('chartPcCheckVIRUS', virusChartData);

			} else {
				alert(data.msg);
			} //if(data.status){
		},
		'json'
	);
}

function LoadAppUpdate() {

	var scan_center_code = $('#scan_center_code').val();
	
	$.post(
		SITE_NAME + '/dashboard/main_app_update_process.php', {
			scan_center_code: scan_center_code
		},
		function (data) {
			
			//$('#file_app_update_list').html(data);
			//$('#vaccine_update_title').html('<font color="#169cfb">(' + str_kiosk_name + ' ' + str_scan_center_name + ')</font>');

		},
		'text'
	);
}


function LoadVcsStatus() {
	$.post(
		SITE_NAME + '/dashboard/main_vcs_status_inc_process.php',
		$("#chartsearchForm").serialize(),
		function (data) {
			$('#main_vcs_status').html(data); 
		},
		'text'
	);
}


function loadMainServerStatus() {
	$.post(
		SITE_NAME + '/dashboard/main_server_status_inc_process.php',
		// $("#chart_main_server_status").serialize(),
		function (data) {
			$('#main_server_status').html(data); 
		},
		'text'
	);
}

//접속IP관리 접속허용 ID추가 입력 Row 초기화
function resetRow_AllowIPAdress(row) {
	$(row).find("input").val('');
	//bindEventClearWarning();
}

//접속IP관리 접속허용 ID추가 입력 Row 추가
function appendRow_AllowIPAdress() {
	var target = window.event.target;
	var row = $(target).closest("tr");

	$("#tbl_AllowIPAdress_List tr:last").after("<tr>" + $(row).html() + "</tr>");

	resetRow_AllowIPAdress($("#tbl_AllowIPAdress_List tr:last"));
}

//접속IP관리 접속허용 ID추가 입력 Row 삭제
function removeRow_AllowIPAdress() {
	var target = window.event.target;
	var row = $(target).closest("tr");
	var row_count = $("#tbl_AllowIPAdress_List tr:gt(0)").length;

	if (row_count == 0) {
		resetRow_AllowIPAdress(row);
	} else {
		$(row).remove();
	}
}

//접속IP관리 IP 주소추가 입력 Row 추가
function appendRow_AllowID() {
	var target = window.event.target;
	var row = $(target).closest("tr");
	var select = row.find('.search_select')

	var user = select.val();
	var text = select.find('option:selected').text().trim();

	if (user == "") {
		alert(selectAllowID[lang_code])
		return false;
	} else {
		var addCnt = $(`input[name='allow_id[]'][value='${user}']`).length;

		if (addCnt > 0) {
			alert(existsAllowID[lang_code])
			return false;
		}
	}

	select.val(null).trigger('change');

	var input = `<td style="width: 310px;"><input type="hidden" name="allow_id[]" value="${user}"><input type="text" name="fake_id[]" class="frm_input" readonly value="${text}" disabled style="width:280px"></td>`;
	var remove = `<td style="text-align:left; padding-left: 0;"><a href="javascript:void(0)" class="btn20 gray" style="width:10px;font-weight:bold;" onclick="removeRow_AllowID()">-</a></td>`;

	$("#tbl_AccessID_List tr:last").after(`<tr>${input}${remove}</tr>`);
}

//접속IP관리 IP 주소추가 입력 Row 삭제
function removeRow_AllowID() {
	var target = window.event.target;
	$(target).closest("tr").remove();
}
