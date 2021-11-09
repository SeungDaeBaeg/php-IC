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
$tu = util::param("tu");
$myshop = util::param("ms") ?? "";

//쿠키 저장
$data = json_encode(array(
    'm'     => $mb_no,
    'ms'    => $myshop
));

setcookie("ICINFO", $data, time() + 3600, "/", ".linkprice.com");

if(!empty($tu)) {
    //지정된 상품 코드가 없으면 메인으로 리다이렉션
    //ex : %2Fshop%2Fitem.php%3Fit_id%3D1634175817
    util::location(G5_URL . urldecode($tu));
}

util::location(G5_URL);

