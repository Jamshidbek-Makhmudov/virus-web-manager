<html>
<head>
<script type="text/javascript" src="../js/jquery-1.8.1.min.js"></script>
<script>
function upload(){
	var formData = new FormData(document.getElementById('frm'));	
	 $.ajax({
	   type:"POST",
	   enctype: 'multipart/form-data',
	   processData:false,
	   contentType:false,
	   url:"./upload_file.php",
	   data:formData,
		xhr: function() { //XMLHttpRequest 재정의 가능
			var xhr = $.ajaxSettings.xhr();
			xhr.upload.onprogress = function(e) { //progress 이벤트 리스너 추가
			var percent = e.loaded * 100 / e.total;
				percent = Math.floor(percent);
				$("#result").html(percent.toString()+"%");
			};
			return xhr;
	   },
	   success:function(data){
			//console.log("success") 
			$("#result").html(data);
	   }, 
		error: function (data, status, e) 
		{ 
			$("#result").html(e);
		} 
	});
}
function upload2(){
	
	var file = $('#upload_file').get(0).files.item(0); // instance of File

	//var formData = new FormData(document.getElementById('frm'));	

	$.ajax({
	  type: 'POST',
	  url: 'upload_file.php',
	  data: file,
	  contentType: 'application/my-binary-type', // set accordingly
	  processData: false,
		xhr: function() { //XMLHttpRequest 재정의 가능
			var xhr = $.ajaxSettings.xhr();
			xhr.upload.onprogress = function(e) { //progress 이벤트 리스너 추가
			var percent = e.loaded * 100 / e.total;
				percent = Math.floor(percent);
				$("#result").html(percent.toString()+"%");
			};
			return xhr;
	   },
		success:function(data){
			//console.log("success") 
			$("#result").html(data);
	   }, 
		error: function (data, status, e) 
		{ 
			$("#result").html(e);
		} 
	});
}
</script>
</head>
<body>
	<form name='frm' id='frm' enctype="multipart/form-data" method='post' action='./upload_file.php'>
		<input type='file' name='upload_file' id='upload_file'><BR>
		v_wvcs_seq : <input type='text' name='v_wvcs_seq' value='1'>
	<input type='submit' value='form submit' onclick='document.frm.submit()'>
		<input type='button' value='ajax submit'  onclick='upload()'>
	<input type='button' value='binary submit' onclick='upload2()'>
		<div id='result' style='maring-top:20px;'></div>
	<form>
</body>
</html>