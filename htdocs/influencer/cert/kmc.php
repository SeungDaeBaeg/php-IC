<?php

header("Content-Type: text/html; charset=UTF-8");
header('Expires: 0');
    
try {

    $ICERTSecu      = './ICERTSecu';
    $mode           = $_REQUEST['mode'] ?? '';

    switch($mode) {
        default:
        $cpId           = 'LINM1001';
        $urlCode        = '032001';
        $certNum        = uniqid();
        $date           = date('YmdHis');
        $certMet        = 'M';
        $extendVar      = '0000000000000000';

        $tr_url         = "https://".$_SERVER['HTTP_HOST']."/influencer/cert/kmc.php?mode=auth_complete";    // 본인인증 결과수신 URL

        $redirect       = $_POST['redirect'] ?? '';
        $app            = $_POST['app'] ?? '';
        $type           = $_POST['type'] ?? '';

        $plusInfo       = $redirect.'^'.$app.'^'.$type;

        // 폼의 입력값 변수로 받기
        $name           = $_POST['name'] ?? '';                       // 이용자 성명
        $phoneNo        = $_POST['phoneNo'] ?? '';               // 이용자 휴대폰번호
        $phoneCorp      = $_POST['phoneCorp'] ?? '';            // 이용자 이통사
        $birthDay       = $_POST['birthDay'] ?? '';                // 이용자 생년월일
        $gender         = $_POST['gender'] ?? '';                  // 이용자 성별
        $nation         = $_POST['nation'] ?? '';                    // 내외국인 구분

        // 한국모바일인증은 이름 정보를 euc-kr로 받는다.
        if(mb_detect_encoding($name) == "UTF-8")
            $name       = mb_convert_encoding($name, 'euc-kr', "utf-8");

        $name           = str_replace(" ", "+", $name) ;  //성명에 space가 들어가는 경우 "+"로 치환하여 암호화 처리

        $tr_cert        = $cpId . "/" . $urlCode . "/" . $certNum . "/" . $date . "/" . $certMet . "/" . $birthDay . "/" . $gender . "/" . $name . "/" . $phoneNo . "/" . $phoneCorp . "/" . $nation . "/" . $plusInfo . "/" . $extendVar;

        $enc_tr_cert    = exec("$ICERTSecu SEED 1 0 $tr_cert ");

        $enc_tr_hash    = exec("$ICERTSecu HMAC 1 0 $enc_tr_cert ");

        $enc_tr_cert    = $enc_tr_cert . "/" . $enc_tr_hash . "/" . $extendVar;
        $enc_tr_cert    = exec("$ICERTSecu SEED 1 0 $enc_tr_cert ");

        $rtn['call_url']= 'https://www.kmcert.com/kmcis/web/kmcisReq.jsp';
        $rtn['tr_url']  = $tr_url;
        $rtn['tr_cert'] = $enc_tr_cert;

        throw new Exception('success', 0);
    }    
} catch(Exception $e) {
    $r['result']        = $e->getCode();
    $r['result_msg']    = $e->getMessage();

    if($r['result'] == 0) {
        $r['item'] = $rtn;
    }

    echo json_encode($r);
}
?>