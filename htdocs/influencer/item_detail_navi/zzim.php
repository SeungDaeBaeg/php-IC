<?php
/**
 * 찜한 상품 저장
 */

include_once('../_common.php');

util::loginCheck();

$it_id = util::paramCheck("it_id", "상품 아이디가 없습니다.");

sql_fetch_data("
SELECT  COUNT(1) cnt
FROM    g5_shop_zzim
WHERE   mb_no = ?
AND     it_no = ?", $zzim, array(data::getLoginMember()['mb_no'], $it_id));

if($zzim['cnt'] > 0) {
    util::alert("해당 상품은 이미 찜한 상태입니다.", true);
}

$id = sql_insert('g5_shop_zzim', array(
    'mb_no' => data::getLoginMember()['mb_no'],
    'it_no' => $it_id
));

util::alert('해당 상품을 찜하였습니다.');
util::location('/shop/item.php?it_id=' . $it_id);