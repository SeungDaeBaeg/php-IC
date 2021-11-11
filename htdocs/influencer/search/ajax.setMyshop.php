<?php
/**
 * 마이샵 담기
 */

include_once('../_common.php');

if(!data::isLogin()) {
    util::ajaxResult("로그인 상태가 아닙니다.", -1);
}

$mb_no = data::getLoginMember()['mb_no'];
if(!data::isInfluencer($mb_no)) {
    util::ajaxResult("해당 회원은 인플루언서가 아닙니다.", -2);
}

$it_ids = util::param("it_ids") ?? util::ajaxResult("상품 아이디가 존재하지 않습니다.", -3);

if(!(is_array($it_ids) && count($it_ids) > 0)) {
    util::ajaxResult("오류가 발생하였습니다.", -4);
}

foreach($it_ids as $it_id) {
    $id = sql_insert('g5_myshop_item', array(
        'it_id' => $it_id,
        'mb_no' => $mb_no
    ));
}

if($id <= 0) {
    util::ajaxResult("담기에 실패하였습니다.", -4);
}

util::ajaxResult("담기에 성공하였습니다.", 0);