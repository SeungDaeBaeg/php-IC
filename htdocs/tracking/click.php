<?php
/**
 * 제휴 링크
 * click.php?m=12345&p=12345
 */
include_once('_common.php');

$mb_no = util::param("m", function() {
    util::alert("회원코드가 존재하지 않습니다.");
    util::location("/");
});
$it_no = util::param("p");

//쿠키 저장
$data = json_encode(array(
    'm' => $mb_no
));

setcookie("ICINFO", $data, time() + 3600, "/", ".linkprice.com");

if(empty($it_no)) {
    //지정된 상품 코드가 없으면 메인으로 리다이렉션
    util::location('/');
} else {
    //상품 코드가 있을 경우 해당 상품의 상세페이지로 리다이렉션
    util::location('/shop/item.php?it_id='.$it_no);
}