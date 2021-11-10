<?php
/**
 * 찜한 상품 저장
 */

include_once('../_common.php');

util::loginCheck();

$it_id = util::param("it_id", "상품 아이디가 없습니다.");

$mb_id = data::getLoginMember()['mb_id'];

sql_fetch_data("
SELECT  COUNT(1) cnt
FROM    g5_shop_wish
WHERE   mb_id = ?
AND     it_id = ?", $wish, array($mb_id, $it_id));

if(intval($wish['cnt']) > 0) {
    util::alert("해당 상품은 이미 찜한 상태입니다.", true);
    exit;
}

$id = sql_insert('g5_shop_wish', array(
    'mb_id' => $mb_id,
    'it_id' => $it_id
));

util::alert('해당 상품을 찜하였습니다.', function() use($it_id)  {
    return util::location('/shop/item.php?it_id=' . $it_id);
});