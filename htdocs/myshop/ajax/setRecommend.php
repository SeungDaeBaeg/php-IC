<?php
/**
 * 추천 상품 설정
 */

include_once('../_common.php');

$mb_id = util::paramCheck("id", function() {
    util::ajaxResult("회원 아이디가 없습니다.", -1);
});

$it_id = util::paramCheck("it_id", function() {
    util::ajaxResult("상품 아이디가 없습니다.", -2);
});

if(!data::isInfluencer($mb_id)) {
    util::ajaxResult("해당 회원은 인플루언서가 아닙니다.", -3);
}

$id = sql_fetch_insert("g5_shop_item_recommend", array(
    'mb_id' => $mb_id,
    'it_id' => $it_id,
    'sort'  => $sort
));

if($id <= 0) {
    util::ajaxResult("등록이 제대로 되지 않았습니다.", -4);
}

util::ajaxResult();


