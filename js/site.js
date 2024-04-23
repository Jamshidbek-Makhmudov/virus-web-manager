$(function(){
	$('.gate_pop_btn').click(function(){
		$('.gate_pop').toggle();
	});
	$('.gate_pop').mouseleave(function(){
		$('.gate_pop').hide();
	});

});

$(function(){
	$('.bg1').click(function(){
	  $('.fd1').css('background', '#f2f8fd');
	  $('.fd2').css('background', '#fff');
	});
	$('.bg2').click(function(){
	  $('.fd2').css('background', '#f2f8fd');
	  $('.fd1').css('background', '#fff');
	});
});

$(function(){
	$(".dropdown img.flag").addClass("flagvisibility");
	 
	$(".dropdown dt a").click(function() {
		var title = $(".dropdown dt a span").attr("title");
		$(".dropdown dt a span").html(title);
		$(".dropdown dd ul").toggle();
	});
	   
	$(".dropdown dd ul li a").click(function() {
	var text = $(this).html();
	$(".dropdown dt a span").html(text);
	$(".dropdown dd ul").hide();
	/* $("#result").html("Selected value is: " + getSelectedValue("sample"));*/
	});
	   
	function getSelectedValue(id) {
	return $("#" + id).find("dt a span.value").html();
	}
	 
	$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if (! $clicked.parents().hasClass("dropdown"))
	$(".dropdown dd ul").hide();
	});
	 
	$(".dropdown img.flag").toggleClass("flagvisibility");
});

$(function(){
	$(".dropdown2 img.flag").addClass("flagvisibility");
	 
	$(".dropdown2 dt a").click(function() {
		var title = $(".dropdown2 dt a span").attr("title");
		$(".dropdown2 dt a span").html(title);
		$(".dropdown2 dd ul").toggle();
	});
	   
	$(".dropdown2 dd ul li a").click(function() {
	var text = $(this).html();
	$(".dropdown2 dt a span").html(text);
	$(".dropdown2 dd ul").hide();
	/* $("#result").html("Selected value is: " + getSelectedValue("sample"));*/
	});
	   
	function getSelectedValue(id) {
	return $("#" + id).find("dt a span.value").html();
	}
	 
	$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if (! $clicked.parents().hasClass("dropdown2"))
	$(".dropdown2 dd ul").hide();
	});
	 
	$(".dropdown2 img.flag").toggleClass("flagvisibility");
});


$(function() {
	$( ".tab>li>a" ).click(function() {
		$(this).parent().addClass("on").siblings().removeClass("on");
		//return false;
	});
});

$(function(){

	//숫자만입력
	$(".only_number").on("propertychange change keyup paste input", function() {
		$(this).val( $(this).val().replace(/[^0-9]/g, ""));
	});

	//날짜입력박스 readonly 처리
	$(".datepicker").datepicker(pickerOpts);
	$(".hasDatepicker").prop("readonly",true);
});
