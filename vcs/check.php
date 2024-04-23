

<!doctype html>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<!-- Jquery AJAXS -->
    <script>
		var timer;
		function testfrm () {
			timer = setInterval(requestGet, 4000);
			return;
		};
    	
    	/* 이벤트 함수 정의 */
		var level = 0;
		var serverVCSvertion = "00000000000000";

    	function requestGet(){
    		
			var xhr = new XMLHttpRequest();
    		var url = "http://localhost:58736/";    
    		xhr.open("POST", url , true);

 			var params_chk = 'Action=CheckInstall&Version='+serverVCSvertion;
			var params_rdy = 'Action=CheckReady';
			var params_opn = 'Action=CheckNDI';
			
			var repmsg = "";
			var arrmsg;
			var arrcnt;
    		 		
			//xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
			if(level == 0)  { xhr.send(params_chk); }
			if(level == 1)  { xhr.send(params_rdy); }
			if(level == 2)  { xhr.send(params_opn); }

    		xhr.onreadystatechange = function() {
    			if (xhr.readyState == 4) { 
    				if (xhr.status == 200 || xhr.status == 201){
						
						repmsg = xhr.responseText;
						arrmsg = repmsg.split(",");
						arrcnt = arrmsg.length;
    					
						if((level == 0) && (arrcnt > 2) && (arrmsg[0] == "Check Ok")) { 
								document.getElementById('divtest').innerHTML = "<b>Version:</b>" + arrmsg[1];
								level = level + 1; 
								return;
						}
						
						if((level == 0) && (arrcnt > 2) && (arrmsg[0] == "Version Chk")) { 
								document.getElementById('divtest').innerHTML = "<b>Version is deferent Current VCSVersion=[" + arrmsg[1] + "]<br> Please Waiting AutoUpgrade!!<br> VCS Program Notice[" + arrmsg[2] + "]</b> <br><input type=\"button\" value=\"Install Download\" onClick=\"location.href='http://www.daum.net'\">";
								return;
						}
						
						if((level == 1) && (arrcnt > 2) && (arrmsg[0] == "Ready Ok")) { 
								clearInterval(timer);
								document.getElementById('divtest').innerHTML = "<b>Window Update Check</b>::[" + arrmsg[1] + "]<br> <b>VCS Check</b>::[" + arrmsg[2] + "]";
								window.open("https://112.219.222.170/wvcs/vcs/NDI_view.php", "VDI Connected", "location=yes,height=570,width=520,scrollbars=yes,status=yes");
								window.open("about:blank","_self").close();
								level = level + 1;
								return;
						} 

						if((level == 1) && (arrcnt > 2) && (arrmsg[0] == "Ready Chk")) {
								document.getElementById('divtest').innerHTML = "<b>Window Update Check</b>::" + arrmsg[1] + "<br> <b>VCS Check</b>::" + arrmsg[2];
								return;
						}
						
						if((level == 2) && (arrcnt > 2) && (arrmsg[0] == "NDI Ok"))   { 
							 clearInterval(timer); 
							 window.open("about:blank","_self").close();  
						} else {
							document.getElementById('divtest').innerHTML = "<b>Return Message Parsing Fail !!<b>"; 
						}
							
    				}
    				else { 
						document.getElementById('divtest').innerHTML = "<b>NOT INSTALL- First this Program Download and install please!! </b><input type=\"button\" value=\"Install Download\" onClick=\"location.href='http://www.daum.net'\"> <br> <input type=\"button\" value=\"다시체크\" onClick=\"location.reload();\">";   				
    				}					
    			} else {
					document.getElementById('divtest').innerHTML = "<b>NOT INSTALL- First this Program Download and install please!! </b><br><input type=\"button\" value=\"Install Download\" onClick=\"location.href='http://www.daum.net'\"> <br> <input type=\"button\" value=\"다시체크\" onClick=\"location.reload();\">"; 
				}

    		}

							
    	};

    	
    	/* 이벤트 함수 정의 */
    	function requestPost(){
    		console.log("");
    		console.log("[requestPost] : [start]");    		
    		console.log("");
    		
    		// url 및 전송 데이터 선언
    		var url = "google.com";
    		    		    		    		
    		// XMLHttpRequest 객체 생성 및 요청 수행
    		var xhr = new XMLHttpRequest();
    		xhr.open("POST", url, true);
    		
    		//xhr.onreadystatechange = CallbackFunction; //콜백 함수 지정해서 처리 가능    		
    		xhr.onreadystatechange = function() {
    			if (xhr.readyState == 4) {
    				if (xhr.status == 200 || xhr.status == 201){
    					console.log("[status] : " + xhr.status);
    					console.log("[response] : " + "[success]");    				   				    				
    					console.log("[response] : " + xhr.responseText);
    					console.log("");        				
    				}
    				else {
    					console.log("[status] : " + xhr.status);
    					console.log("[response] : " + "[fail]");    				   				    				
    					console.log("[response] : " + xhr.responseText);
    					console.log("");        				
    				}    				
    			}    			
    		}
    		xhr.send(null); //post 쿼리 파람 방식일때 null    					    		    		
									
    	};

    	
    	
    	/* 이벤트 함수 정의 */
    	function requestPostBodyJson(){
    		console.log("");
    		console.log("[requestPostBodyJson] : [start]");    		
    		console.log("");
    		
    		// url 및 전송 데이터 선언
    		var url = "http://jsonplaceholder.typicode.com/posts";
    		
    		// 전송 json 데이터 선언
    		var jsonData = {"userId" : 1 , "id" : 1};
    		    		    		    		
    		var xhr = new XMLHttpRequest();
    		xhr.open("POST", url, true);
    		    		
    		console.log("[request data] : " + JSON.stringify(jsonData));

    		xhr.onreadystatechange = function() {
    			if (xhr.readyState == 4) {
    				if (xhr.status == 200 || xhr.status == 201){
    					console.log("[status] : " + xhr.status);
    					console.log("[response] : " + "[success]");    				   				    				
    					console.log("[response] : " + xhr.responseText);
    					console.log("");        				
    				}
    				else {
    					console.log("[status] : " + xhr.status);
    					console.log("[response] : " + "[fail]");    				   				    				
    					console.log("[response] : " + xhr.responseText);
    					console.log("");        				 
    				}						    				
    			}    			
    		}
    		xhr.setRequestHeader("Content-Type", "application/json");    		
    		xhr.send(JSON.stringify(jsonData)); //post body json 방식 일때    					    		    		
									
    	};
    	
    </script>

<title>onload</title>

</head>

<body onload="testfrm();">


<div id="divtest">VDI Forwording ... Check Message</div>
<iframe id="ifrm"  frameborder="0" width="600" height="300" marginwidth="0" marginheight="0" scrolling="yes">
</iframe>
</body>

</html>
