<?php
/*
* Description : kakaobank ldap 연동 임직원 정보 동기화 배치
*/
ini_set('memory_limit', '1024M');

if (strpos($_SERVER['windir'], "Windows") || strpos($_SERVER['WINDIR'], "Windows")) {
	$_server_path = "D:/DPTWebManager/htdocs";
} else {
	$_server_path = "/DPT/DPTWebManager/htdocs";
}

$_site_path = "wvcs";

include $_server_path . "/" . $_site_path ."/lib/wvcs_config.inc"; 
include $_server_path . "/" . $_site_path ."/lib/lib.inc"; 
include $_server_path . "/" . $_site_path ."/inc/function.inc"; 
include $_server_path . "/" . $_site_path ."/api/common.php"; 
include $_server_path . "/" . $_site_path ."/api/kabang/ldap.php"; 

//배치 로그 기록
function write_batch_log($result,$msg){

	$log_div = "emp_sync_batch";

	writeSystemLog($log_div,$result,$msg);

	$_result = array("result"=>$result,"msg"=>$msg);
	
	echo json_encode($_result);
	exit;
}

if(gethostname()=="dataprotecs"){
	//**test data
	$data = array(
		array("user_name"=>"서형석","user_id"=>"jorba.0","dept_name"=>"정보보호기술","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;정보보호기술","dept_code_path"=>"","status"=>"9")
		, array("user_name"=>"양기환","user_id"=>"reggie.williams","dept_name"=>"감사1","dept_code"=>"","dept_name_path"=>"카카오뱅크;감사;감사1","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"신재주","user_id"=>"logan.elliott","dept_name"=>"감사2","dept_code"=>"","dept_name_path"=>"카카오뱅크;감사;감사2","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"신태원","user_id"=>"jaime.shaw","dept_name"=>"경영고문","dept_code"=>"","dept_name_path"=>"카카오뱅크;경영고문","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정홍자","user_id"=>"glen.richards","dept_name"=>"기타비상무이사","dept_code"=>"","dept_name_path"=>"카카오뱅크;기타비상무이사","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"설세호","user_id"=>"billy.williamson","dept_name"=>"노동조합","dept_code"=>"","dept_name_path"=>"카카오뱅크;노동조합","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"안재경","user_id"=>"reed.wilkinson","dept_name"=>"사외이사","dept_code"=>"","dept_name_path"=>"카카오뱅크;사외이사","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"손재성","user_id"=>"aubrey.brown","dept_name"=>"금융사기대응","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;금융소비자보호;소비자보호;금융사기대응","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정영옥","user_id"=>"jaime.holland","dept_name"=>"소비자보호기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;금융소비자보호;소비자보호;소비자보호기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정영환","user_id"=>"ash.walker","dept_name"=>"FDS","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;금융소비자보호;소비자보호;FDS","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"김진혁","user_id"=>"caden.fisher","dept_name"=>"건전성관리","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;리스크;건전성관리","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"배광준","user_id"=>"kerry.macdonald","dept_name"=>"리스크기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;리스크;리스크기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"최승욱","user_id"=>"leslie.perry","dept_name"=>"모형개발운영","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;리스크;신용리스크모델링;모형개발운영","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"문주연","user_id"=>"eli.kennedy","dept_name"=>"모형기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;리스크;신용리스크모델링;모형기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"노인철","user_id"=>"robin.howard","dept_name"=>"신용리스크정책","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;리스크;신용리스크정책","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"유성우","user_id"=>"willy.lane","dept_name"=>"전사리스크관리","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;리스크;전사리스크관리","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"사공유성","user_id"=>"aa.taylor.pearson","dept_name"=>"대외협력","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;대외협력","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"조창욱","user_id"=>"aa.justice.marsh","dept_name"=>"PA","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;대외협력;PA","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"황승환","user_id"=>"aa.glen.lowe","dept_name"=>"자금운용","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;자금운용","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"백영수","user_id"=>"aa.ali.morgan","dept_name"=>"자금","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;자금운용;자금","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"신원우","user_id"=>"aa.ash.khan","dept_name"=>"외화자금","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;자금운용;자금;외화자금","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"9")
		, array("user_name"=>"남혜경","user_id"=>"aa.jude.mason","dept_name"=>"자금관리","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;자금운용;자금;자금관리","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"최태은","user_id"=>"aa.gene.morris","dept_name"=>"자금기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;자금운용;자금;자금기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"서기우","user_id"=>"aa.franky.reid","dept_name"=>"내부회계관리","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;재무관리;내부회계관리","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임명희","user_id"=>"aa.harper.sutton","dept_name"=>"세무","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;재무관리;세무","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"추주원","user_id"=>"aa.brook.price","dept_name"=>"재무결제","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;재무관리;재무결제","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"봉철수","user_id"=>"aa.kit.mendoza","dept_name"=>"재무기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;재무관리;재무기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"조인경","user_id"=>"aa.alexis.gillespie","dept_name"=>"회계","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;재무;재무관리;회계","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"9")
		, array("user_name"=>"한미나","user_id"=>"aa.sidney.herman","dept_name"=>"글로벌","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;전략;글로벌","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남정욱","user_id"=>"aa.gabby.woodward","dept_name"=>"기획조정","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;전략;기획조정","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"문용남","user_id"=>"aa.aubrey.robertson","dept_name"=>"전략","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;전략;전략","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"성힘찬","user_id"=>"aa.mel.buck","dept_name"=>"투자","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;전략;투자","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"표한결","user_id"=>"bb.jordan.knapp","dept_name"=>"PR","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;홍보;PR","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"성나라우람","user_id"=>"bb.kai.solis","dept_name"=>"IR","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영전략;IR","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"배힘찬","user_id"=>"bb.terry.guthrie","dept_name"=>"365고객지원","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;업무지원;365고객지원","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"허나길","user_id"=>"bb.jude.harrell","dept_name"=>"금융거래정보제공","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;업무지원;금융거래정보제공","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남궁으뜸","user_id"=>"bb.brice.phelps","dept_name"=>"수신업무","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;업무지원;수신업무","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"제갈샘","user_id"=>"bb.brook.wheeler","dept_name"=>"압류","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;업무지원;압류","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"손나라우람","user_id"=>"bb.ali.foreman","dept_name"=>"외환지원","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;업무지원;외환지원","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"손믿음","user_id"=>"bb.lynn.roberts","dept_name"=>"인증운영","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;업무지원;인증운영","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"복샘","user_id"=>"bb.danny.haney","dept_name"=>"CS운영","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS운영","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"성으뜸","user_id"=>"bb.addison.frederick","dept_name"=>"전월세운영","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS운영;전월세운영","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"사공한결","user_id"=>"bb.ash.short","dept_name"=>"주담대운영","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS운영;주담대운영","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"조샘","user_id"=>"bb.lee.cabrera","dept_name"=>"CS지원","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS지원","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남믿음","user_id"=>"bb.robin.morgan","dept_name"=>"CS플랫폼기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS지원;CS플랫폼기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"장샘","user_id"=>"bb.jesse.bentley","dept_name"=>"RPA","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS지원;RPA","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"사공미르","user_id"=>"bb.phoenix.ruiz","dept_name"=>"CS채널","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS채널","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"배한결","user_id"=>"bb.denny.norton","dept_name"=>"CS기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS채널;CS기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"백빛가람","user_id"=>"bb.jessie.maxwell","dept_name"=>"CS정책","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS채널;CS정책","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"문우람","user_id"=>"bb.blake.blake","dept_name"=>"CS챗봇기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;고객서비스;CS채널;CS챗봇기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"백나라우람","user_id"=>"cc.cameron.chavez","dept_name"=>"구매","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;매니지먼트;구매","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남궁빛가람","user_id"=>"cc.reuben.pearson","dept_name"=>"인사","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;매니지먼트;인사","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"유빛가람","user_id"=>"cc.reuben.ryan","dept_name"=>"Culture","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;매니지먼트;인사;Culture","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"서다운","user_id"=>"cc.ewan.marsh","dept_name"=>"People","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;매니지먼트;인사;People","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"표나라우람","user_id"=>"cc.davian.padilla","dept_name"=>"Rewards","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;매니지먼트;인사;Rewards","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"노믿음","user_id"=>"cc.charlie.reed","dept_name"=>"총무","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;매니지먼트;총무","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"서달","user_id"=>"cc.dane.howard","dept_name"=>"ESG","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;경영지원;ESG","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"탁우람","user_id"=>"cc.rowan.owen","dept_name"=>"기술기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;기술기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"하샘","user_id"=>"cc.blair.bennett","dept_name"=>"기술XR","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;기술기획;기술XR","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"표다운","user_id"=>"cc.danny.jordan","dept_name"=>"IT변경관리","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;기술기획;IT변경관리","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"하빛가람","user_id"=>"cc.mel.wallace","dept_name"=>"AA개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;AA개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정이레","user_id"=>"cc.sam.murphy","dept_name"=>"IT자체상시감사","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;IT자체감사;IT자체상시감사","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"전누리","user_id"=>"cc.quinn.doyle","dept_name"=>"IT자체정기감사","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;IT자체감사;IT자체정기감사","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"풍소리","user_id"=>"cc.jamie.phillips","dept_name"=>"경영플랫폼","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;TX;경영플랫폼","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"장하나","user_id"=>"cc.gail.john","dept_name"=>"Env기술","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;기술전략;TX;Env기술","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"홍나라","user_id"=>"cc.glenn.jenkins","dept_name"=>"공공기업금융개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;금융정보개발;공공기업금융개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남궁별","user_id"=>"dd.gabe.perry","dept_name"=>"종합재무리스크개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;금융정보개발;종합재무리스크개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"양소리","user_id"=>"dd.jackie.cooke","dept_name"=>"종합정보개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;금융정보개발;종합정보개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"허보라","user_id"=>"dd.addison.hayes","dept_name"=>"컴플라이언스개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;금융정보개발;컴플라이언스개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"예새론","user_id"=>"dd.ashley.foster","dept_name"=>"회계자금개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;금융정보개발;회계자금개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"서나래","user_id"=>"dd.brett.chapman","dept_name"=>"금융데이터엔지니어링","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;빅데이터;금융데이터엔지니어링","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"안초롱","user_id"=>"dd.aaren.watson","dept_name"=>"데이터XP","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;빅데이터;데이터XP","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임솔","user_id"=>"dd.blair.webb","dept_name"=>"모던데이터엔지니어링","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;빅데이터;모던데이터엔지니어링","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"안은비","user_id"=>"dd.reggie.taylor","dept_name"=>"모던데이터플랫폼","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;빅데이터;모던데이터플랫폼","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"탁새롬","user_id"=>"dd.maddox.robinson","dept_name"=>"네트워크아키텍처","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;인프라;네트워크아키텍처","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"복푸름","user_id"=>"dd.skylar.bailey","dept_name"=>"데이터센터","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;인프라;데이터센터","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"윤빛나","user_id"=>"dd.kerry.andrews","dept_name"=>"시스템아키텍처1","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;인프라;시스템아키텍처1","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"손보라","user_id"=>"dd.angel.hudson","dept_name"=>"시스템아키텍처2","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;인프라;시스템아키텍처2","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"문은비","user_id"=>"dd.billy.jackson","dept_name"=>"컨테이너플랫폼","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;컨테이너플랫폼","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"황그루","user_id"=>"dd.bret.day","dept_name"=>"DA","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;DB기술;DA","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"송별","user_id"=>"dd.jess.baker","dept_name"=>"MySQL","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;DB기술;MySQL","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"김슬기","user_id"=>"dd.jess.haynes","dept_name"=>"Oracle","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;신뢰기술;DB기술;Oracle","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"이여름","user_id"=>"dd.angel.blackwell","dept_name"=>"뱅킹아키","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;엔지니어링기술;신뢰성엔지니어링;뱅킹아키","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"권나무","user_id"=>"dd.kit.brennan","dept_name"=>"서비스아키","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;엔지니어링기술;신뢰성엔지니어링;서비스아키","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임하얀","user_id"=>"dd.clem.barron","dept_name"=>"기반기술엔지니어링","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;엔지니어링기술;플랫폼엔지니어링;기반기술엔지니어링","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"홍온","user_id"=>"dd.avery.mcgee","dept_name"=>"솔루션엔지니어링","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;엔지니어링기술;플랫폼엔지니어링;솔루션엔지니어링","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임아라","user_id"=>"dd.lane.hood","dept_name"=>"클라우드엔지니어링","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;엔지니어링기술;플랫폼엔지니어링;클라우드엔지니어링","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"하나비","user_id"=>"dd.bev.morse","dept_name"=>"DevOps엔지니어링","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;엔지니어링기술;플랫폼엔지니어링;DevOps엔지니어링","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"백새론","user_id"=>"ee.harper.everett","dept_name"=>"네트워크보안","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;정보보호기술;네트워크보안","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임온","user_id"=>"ee.glenn.estrada","dept_name"=>"보안기술아키텍처","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;정보보호기술;보안기술아키텍처","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"양가을","user_id"=>"ee.bailey.farmer","dept_name"=>"시스템보안","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;정보보호기술;시스템보안","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남광","user_id"=>"ee.rene.johnson","dept_name"=>"어플리케이션보안","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;정보보호기술;어플리케이션보안","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남훈","user_id"=>"ee.denny.rice","dept_name"=>"정보보호기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;PIS;정보보호기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"배웅","user_id"=>"ee.marley.strickland","dept_name"=>"정보보호매니지먼트","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;PIS;정보보호매니지먼트","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"성철","user_id"=>"ee.shay.dennis","dept_name"=>"정보보호플랫폼","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;기술;정보보호;PIS;정보보호플랫폼","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정건","user_id"=>"ee.drew.randall","dept_name"=>"광고사업","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;신사업;광고/제휴사업;광고사업","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"한철","user_id"=>"ee.riley.norman","dept_name"=>"제휴사업","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;신사업;광고/제휴사업;제휴사업","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"이웅","user_id"=>"ee.haiden.mcknight","dept_name"=>"신사업기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;신사업;신사업;신사업기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"류웅","user_id"=>"ee.bret.gillespie","dept_name"=>"신사업추진","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;신사업;신사업;신사업추진","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"조철","user_id"=>"ee.lane.cleveland","dept_name"=>"비즈플랫폼개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;신사업;신사업기술;비즈플랫폼개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"풍광","user_id"=>"ee.kai.peck","dept_name"=>"신사업플랫폼개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;신사업;신사업기술;신사업플랫폼개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"이혁","user_id"=>"ee.phoenix.potts","dept_name"=>"개인사업자개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;개인사업자캠프;개인사업자개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"유건","user_id"=>"ee.nicky.sykes","dept_name"=>"개인사업자서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;개인사업자캠프;개인사업자서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정웅","user_id"=>"ee.steff.parker","dept_name"=>"결제서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;결제캠프;결제서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"최호","user_id"=>"ee.ashley.mcdonald","dept_name"=>"결제서비스개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;결제캠프;결제서비스개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"배혁","user_id"=>"ee.will.mcgowan","dept_name"=>"결제코어개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;결제캠프;결제코어개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"표호","user_id"=>"ee.reggie.williams","dept_name"=>"고객개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;고객인증캠프;고객개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"허철","user_id"=>"ee.logan.elliott","dept_name"=>"고객인증서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;고객인증캠프;고객인증서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"황건","user_id"=>"ee.jaime.shaw","dept_name"=>"고객인증채널개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;고객인증캠프;고객인증채널개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"윤혁","user_id"=>"ee.glen.richards","dept_name"=>"인증기술","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;고객인증캠프;인증기술","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"홍철","user_id"=>"ee.billy.williamson","dept_name"=>"인증사업","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;고객인증캠프;인증사업","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"한철","user_id"=>"ee.reed.wilkinson","dept_name"=>"담보여신개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;담보여신캠프;담보여신개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"이훈","user_id"=>"ee.aubrey.brown","dept_name"=>"여신제도기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;담보여신캠프;여신제도기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"예훈","user_id"=>"ff.jaime.holland","dept_name"=>"전월세서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;담보여신캠프;전월세서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"예혁","user_id"=>"ff.ash.walker","dept_name"=>"주담대서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;담보여신캠프;주담대서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"황보건","user_id"=>"ff.caden.fisher","dept_name"=>"브랜드디자인","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;디자인;브랜드디자인","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"신건","user_id"=>"ff.kerry.macdonald","dept_name"=>"프로덕트디자인","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;디자인;프로덕트디자인","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"예혁","user_id"=>"ff.leslie.perry","dept_name"=>"그로스마케팅","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;마케팅;그로스마케팅","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"고웅","user_id"=>"ff.eli.kennedy","dept_name"=>"브랜드마케팅","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;마케팅;브랜드마케팅","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"강웅","user_id"=>"ff.robin.howard","dept_name"=>"서비스마케팅","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;마케팅;서비스마케팅","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"홍철","user_id"=>"ff.willy.lane","dept_name"=>"콘텐츠마케팅","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;마케팅;콘텐츠마케팅","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임상","user_id"=>"ff.taylor.pearson","dept_name"=>"수신개발1","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;수신캠프;수신개발1","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"봉성","user_id"=>"ff.justice.marsh","dept_name"=>"수신개발2","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;수신캠프;수신개발2","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정성","user_id"=>"ff.glen.lowe","dept_name"=>"수신개발3","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;수신캠프;수신개발3","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"손재","user_id"=>"ff.ali.morgan","dept_name"=>"수신서비스1","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;수신캠프;수신서비스1","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"권재","user_id"=>"ff.ash.khan","dept_name"=>"수신서비스2","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;수신캠프;수신서비스2","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"사공성","user_id"=>"ff.jude.mason","dept_name"=>"수신제도기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;수신캠프;수신제도기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"박재","user_id"=>"ff.gene.morris","dept_name"=>"대출비교서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;여신플랫폼캠프;대출비교서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"오현","user_id"=>"ff.franky.reid","dept_name"=>"신용서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;여신플랫폼캠프;신용서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임리","user_id"=>"ff.harper.sutton","dept_name"=>"여신관리센터","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;여신플랫폼캠프;여신관리;여신관리센터","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"최상","user_id"=>"gg.brook.price","dept_name"=>"여신플랫폼개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;여신플랫폼캠프;여신플랫폼개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"사공상","user_id"=>"gg.kit.mendoza","dept_name"=>"외환개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;외환캠프;외환개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"강린","user_id"=>"gg.alexis.gillespie","dept_name"=>"외환서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;외환캠프;외환서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"한상","user_id"=>"gg.sidney.herman","dept_name"=>"투자개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;투자캠프;투자개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"설란","user_id"=>"gg.gabby.woodward","dept_name"=>"투자서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;투자캠프;투자서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"안란","user_id"=>"gg.aubrey.robertson","dept_name"=>"펀드","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;투자캠프;펀드","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"송란","user_id"=>"gg.mel.buck","dept_name"=>"뱅킹DQA","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;홈캠프;뱅킹DQA","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정리","user_id"=>"gg.jordan.knapp","dept_name"=>"웹서비스개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;홈캠프;웹서비스개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"정리","user_id"=>"gg.kai.solis","dept_name"=>"홈뱅킹개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;홈캠프;홈뱅킹개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"허진","user_id"=>"gg.terry.guthrie","dept_name"=>"홈서비스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;홈캠프;홈서비스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"봉현","user_id"=>"gg.jude.harrell","dept_name"=>"홈서비스개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;홈캠프;홈서비스개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"권설","user_id"=>"gg.brice.phelps","dept_name"=>"홈QA","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;캠프;홈캠프;홈QA","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"윤재","user_id"=>"gg.brook.wheeler","dept_name"=>"연구개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;기술연구소;연구개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남상","user_id"=>"gg.ali.foreman","dept_name"=>"연구전략","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;기술연구소;연구전략","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"강란","user_id"=>"gg.lynn.roberts","dept_name"=>"AI데이터사이언스","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;AI개발;AI데이터사이언스","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"남재","user_id"=>"gg.danny.haney","dept_name"=>"AI코어개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;AI개발;AI코어개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"최재","user_id"=>"gg.addison.frederick","dept_name"=>"AI프로덕트개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;AI개발;AI프로덕트개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"홍란","user_id"=>"gg.ash.short","dept_name"=>"AICC개발","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;AI개발;AICC개발","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"백설","user_id"=>"gg.lee.cabrera","dept_name"=>"AI사업","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;AI사업","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"임란","user_id"=>"gg.robin.morgan","dept_name"=>"AI플랫폼","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;부대표;AI;AI플랫폼","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"허재","user_id"=>"gg.jesse.bentley","dept_name"=>"자금세탁방지기획","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;컴플라이언스;자금세탁방지;자금세탁방지기획","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"황무열","user_id"=>"gg.phoenix.ruiz","dept_name"=>"자금세탁방지운영1","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;컴플라이언스;자금세탁방지;자금세탁방지운영1","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"심병헌","user_id"=>"gg.denny.norton","dept_name"=>"자금세탁방지운영2","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;컴플라이언스;자금세탁방지;자금세탁방지운영2","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"설동건","user_id"=>"gg.jessie.maxwell","dept_name"=>"AML지원","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;컴플라이언스;자금세탁방지;AML지원","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"문오성","user_id"=>"gg.blake.blake","dept_name"=>"법무","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;컴플라이언스;준법감시/법무;법무","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"제갈광조","user_id"=>"gg.cameron.chavez","dept_name"=>"준법감시","dept_code"=>"","dept_name_path"=>"카카오뱅크;대표;컴플라이언스;준법감시/법무;준법감시","dept_code_path"=>"","project_name"=>"","project_code"=>"","status"=>"1")
		, array("user_name"=>"캘린더","user_id"=>"hh.lynn.chavez","dept_name"=>"카카오텔러팀","dept_code"=>"","dept_name_path"=>"","dept_code_path"=>"","project_name"=>"모바일뱅크 텔러관리_나이스신용정보","project_code"=>"2022011130001","status"=>"1")
		, array("user_name"=>"노라죠","user_id"=>"hh.blake.blake","dept_name"=>"카카오텔러팀","dept_code"=>"","dept_name_path"=>"","dept_code_path"=>"","project_name"=>"모바일뱅크 텔러관리_고려신용정보","project_code"=>"2022011130001","status"=>"1")
		, array("user_name"=>"가린다","user_id"=>"hh.cameron.chavez","dept_name"=>"카카오프로젝트팀","dept_code"=>"","dept_name_path"=>"","dept_code_path"=>"","project_name"=>"프로젝트 하나둘셋","project_code"=>"2023011130001","status"=>"1")
		, array("user_name"=>"모간 차베즈","user_id"=>"hh.morgan.chavez","dept_name"=>"카카오프로젝트팀","dept_code"=>"","dept_name_path"=>"","dept_code_path"=>"","project_name"=>"프로젝트 너랑나랑","project_code"=>"2023011130001","status"=>"1")
	);

}else{
	//실제운영
	$ldap = new ldap\LDAP;
	$return_result = $ldap->get_users();

	$result = $return_result['result'];
	$msg = $return_result['msg'];
	$data = $return_result['data'];

	if($result=='fail') write_batch_log('fail',$msg);
}


$create_date = date("YmdHis");

function fn_insert($_data){
	global $wvcs_dbcon;

	$_user_name = $_data[user_name];
	$_user_id = $_data[user_id];
	$_dept_name = $_data[dept_name];
	$_dept_code = $_data[dept_code];
	$_dept_name_path = $_data[dept_name_path];
	$_dept_code_path = $_data[dept_code_path];
	$_project_name = $_data[project_name];
	$_project_code = $_data[project_code];
	$_status = $_data[status];     
	$_create_time = $_data[create_time];	//최초등록일시 ex.20170131073614Z
	$_update_time = $_data[update_time];	//마지막수정일시 ex.20190131073614Z		

	if (empty($_dept_name_path)) {
		if (!empty($_dept_name) && !empty($_project_name)) {
			$_dept_name_path = "{$_dept_name};{$_project_name}";
		}
	}

	$work_yn = ($_status == '1') ? "Y":"N";

	$sql = "
		DELETE FROM tb_emp_kakaobank WHERE emp_id = '{$_user_id}';
		INSERT INTO tb_emp_kakaobank (
			emp_name,emp_id,dept_name,dept_code,dept_name_path,dept_code_path,status,create_date,project_name,project_code
		) VALUES (
			'".aes_256_enc($_user_name)."','{$_user_id}',N'{$_dept_name}','{$_dept_code}',N'{$_dept_name_path}','{$_dept_code_path}'
			,'{$_status}','{$create_date}',N'{$_project_name}','{$_project_code}'
		);
		UPDATE tb_employee SET work_yn = '{$work_yn}' WHERE emp_no = '{$_user_id}';
	";

	//echo $sql;
	//writeLog($sql);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);
	$rows_affected = @sqlsrv_rows_affected( $result);

	return $rows_affected;
}

function fn_insert_department() {
	global $wvcs_dbcon;

	$sql = "EXEC dbo.up_InsertKakaobankDepartment";

	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	$insert_count = 0;

	if ($result !== false) {
		$insert_count = @sqlsrv_get_field( $result, 0);
	}
	
	return $insert_count;
}

if(is_array($data)){
	$sync_count = 0;

	for ($i=0; $i< count($data); $i++) {

		$_data = $data[$i];
		$rows_affected = fn_insert($_data);

		if ($rows_affected >= 1) {
			$sync_count++;
		} else {
			//에러난 경우 한번 더 처리..
			$rows_affected = fn_insert($_data);

			if ($rows_affected >= 1) {
				$sync_count++;
			} else {
				$error[] =$_data;
			}
		}
	}

	if (count($error) == 0) {
		$count = fn_insert_department();
	}
}

//등록 실패
if (count($error) > 0) {
	for($i = 0 ; $i < count($error) ; $i++) $error[$i]['user_name'] = "***";	//이름값 숨김처리.

	$msg = array("result"=>number_format($sync_count)." completed with ".count($error)." errors" ,"data"=>$error);
	$msg = json_encode($msg,true);
	write_batch_log('fail',$msg);
}

//성공..
$msg = number_format($sync_count)." completed with 0 errors ";
write_batch_log('success',$msg);
?>