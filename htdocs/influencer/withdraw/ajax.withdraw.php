<?php
/**
 * 커미션 신청
 */

include_once('../_common.php');

//수익금 신청 날짜 체크
$today = (int)date('d');
if(!($today >= 6 && $today <= 15)) {
    util::ajaxResult("현재 출금 기간이 아닙니다. (출금기간: 매월 6일 ~ 15일)", -7);
}


if(!data::isLogin()) {
    util::ajaxResult("로그인 상태가 아닙니다.", -1);
}

$commission = util::param("commission", function() {
    util::ajaxResult("커미션 금액이 없습니다.", -2);
});

$mb_id = data::getLoginMember()['mb_no'];
if(!data::isInfluencer($mb_id)) {
    util::ajaxResult("해당 회원은 인플루언서가 아닙니다.", -3);
}

sql_fetch_data("
SELECT  mb_commission
FROM    g5_member
WHERE   mb_no = ?", $member, array($mb_id));

//요청한 커미션보다 가지고 있는 커미션이 적으면
if($commission > $member['mb_commission']) {
    util::ajaxResult("보유하고 있는 커미션이 부족합니다.", -4);
}

sql_fetch_data("
SELECT  commission
FROM    g5_payable
WHERE   mb_no = ?
AND     paid = 'UNPAID'
LIMIT   0, 1", $payable, array($mb_id));

if(!empty($payable)) {
    util::ajaxResult("이미 신청한 커미션이 있습니다. [신청한 금액 : ".number_format($payable['commission'])."원]", -5);
}

$res = sql_insert("g5_payable", array(
    'mb_no'     => $mb_id,
    'yyyymmdd'  => date('Ymd'),
    'hhmiss'    => date('His'),
    'commission'=> $commission
));

if($res <= 0) {
    util::ajaxResult('커미션 신청에 실패하였습니다.', -6);
}

util::ajaxResult('성공적으로 커미션 신청에 성공하였습니다.', 0);