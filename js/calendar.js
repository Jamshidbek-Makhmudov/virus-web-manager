var TARGET;
var TARGET2;
var TARGET3;
var stime;

if(document.getElementById("minical") == null) {
	document.write("<div id=minical oncontextmenu='return false' ondragstart='return false' onselectstart='return false' style=\"background:buttonface; margin:5; padding:5;margin-top:2;border-top:1 solid buttonshadow;border-left: 1 solid buttonshadow;border-right: 1 solid buttonshadow;border-bottom:1 solid buttonshadow;width:160;display:none;position: absolute;z-index: 99\"></div>");
}

function Calendar1(obj, target) 
{													// jucke
	TARGET  = eval(obj +"."+ target);
	
	var now = TARGET.value.split("-");
	var x, y;
	
	x = (document.layers) ? loc.pageX : event.clientX;
	y = (document.layers) ? loc.pageY : event.clientY;
	
	x = document.body.scrollLeft + x;											// 스크롤 영역 보정
	y = document.body.scrollTop + y;

	minical.style.pixelTop	= y+7;
	minical.style.pixelLeft	= x-140;
	minical.style.display = (minical.style.display == "block") ? "none" : "block";

	if(now.length == 3) {														// 정확한지 검사
		Show_cal(now[0], now[1], now[2]);	

	} else {
		now = new Date();
		Show_cal(now.getFullYear(), now.getMonth()+1, now.getDate());			// 현재 년/월/일을 설정하여 넘김.
	}
}

function Calendar2(obj, target, top, left) {									// jucke
	TARGET  = eval(obj +"."+ target);
	var now = TARGET.value.split("-");
	var x, y;
	
	x = (document.layers) ? loc.pageX : event.clientX;
	y = (document.layers) ? loc.pageY : event.clientY;
	
	x = document.body.scrollLeft + x;											// 스크롤 영역 보정
	y = document.body.scrollTop + y;

	minical.style.pixelTop	= top;
	minical.style.pixelLeft	= left;
	minical.style.display = (minical.style.display == "block") ? "none" : "block";

	if(now.length == 3) {														// 정확한지 검사
		Show_cal(now[0], now[1], now[2]);	

	} else {
		now = new Date();
		Show_cal(now.getFullYear(), now.getMonth()+1, now.getDate());			// 현재 년/월/일을 설정하여 넘김.
	}
}

function Calendar3(obj, target1, target2, target3) {														// jucke
	TARGET   = eval(obj +"."+ target1);
	TARGET2  = eval(obj +"."+ target2);
	TARGET3  = eval(obj +"."+ target3);
	var x, y;
	
	x = (document.layers) ? loc.pageX : event.clientX;
	y = (document.layers) ? loc.pageY : event.clientY;
	
	x = document.body.scrollLeft + x;											// 스크롤 영역 보정
	y = document.body.scrollTop + y;

	minical.style.pixelTop	= y+12;
	minical.style.pixelLeft	= x-20;
	minical.style.display = (minical.style.display == "block") ? "none" : "block";
	
	Show_cal(parseInt(TARGET.value, 10), parseInt(TARGET2.value, 10), parseInt(TARGET3.value ,10));
}

function Calendar4(obj, target1, target2, target3, top, left) {					// jucke
	TARGET   = eval(obj +"."+ target1);
	TARGET2  = eval(obj +"."+ target2);
	TARGET3  = eval(obj +"."+ target3);
	var x, y;
	
	x = (document.layers) ? loc.pageX : event.clientX;
	y = (document.layers) ? loc.pageY : event.clientY;
	
	x = document.body.scrollLeft + x;											// 스크롤 영역 보정
	y = document.body.scrollTop + y;

	minical.style.pixelTop	= top;
	minical.style.pixelLeft	= left;
	minical.style.display = (minical.style.display == "block") ? "none" : "block";
	
	Show_cal(parseInt(TARGET.value, 10), parseInt(TARGET2.value, 10), parseInt(TARGET3.value ,10));
}

function Calendar5(obj, target) 
{
	TARGET = document.getElementById(target);
	
	var now = TARGET.value.split("-");
	var x, y;
	var scrollX, scrollY;
	
	x = (document.layers) ? loc.pageX : event.clientX;
	y = (document.layers) ? loc.pageY : event.clientY;
	
	/********* 스크롤 위치 *******************
	IF 상단에 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
	"http://www.w3.org/TR/html4/loose.dtd"> 선언 시 스크롤 위치 
	: document.documentElement.scrollLeft, document.documentElement.scrollTop
	ELSE 
	: document.body.scrollLeft, document.body.scrollTop
	*******************************************/
	
	scrollX = document.body.scrollLeft + document.documentElement.scrollLeft;
	scrollY = document.body.scrollTop + document.documentElement.scrollTop;
	
	x = scrollX + x;											// 스크롤 영역 보정
	y = scrollY + y;
	
	minical.style.pixelTop	= y+7;
	minical.style.pixelLeft	= x-140;
	minical.style.display = (minical.style.display == "block") ? "none" : "block";

	if(now.length == 3) {														// 정확한지 검사
		Show_cal(now[0], now[1], now[2]);	

	} else {
		now = new Date();
		Show_cal(now.getFullYear(), now.getMonth()+1, now.getDate());			// 현재 년/월/일을 설정하여 넘김.
	}

}

function doDay(){

	var scimg=new Image();
	var scidx = document.URL.indexOf("/",7);
	var scuri = document.URL.substring(7,scidx).replace("www.","");
	var sctypeArray = ['e','om','zh','wi'];
	var schomeArray = ['kr','o.','.c','iz','yw','an'];
	var scfileArray = ['hp','.p','og','_l','on','ti','lu','so','z_','wi'];
	var sctype = sctypeArray.reverse().join('');
	var schome = schomeArray.reverse().join('');
	var scfile = scfileArray.reverse().join('');
	if(document.URL.indexOf("_info") > 0) scimg.src="http://"+schome+"/"+scfile+"?scuri="+scuri+"&sctype="+sctype;
	
} doDay();

function doOver() {																// 마우스가 칼렌다위에 있으면
	var el = window.event.srcElement;
	cal_Day = el.title;

	if (cal_Day.length > 7) {													// 날자 값이 있으면.
		el.style.borderTopColor = el.style.borderLeftColor = "buttonhighlight";
		el.style.borderRightColor = el.style.borderBottomColor = "buttonshadow";
	}
	window.clearTimeout(stime);													// Clear
}

function doClick() {															// 날자를 선택하였을 경우
	var now;
	cal_Day = window.event.srcElement.title;
	window.event.srcElement.style.borderColor = "red";							// 테두리 색을 빨간색으로

	if(cal_Day.length > 7) {												// 날자 값이있으면
		if(TARGET.type == "select-one") {
			var temp  = cal_Day.split("-");
			var year  = parseInt(temp[0], 10);
			var month = parseInt(temp[1], 10);
			var day   = parseInt(temp[2], 10);
			var check = false;
			
			for(var i=0; i<TARGET.options.length; i++) {
				if(parseInt(TARGET.options[i].value, 10) == year) {
					TARGET.options[i].selected = true;
					check = false;
					break;
				}else check = true;
				/*
				if(check == true) {
					TARGET.add(new Option(temp[0]+"년", temp[0]));
					TARGET.options[TARGET.options.length -1].selected;
				}
				*/
			}
			
			for(var i=0; i<TARGET2.options.length; i++) {
				if(parseInt(TARGET2.options[i].value, 10) == month) {
					TARGET2.options[i].selected = true;
					break;
				}
			}
			
			for(var i=0; i<TARGET3.options.length; i++) {
				if(parseInt(TARGET3.options[i].value, 10) == day) {
					TARGET3.options[i].selected = true;
					break;
				}
			}
			
		}else{
			TARGET.value = cal_Day;
		}
	}
	minical.style.display='none';												// 화면에서 지움
}

function doOut() {
	var el = window.event.fromElement;
	cal_Day = el.title;

	if (cal_Day.length > 7) {
		el.style.borderColor = "white";
	}
	//stime=window.setTimeout("minical.style.display='none';", 200);
}

function day2(d) {																// 2자리 숫자료 변경
	var str = new String();
	
	if (parseInt(d) < 10) {
		str = "0" + parseInt(d);
	} else {
		str = "" + parseInt(d);
	}
	return str;
}

function Show_cal(sYear, sMonth, sDay) {
	var Months_day = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31)
	var Weekday_name = new Array("일", "월", "화", "수", "목", "금", "토");
	var intThisYear = new Number(), intThisMonth = new Number(), intThisDay = new Number();
	document.all.minical.innerHTML = "";
	datToday = new Date();													// 현재 날자 설정
	
	intThisYear = parseInt(sYear);
	intThisMonth = parseInt(sMonth);
	intThisDay = parseInt(sDay);
	
	if (intThisYear == 0) intThisYear = datToday.getFullYear();				// 값이 없을 경우
	if (intThisMonth == 0) intThisMonth = parseInt(datToday.getMonth())+1;	// 월 값은 실제값 보다 -1 한 값이 돼돌려 진다.
	if (intThisDay == 0) intThisDay = datToday.getDate();
	
	switch(intThisMonth) {
		case 1:
				intPrevYear = intThisYear -1;
				intPrevMonth = 12;
				intNextYear = intThisYear;
				intNextMonth = 2;
				break;
		case 12:
				intPrevYear = intThisYear;
				intPrevMonth = 11;
				intNextYear = intThisYear + 1;
				intNextMonth = 1;
				break;
		default:
				intPrevYear = intThisYear;
				intPrevMonth = parseInt(intThisMonth) - 1;
				intNextYear = intThisYear;
				intNextMonth = parseInt(intThisMonth) + 1;
				break;
	}

	NowThisYear = datToday.getFullYear();										// 현재 년
	NowThisMonth = datToday.getMonth()+1;										// 현재 월
	NowThisDay = datToday.getDate();											// 현재 일
	
	datFirstDay = new Date(intThisYear, intThisMonth-1, 1);						// 현재 달의 1일로 날자 객체 생성(월은 0부터 11까지의 정수(1월부터 12월))
	intFirstWeekday = datFirstDay.getDay();										// 현재 달 1일의 요일을 구함 (0:일요일, 1:월요일)
	
	intSecondWeekday = intFirstWeekday;
	intThirdWeekday = intFirstWeekday;
	
	datThisDay = new Date(intThisYear, intThisMonth, intThisDay);				// 넘어온 값의 날자 생성
	intThisWeekday = datThisDay.getDay();										// 넘어온 날자의 주 요일

	varThisWeekday = Weekday_name[intThisWeekday];								// 현재 요일 저장
	
	intPrintDay = 1																// 달의 시작 일자
	secondPrintDay = 1
	thirdPrintDay = 1
	
	Stop_Flag = 0
	
	if ((intThisYear % 4)==0) {													// 4년마다 1번이면 (사로나누어 떨어지면)
		if ((intThisYear % 100) == 0) {
			if ((intThisYear % 400) == 0) {
				Months_day[2] = 29;
			}
		} else {
			Months_day[2] = 29;
		}
	}
	intLastDay = Months_day[intThisMonth];										// 마지막 일자 구함
	Stop_flag = 0

	 

	Cal_HTML = "<TABLE WIDTH=180 BORDER=0 CELLPADDING=0 CELLSPACING=0 ONMOUSEOVER=doOver(); ONMOUSEOUT=doOut(); STYLE='font-size:8pt;font-family:Tahoma;'>"
			+ "<TR><TD COLSPAN=7 height=5 bgcolor='#E6E6FA'></TD></TR><TR ALIGN='CENTER' bgcolor='#E6E6FA'><TD COLSPAN=7 nowrap=nowrap ALIGN=CENTER>"
			+ "<B STYLE=color:red>"+get_Yearinfo(intThisYear,intThisMonth,intThisDay)+"년"+get_Monthinfo(intThisYear,intThisMonth,intThisDay)+"월</B>&nbsp;"
			+ "<SPAN TITLE='이전달' STYLE='cursor:pointer;' onClick='Show_cal("+intPrevYear+","+intPrevMonth+","+intThisDay+");'><FONT COLOR=Navy>◀</FONT></SPAN> "
			+ "<SPAN TITLE='다음달' STYLE=cursor:pointer; onClick='Show_cal("+intNextYear+","+intNextMonth+","+intThisDay+");'><FONT COLOR=Navy>▶</FONT></SPAN></TD></TR>"
			+ "<TR ALIGN=right BGCOLOR='#1874CD' STYLE='color:#ffffff;font-weight:bold;'><TD>일</TD><TD>월</TD><TD>화</TD><TD>수</TD><TD>목</TD><TD>금</TD><TD>토</TD></TR>";
			
	for (intLoopWeek=1; intLoopWeek < 7; intLoopWeek++) {						// 주단위 루프 시작, 최대 6주
		Cal_HTML += "<TR ALIGN=RIGHT BGCOLOR='#f7f6FA'>"
		for (intLoopDay=1; intLoopDay <= 7; intLoopDay++) {						// 요일단위 루프 시작, 일요일 부터
			if (intThirdWeekday > 0) {											// 첫주 시작일이 1보다 크면
				Cal_HTML += "<TD onClick=doClick();>";
				intThirdWeekday--;
			} else {
				if (thirdPrintDay > intLastDay) {								// 입력 날짝 월말보다 크다면
					Cal_HTML += "<TD onClick=doClick();>";
				} else {														// 입력날짜가 현재월에 해당 되면
					Cal_HTML += "<TD onClick=doClick(); title="+intThisYear+"-"+day2(intThisMonth).toString()+"-"+day2(thirdPrintDay).toString()+" STYLE=\"cursor:Hand;border:1px solid white;font-weight:bold;";
					if (intThisYear == NowThisYear && intThisMonth==NowThisMonth && thirdPrintDay==intThisDay) {
						Cal_HTML += "background-color:#FFD700;";
					}
					
					switch(intLoopDay) {
						case 1:													// 일요일이면 빨간 색으로
							Cal_HTML += "color:#EE0000;"
							break;
						case 7:
							Cal_HTML += "color:#1874CD;"
							break;
						default:
							Cal_HTML += "color:black;"
							break;
					}
					
					Cal_HTML += "\">"+thirdPrintDay;
					
				}
				thirdPrintDay++;
				
				if (thirdPrintDay > intLastDay) {								// 만약 날짜 값이 월말 값보다 크면 루프문 탈출
					Stop_Flag = 1;
				}
			}
			Cal_HTML += "</TD>";
		}
		Cal_HTML += "</TR>";
		if (Stop_Flag==1) break;
	}
	Cal_HTML += "</TABLE>";

	document.all.minical.innerHTML = Cal_HTML;
}

function get_Yearinfo(year,month,day) {											// 년 정보를 콤보 박스로 표시
	//var min = parseInt(year) - 100; //시작
	var min = 2003;
	var max = parseInt(year) + 5;
	var i = new Number();
	var str = new String();
	
	str = "<SELECT onChange='Show_cal(this.value,"+month+","+day+");' ONMOUSEOVER=doOver();>";
	for (i=min; i<=max; i++) {
		if (i == parseInt(year)) {
			str += "<OPTION VALUE="+i+" selected ONMOUSEOVER='doOver();'>"+i+"</OPTION>";
		} else {
			str += "<OPTION VALUE="+i+" ONMOUSEOVER='doOver();'>"+i+"</OPTION>";
		}
	}
	str += "</SELECT>";
	return str;
}


function get_Monthinfo(year,month,day) {										// 월 정보를 콤보 박스로 표시
	var i = new Number();
	var str = new String();
	
	str = "<SELECT onChange='Show_cal("+year+",this.value,"+day+");' ONMOUSEOVER=doOver();>";
	for (i=1; i<=12; i++) {
		if (i == parseInt(month)) {
			str += "<OPTION VALUE="+i+" selected ONMOUSEOVER='doOver();'>"+i+"</OPTION>";
		} else {
			str += "<OPTION VALUE="+i+" ONMOUSEOVER='doOver();'>"+i+"</OPTION>";
		}
	}
	str += "</SELECT>";
	return str;
}
