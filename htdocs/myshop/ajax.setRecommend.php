<?php
/**
 * 추천 상품 설정
 */

include_once('./_common.php');


if(!data::isLogin()) {
    util::ajaxResult("로그인 상태가 아닙니다.", -2);
}

$it_id = util::param("it_id", function() {
    util::ajaxResult("상품 아이디가 없습니다.", -2);
});

$mb_id = data::getLoginMember()['mb_no'];
if(!data::isInfluencer($mb_id)) {
    util::ajaxResult("해당 회원은 인플루언서가 아닙니다.", -4);
}

sql_fetch_data("
SELECT  COUNT(1) as cnt
FROM    g5_shop_item_recommend
WHERE   mb_no = ?
AND     it_no = ?", $recommendCnt, array($mb_id, $it_id));

if($recommendCnt['cnt'] > 0) {
    util::ajaxResult("이미 추천이 등록되었습니다.", -5);
}

//멤버당 갯수는 3개 이상은 등록할 수 없다.
sql_fetch_data("
SELECT  COUNT(1) as cnt
FROM    g5_shop_item_recommend
WHERE   mb_no = ?", $recommendCnt, array($mb_id));

if($recommendCnt['cnt'] >= 3) {
    util::ajaxResult("추천 상품은 3개까지 등록이 가능합니다.", -6);
}

//추천 등록
$id = sql_insert("g5_shop_item_recommend", array(
    'mb_no' => $mb_id,
    'it_no' => $it_id,
    'sort'  => 0
));

if($id <= 0) {
    util::ajaxResult("등록이 제대로 되지 않았습니다.", -7);
}

util::ajaxResult('성공적으로 추천에 등록하였습니다.', 0);


