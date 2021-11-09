<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/common.php');
header("Content-Type: text/html; charset=UTF-8");
header('Expires: 0');
    
try {

    $ICERTSecu      = './ICERTSecu';
    $mode           = $_REQUEST['mode'] ?? '';

    switch($mode) {
        default:
            $cpId           = 'LINM1001';
            $urlCode        = $_POST['url_code'] ?? '';
            $certNum        = uniqid();
            $date           = date('YmdHis');
            $certMet        = 'M';
            $extendVar      = '0000000000000000';
            $from           = $_POST['from'] ?? '';
            $plusInfo       = $from; 

            $tr_url         = "https://".$_SERVER['HTTP_HOST']."/influencer/cert/kmc.php?mode=auth_complete";    // 본인인증 결과수신 URL

            $tr_cert        = $cpId . "/" . $urlCode . "/" . $certNum . "/" . $date . "/" . $certMet . "/" . "/" . "/" . "/" . "/" .  "/" . "/" . $plusInfo . "/" . $extendVar;

            $enc_tr_cert    = exec("$ICERTSecu SEED 1 0 $tr_cert ");

            $enc_tr_hash    = exec("$ICERTSecu HMAC 1 0 $enc_tr_cert ");

            $enc_tr_cert    = $enc_tr_cert . "/" . $enc_tr_hash . "/" . $extendVar;
            $enc_tr_cert    = exec("$ICERTSecu SEED 1 0 $enc_tr_cert ");

            $rtn['call_url']= 'https://www.kmcert.com/kmcis/web/kmcisReq.jsp';
            $rtn['tr_url']  = $tr_url;
            $rtn['tr_cert'] = $enc_tr_cert;

            throw new Exception('success', 0);
        case 'auth_complete':
            //https://www.inburstshop.com/influencer/cert/kmc.php?mode=auth_complete&rec_cert=CA3090C4A8CD2B334BF1CAA1C7CCEFD6577B7137C8330E903CBA80562C37FABD175F13A477FDBFC8C89D0C9E720474BFCAAF88E7C9C648AC060E4CBE2E4CB8BACE4D695045C8A2D04DDBEAA70F5B0FC1B21E9D600D0CEC46DD4AC16DE0FB02A4C522FFD7C725670489631618F7AF31EB3B8149B95E87788B461487C1BF320BFDA2289629236F4685613F2527E4813A6F31A9859B54FC0E40B67B295453472790C8D92B9C592BBC07896CF09E05D29CD01F871E70973D26E01812F88BAFD03681B2A7EC590B5BF30E543EC36A4D0BC52E1DAB9E4E8B77D41FD5BB12650DF7755FF888FEDD9E3EEE0595CD196FD9D7ED048587278111619367C2E329665C28876CB1C34734D99279837FB4209EBE03A14F33DBE5C699285523453A436C7DFC184A2255473C87E5D9ED24897C8F66ED9D2172F65799BE4ED0DB5AC30A26B1014B794BB312F6D3BB72AEE11ACF18CC2F7BDD779DBF92AC465FB4723E7CFC3CB5881E4B12BB5953C30BD7235D663FAEB564C76EBED61F6C5E83104A9B442C9B98F0124296791933991B81C8B983AEA4298A6D3203F1D3E42088A5B5BC435BBF1AC3CC953711EA624006B1FEB29759FD63EAD198453DE2B91D1C3929ECBC83560CDB7D4E3B4EE45984E4377BACC5DF48515E35D679BBC583B0BCD0D7992BA0B8E2978BDB90E33280F6801854479EA236F4C27898042EF330A98DF3D2294F00C28808CD60A065CCD544A76192177FB362E6E613000859F112E96E1FD26EEFA3A2C3765751C258FD9782E4FE060E924208749FBB3D8EA2E2F670D543417CB35D73F7F1F7D5D86D0526225E0364D2EC70B54D2C1E886F5E6B9EFB3B0D2B7D5A8EEC3E88386598DC559B2CD9C0EB18A0E8B55FF424616E53BDAC2C3F00BFE384960EFB19993747EC818E7D27B1FCC44515A9B9CFC3BA3168ED5552E990138CB3573FCE32F34CA2B93A7E6725682BEE90B0C496AEC1FEF07AB286256F9423221D6C1B1EB6F7B6F9D98F1D47421F76EE77E479F68826CE411F38DA68945F1BFC88775D7FBEDB9C0FBCB47E9BE01FF39DD2265A7C9B90F547C80C94699D550032C2BF888BCF12D7A1500EFFE3D40B3166450DA89FFA80A3CA1B85E6DD87D290DBD6ACDF5BFA9AC876A04E0E8E276FD7AE24DE795D938925CF61BA536DE7B5CFDB5A4962B7BEB44AF66FA09C62911B79EB8BA6DEEFED2478F3B50B8E639E487FF34B2A3F26DB8C5B3264E9B5EED0693D7C4D48501E9743B3EB3C3F873E2D3C81B6CF8FF4F0D5941650C6D1A931DD19E745733D440496E6C63F24135281DB148021FDB7FA6EA0217B2144B8D2629D3A81B380252B59938AD27A1D6AFD9EC0106AEB918D65DB0973&certNum=6189bd0e81f4e

            // Parameter 수신
            $rec_cert = $_REQUEST['rec_cert'] ?? "";
            $cookieCertNum = $_REQUEST['certNum'] ?? "";    
    
            // 01. 인증결과 1차 복호화
            $rec_cert = exec($ICERTSecu.' SEED 2 0 '.$cookieCertNum.' '.$rec_cert);
    
            // 02. 복호화 데이터 Split (rec_cert 1차암호화데이터 / 위변조 검증값 / 암복화확장변수)
            $decStr_Split = explode('/', $rec_cert);
    
            $encPara = isset($decStr_Split[0]) ? $decStr_Split[0] : '';    // rec_cert 1차 암호화데이터
            $encMsg = isset($decStr_Split[1]) ? $decStr_Split[1] : '';        // 위변조 검증값
    
            // 03.인증결과 1차 복호화
            $rec_cert = exec($ICERTSecu.' SEED 2 0 '.$cookieCertNum.' '.$encPara);
    
            // 04. 복호화 된 결과자료 '/'로 Split 하기
            $decStr_Split = explode('/', $rec_cert);
            
            //Array ( [0] => 60a77936d8e1c [1] => 20210521181118 [2] => 5DAE7DE89F313736BD629B9414A300034F5891CA2E485D6195194CF7B1ED6EF40193412D40272FB45B5AA27E86235A24A953D4C4042830FC29FD236BB5D588746DA0DA72D575728E52A723B9CFFAC2BF7BE9C2BA6886BB03157406B3420DC0FB [3] => 01037632756 [4] => KTM [5] => 19850805 [6] => 0 [7] => 0 [8] => ������ [9] => Y [10] => M [11] => 211.189.137.203 [12] => [13] => [14] => [15] => [16] => join^kjs503^pw-find^ [17] => 2B759E03665E4960A054C087295A6BB8E46880043BBC2B01287CC21E81EEC603D99A24E00207A87178994EF8F631A91E0ED9093DAB00445E1E015297AE542DE4 [18] => 0000000000000000 )
    
            $certNum    = isset($decStr_Split[0]) ? $decStr_Split[0] : '';
            $date        = isset($decStr_Split[1]) ? $decStr_Split[1] : '';
            $CI            = isset($decStr_Split[2]) ? $decStr_Split[2] : '';
            $phoneNo    = isset($decStr_Split[3]) ? $decStr_Split[3] : '';
            $phoneCorp    = isset($decStr_Split[4]) ? $decStr_Split[4] : '';
            $birthDay    = isset($decStr_Split[5]) ? $decStr_Split[5] : '';
            $gender        = isset($decStr_Split[6]) ? $decStr_Split[6] : '';
            $nation        = isset($decStr_Split[7]) ? $decStr_Split[7] : '';
            $name        = isset($decStr_Split[8]) ? $decStr_Split[8] : '';
            $result        = isset($decStr_Split[9]) ? $decStr_Split[9] : '';
            $certMet    = isset($decStr_Split[10]) ? $decStr_Split[10] : '';
            $ip            = isset($decStr_Split[11]) ? $decStr_Split[11] : '';
            $M_name        = isset($decStr_Split[12]) ? $decStr_Split[12] : '';
            $M_birthDay    = isset($decStr_Split[13]) ? $decStr_Split[13] : '';
            $M_Gender    = isset($decStr_Split[14]) ? $decStr_Split[14] : '';
            $M_nation    = isset($decStr_Split[15]) ? $decStr_Split[15] : '';
            $plusInfo    = isset($decStr_Split[16]) ? $decStr_Split[16] : '';
            $DI            = isset($decStr_Split[17]) ? $decStr_Split[17] : '';
    
            // CI, DI 복호화
            if (strlen($CI) > 0)
                $CI = exec($ICERTSecu.' SEED 2 0 '.$cookieCertNum.' '.$CI);
    
            if (strlen($DI) > 0)
                $DI = exec($ICERTSecu.' SEED 2 0 '.$cookieCertNum.' '.$DI);
    
            $birth_year = substr($birthDay, 0, 4);
    
            if ($gender == 0)
            {
                $db_gender = 'M';
    
                if ($birth_year < 1900)
                    $ipin_birth = 9;
                else if ($birth_year < 2000)
                    $ipin_birth = 1;
                else
                    $ipin_birth = 3;
            }
            else
            {
                $db_gender = 'F';
    
                if ($birth_year < 1900)
                    $ipin_birth = 0;
                else if ($birth_year < 2000)
                    $ipin_birth = 2;
                else
                    $ipin_birth = 4;
            }
    
            $name = mb_convert_encoding($name, "utf-8", "euc-kr");
            $phoneNo = mb_convert_encoding($phoneNo,"utf-8", "euc-kr");
            $birthDay = mb_convert_encoding($birthDay, "utf-8", "euc-kr");
            $DI = mb_convert_encoding($DI, "utf-8", "euc-kr");
            $cookieCertNum = mb_convert_encoding($cookieCertNum, "utf-8", "euc-kr");    
    
            $rtn['name']     = $name;
            $rtn['phoneNo']     = $phoneNo;
            $rtn['birthDay'] = $birthDay;
            $rtn['DI']         = $DI;
            $rtn['certNum']     = $cookieCertNum;
    
            //========================================================== 인증 후 처리 ==========================================================
            if($plusInfo === 'update') {
                $sql = "select count(*) cnt from g5_member where mb_hp = ? and mb_no <> ?";
                sql_fetch_data($sql,$dupCheck,array($phoneNo,data::getLoginMember()['mb_no']));
                
                if((int)$dupCheck['cnt'] > 0) {
                    echo "
                    <script>
                    opener.util.alert('중복된 핸드폰 번호가 있습니다.');
                    window.close();
                    </script>
                    ";
                }
                else {
                    echo "
                    <script>
                    opener.document.getElementById('name').value = '$name'
                    opener.document.getElementById('info_update_phone').value = '$phoneNo'
                    opener.cert_phone = '$phoneNo'
                    opener.cert_name = '$name'
                    window.close();
                    </script>
                    ";
                }                
            }
            else if($plusInfo === 'join') {
                $sql = "select count(*) cnt from g5_member where mb_hp = ?";
                sql_fetch_data($sql,$dupCheck,array($phoneNo));
                
                //신규가입 중복된 핸드폰 번호가 있을때
                if((int)$dupCheck['cnt'] > 0) {
                    echo "
                    <script>
                    opener.document.getElementById('error_phone').textContent = '중복된 핸드폰 번호가 있습니다.';
                    window.close();
                    </script>
                    ";
                }
                else {
                    echo "
                    <script>
                    opener.document.getElementById('input_phone').value = '$phoneNo'
                    opener.document.getElementById('reg_mb_name').value = '$name'
                    opener.document.getElementById('reg_mb_sex').value = '$db_gender'
                    opener.kmc_hp = '$phoneNo'
                    window.close();
                    </script>
                    ";
                }                
            }
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