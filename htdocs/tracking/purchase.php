<?php
/**
 * 실적 집계
 * purchase.php?m=12345&p=12345
 */
include_once('_common.php');

$ymd            = util::param('ymd') ?? date('Ymd');
$his            = util::param('his') ?? date('His');
$od_id          = util::param('ocd', '주문번호가 없습니다.');
$member_no      = util::param('mno', '회원번호가 없습니다.');
$from_purchase  = util::param('fp') ?? 'IC';
$buy_confirm    = util::param('bc') ?? 'N';
$sales          = util::param('sales') ?? 0;
$commission     = util::param('comm') ?? 0;
$commission_lp  = util::param('comm_lp') ?? 0;


$tr_id = sql_insert("g5_translog", array(
    "yyyymmdd"      => $ymd,
    "hhmiss"        => $his,
    "od_id"         => $od_id,
    "mb_no"         => $member_no,
    "from_purchase" => $from_purchase,
    "buy_confirm"   => $buy_confirm,
    "sales"         => $sales,
    "commission"    => $commission,
    "commission_lp" => $commission_lp
));

if($tr_id <= 0) {
    util::ajaxResult("실적 발생이 안되었습니다.", -2);
}

util::ajaxResult();
