<?php
/**
 * 마이샵에서 제거
 */
include_once('./_common.php');

if(!data::isLogin()) {
    util::ajaxResult("로그인 상태가 아닙니다.", -1);
}

$it_id = util::param("it_id", function() {
    util::ajaxResult("상품 아이디가 없습니다.", -2);
});

$mb_id = data::getLoginMember()['mb_id'];
if(!data::isInfluencer($mb_id)) {
    util::ajaxResult("해당 회원은 인플루언서가 아닙니다.", -3);
}

$res = sql_delete("g5_myshop_item", "mb_no = {$mb_id} AND it_id = {$it_id}");

if(!$res) {
    util::ajaxResult('오류가 발생하였습니다.', -4);
}

util::ajaxResult('성공적으로 제거하였습니다.', 0);


