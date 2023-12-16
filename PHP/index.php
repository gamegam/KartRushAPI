<?php

$apiEndpoint = 'https://open.api.nexon.com/kartrush/v1/id';
$apiKey = 'API KEY';//API키를 입력하세요
$racerName = "레이서 닉네임을 작성하십시오(UI)일경우 UI을 여기다 출력하세요!";
$url = "$apiEndpoint?racer_name=" . urlencode($racerName);
$options = [
	'http' => [
		'header' => "accept: application/json\r\n" . "x-nxopen-api-key: $apiKey",
	],
];
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
if ($response === FALSE){//해당 라이더가 존재하지 않거나 API키가 유효하지 않을경우
	echo "해당 라이더를 찾을 수 없습니다.";
	return;
}
$data = json_decode($response, true);
if (isset($data['ouid_info'][0]['ouid'])){
	$ouid = $data['ouid_info'][0]['ouid'];//해당 라이더의 식별자을 불러옵니다.
	$lv = $data['ouid_info'][0]['racer_level'];//해당 라이더의 레벨을 불러옵니다.
	$date = $data['ouid_info'][0]["racer_date_create"];//첫 가입때 날짜를 불러옵니다.
	$user = 'https://open.api.nexon.com/kartrush/v1/user/basic';//넥슨 오픈API참고
	$url = "$user?ouid=" . urlencode($ouid);
	$response = file_get_contents($url, false, $context);
	$data = json_decode($response, true);
	$nick = $data["racer_name"] ?? "준비중";//라이더 닉네임을 불러오고 그렇지 않을경우 준비중이라고 출력합니다.
	$date1 = $data["racer_date_create"];//첫 가입 날짜을 불러옴(저거위랑 똑같습니다)
	$join = new DateTime($date1);
	$A1 = ($join->format("A") == "PM") ? '오후' : '오전';
	$date1 = $join->format("Y년 m월 d일 $A1 h시 i분 s초");
	$date2 = $data["racer_date_last_login"] ?? null;
	$login = new DateTime($date2);
	$A2 = ($login->format("A") == "PM") ? '오후' : '오전';
	$date2 = $login->format("Y년 m월 d일 $A2 h시 i분 s초");
	$date3 = $data["racer_date_last_logout"] ?? "오류코드: ". var_dump($data);
	$loginout = new DateTime($date3);
	$A3 = ($loginout->format("A") == "PM") ? '오후' : '오전';
	$date3 = $loginout->format("Y년 m월 d일 $A3 h시 i분 s초");
	$titlelink = 'https://open.api.nexon.com/kartrush/v1/user/title_equipment';
	$response = file_get_contents($titlelink, false, $context);
	$titledata = json_decode($response, true);
	$title = $titledata["title_equipment"][0]["title_name"] ?? "없음!";//해당 라이더가 타이틀이 없을경우 없음!이라는걸 출력합니다.
	echo $lv;
	/**
	 * 이런식으로 하면 해당 플레이의 레벨을 불러옵니다.
	 * 줄바꿈을 원하실경우
	 */

	echo $lv."<br>";
	echo "줄바꿈";
	/**
	 * 이런식이고 참 쉽쥬?
	 */
}
?>
