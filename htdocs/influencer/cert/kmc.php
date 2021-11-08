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

            $tr_cert        = $cpId . "/" . $urlCode . "/" . $certNum . "/" . $date . "/" . $certMet . "/" . "/" . "/" . "/" . "/" .  "/" . "/" . "/" . $extendVar;

            $enc_tr_cert    = exec("$ICERTSecu SEED 1 0 $tr_cert ");

            $enc_tr_hash    = exec("$ICERTSecu HMAC 1 0 $enc_tr_cert ");

            $enc_tr_cert    = $enc_tr_cert . "/" . $enc_tr_hash . "/" . $extendVar;
            $enc_tr_cert    = exec("$ICERTSecu SEED 1 0 $enc_tr_cert ");

            $rtn['call_url']= 'https://www.kmcert.com/kmcis/web/kmcisReq.jsp';
            $rtn['tr_url']  = $tr_url;
            $rtn['tr_cert'] = $enc_tr_cert;

            throw new Exception('success', 0);
        case 'auth_complete':
            //https://www.inburstshop.com/influencer/cert/kmc.php?mode=auth_complete&rec_cert=D4D4284652744B163A0A9DDE718F74D04BBEAFD6C30279F187A6DBC61BC01208AF9354E26FC034A85F85B7298ABA27D8EDAFD5EC1D13557311E10E205A92DBDDC72497B918924E50A705F5849CBB4D5CF6E59DB885A33719912558C4AB42946EDEDD36745CCF479EB1F738E04AA56923546CC2507C8108E2D756BBA56AB9216D19BF555860BEF3E63BE70D0818FD13D4C3B96900FCA1BA7AE9A1D7BCC105AE195A7A13ADDCAFD0F44087C79F24B5E441C3F9C640174F2A53E59F03631CF85390E6CFF23F2142F9D916B6524495818996C1A386935CE8C980F76640CA9E8E21F01364D9160E4E5C2CA19B475821A1F8E58F439A04ABDEE4FD4A15C54221F4C32CC12B9A5DBFA568C8CBFA4A2B0C41ABA4497F28B373DF906734EABD75DEE723BA5B40FEBACB230DBA1509A88544332CB7ADCB9317B421289FFCC819427479C153B2FBD10A57F69B18B3A89301075BAA98347747D075657241FC51840E4A4C217EA7FCB6584193F929290FC847AC92DF6F0164EEB3667E34C7C5AB1BD4BA4834918D046DAF69FB9A78ECE5026045043D094A021ED1349332E2442C225F2FF2A995C687D6625894AF0DC7A908004CE911F1C3926C0A159D21D03E4FF84C608AB7AA32593A3E38F8FCBE6434F6F0C66A2FC78999C514F345EC8F385F0E2D59D3F84A56D78C52A19F3E90919966B44DA7CE3E499BC47112DB0BC0DC21D89D2F509372C6D4F2AB8ADF397602B2A5DEC1BE7667AE5811AB1C52D497BDEDC20D30322BDF4B493BA81C4430313B3F2A77D1FDCA45104D4CACE121A19E2872D7169B995D7330B35C2CDCDCE255446B7CB28E65CABDB16B8B9109D57C0B265247D6D21E60C502135961276A7F78B54D1CE69F9B20528A7D091DA20CF8097B55874B951EEBEA7255356C4A4BDB1F7B70E31F3E8F2282641BC2F016270994FAB9BE55B3E33F1A989C3403A5D896742FA3ADC55051F4F3C3FA25A98DB5501C379439454884BF53D49C1DA5F1D87FB5A9EA3BF63AD92FC7BDAE2C9EBAE36DB2815A56FAAD1EE87515A8B2DD06D7C2D92879CDE89732143817A3A8A73F27ABCA0FC7DEBFF3B94E5CAF4C922CA4621008482C6645968CC10CE50392D8CCA3566BEB9D58F4565877A02415A5178D5F95D57218545C980696C2EA471CC76671E70C4F3A7EF742429F7D4151D9A15791120BC6FC3410339427600E2E5E26AC34A848EED6CE17B1A68144A249BACE8B5D3399DCECD3E7D794666CC73EDAC2416822E333EA332B6AD63623260F914BDE14A9D1FE3FE66DBAE3D68C47773B39F7612C078B89B2B5AC3E444C&certNum=6189149f209d9

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

            var_dump($decStr_Split);
            exit;
            
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
    
            if ($result == 'Y')            // 성공
                $db_result = 1;
            else if ($result == 'N')    // 실패
                $db_result = 2;
            else                        // 오류
                $db_result = 5;
    
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
            $plusInfo = explode('^', trim($plusInfo));
            $app = trim($plusInfo[3]);
    
            if($plusInfo[2] == 'ac2') {
                //ac2.linkprice.com 처리
                $accountCheck = true;
    
                phoneNumberUpdate($account_id, $name, $phoneNo);
            } else if($plusInfo[2] == 'id-find') {
                $accountCheck = getAccountCheck(null, $name, $phoneNo, getSsn($birthDay, $gender));
    
                //ac2.linkprice.com/id-find 처리
                $account_id = getAccountInfo($name, $phoneNo, getSsn($birthDay, $gender));
            } else if ($plusInfo[2] =='pw-find') {
                $accountCheck = getAccountCheck($account_id, $name, $phoneNo, getSsn($birthDay, $gender));
    
                $account_id = $plusInfo[1];
                $old_password = getPasswordInfo($account_id, $name, $phoneNo, getSsn($birthDay, $gender));
            } else if($plusInfo[2] == 'dormancy') {
                //휴면 계정
                $account_id        = getAccountDormancy($type, $account_id, $name, $phoneNo, getSsn($birthDay, $gender));
    
                if(trim($account_id) != '') {
                    setDormancyRelease($account_id);
                } else {
                    //본인인증 실패
                    echo "
                    <script>
                        alert(\"입력하신 정보가 등록된 정보와 달라 휴면 계정 해제가 불가능 합니다. 고객센터로 휴면 계정 해제 요청 부탁드립니다.\");
                        window.opener.location = 'https://www2.linkprice.com/views/about_us/contact_us.html';
                        window.close();
                    </script>";
                    exit();
                }
    
                $accountCheck    = ($account_id != '');
            } else {
                $accountCheck = true;
            }
    
            if(!$accountCheck) {
                $result = 'N';
            }
    
            if($result == 'N') {
                if(!$accountCheck) {
                    $result_msg = "존재하지 않는 계정이거나 휴대폰번호 입니다.";
                } else {
                    $result_msg = "인증이 되지 않았습니다.";
                }
            }
    
    
            if ($result == 'Y') {
                if($account_id != '') {
                    //본인 인증 완료 후, 필수 업데이트
                    $row = getQuery(
                        'ADM',
                        'dc_lpop/taccount/v1/GetAccount',
                        array(
                            "acc_id"=>$account_id
                        ),
                        'PHP_API'
                    );
                    $row = $row[0];
    
                    if((isset($row['password']) && $row['password'] == '0') || (isset($row['contact_name']) && $row['contact_name'] == '휴면계정상태')) {
                        $row = getQuery(
                            'ADM',
                            'dc_lpop/taccount_dormancy/v1/Update',
                            array(
                                "acc_id"=>$account_id,
                                "contact_phone2"=>$phoneNo,
                                "discrhash"=>$DI,
                                "ciscrhash"=>$CI,
                                "ssn1"=>getSsn($birthDay, $gender)
                            ),
                            'PHP_API'
                        );
                    } else {
                        $row = getQuery(
                            'ADM',
                            'dc_lpop/taccount/v1/Update',
                            array(
                                "acc_id"=>$account_id,
                                "contact_phone2"=>$phoneNo,
                                "discrhash"=>$DI,
                                "ciscrhash"=>$CI,
                                "ssn1"=>getSsn($birthDay, $gender)
                            ),
                            'PHP_API'
                        );
                    }
    
                    $row = getQuery(
                        'ADM',
                        'dc_lpop/taccount/v1/Update',
                        array(
                            "acc_id"=>$account_id,
                            "remind_mail_date"=>date('YmdHis')
                        ),
                        'PHP_API'
                    );
                }
    
                if($plusInfo[2] == 'id-find' || $plusInfo[2] == 'pw-find') {
                    echo "
                    <script>
                        var objReturn = {};
                        objReturn.contact_name = '".$name."';
                        objReturn.reqnum = '".$cookieCertNum."';
                        objReturn.phoneNo = '".$phoneNo."';
                        objReturn.birthDay = '".$birthDay."';
                        objReturn.DI = '".$DI."';
                        objReturn.account_id = '".$account_id."';
                        objReturn.old_password = '".$old_password."';
                        objReturn.redirect = '".$plusInfo[2]."';
                        objReturn.gender = '".$gender."';
                        objReturn.app = '".$app."';
                        
                        var str = [];
                        for (var p in objReturn)
                        if (objReturn.hasOwnProperty(p)) {
                            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(objReturn[p]));
                        }
                        var qs = str.join('&');
    
                        window.location = 'https://ac.linkprice.net/person_check.html?' + qs;
                    </script>";
                } else if($plusInfo[2] == 'dormancy') {
                    echo "
                    <script>
                        var objReturn = {};
                        objReturn.contact_name = '".$name."';
                        objReturn.reqnum = '".$cookieCertNum."';
                        objReturn.phoneNo = '".$phoneNo."';
                        objReturn.birthDay = '".$birthDay."';
                        objReturn.DI = '".$DI."';
                        objReturn.account_id = '".$account_id."';
                        objReturn.old_password = '".$old_password."';
                        objReturn.redirect = '".$plusInfo[2]."';
                        objReturn.type = '".$type."';
                        objReturn.gender = '".$gender."';
                        objReturn.app = '".$app."';
                        
                        var str = [];
                        for (var p in objReturn)
                        if (objReturn.hasOwnProperty(p)) {
                            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(objReturn[p]));
                        }
                        var qs = str.join('&');
                        
                        
                        alert(\"인증 완료 되었습니다. 원활한 활동을 위해 개인정보 변경 페이지로 이동합니다.\");
                        
                        window.location = 'https://ac.linkprice.net/person_check.html?' + qs;
                    </script>";
                } else {
                    echo "
                    <script>
                        var objReturn = {};
                        objReturn.contact_name = '".$name."';
                        objReturn.reqnum = '".$cookieCertNum."';
                        objReturn.phoneNo = '".$phoneNo."';
                        objReturn.birthDay = '".$birthDay."';
                        objReturn.DI = '".$DI."';
                        objReturn.redirect = '".$plusInfo[2]."';
                        objReturn.type = '".$type."';
                        objReturn.gender = '".$gender."';
                        objReturn.app = '".$app."';
    
                        var str = [];
                        for (var p in objReturn)
                        if (objReturn.hasOwnProperty(p)) {
                            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(objReturn[p]));
                        }
                        var qs = str.join('&');
    
                        window.location = 'https://ac.linkprice.net/person_check.html?' + qs;
                        
                    </script>";
                }
            } else {
                $result_msg = isset($result_msg) ? $result_msg : '';
                echo "
                <script>
                    alert('".$result_msg."');
                    window.opener.location.href = 'https://www2.linkprice.com/views/about_us/contact_us_detail.html?cat_id=1';
                    window.close();
                </script>";
            }
            exit();
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