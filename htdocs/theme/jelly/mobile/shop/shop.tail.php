<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$admin = get_admin("super");

$is_not_navigation = false;
if(in_array($_SERVER['DOCUMENT_URI'], array('/shop/item.php'))) {
    $is_not_navigation = true;
}

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
echo util::component('common/footer', array(
    'config'             => $config,
    'is_admin'           => $is_admin,
    'is_not_navigation'  => $is_not_navigation
));

// HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다.
echo html_end();