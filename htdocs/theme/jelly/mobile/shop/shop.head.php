<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');

function get_mshop_category($ca_id, $len) {
    global $g5;

    $sql = " select ca_id, ca_name from g5_shop_category
                where ca_use = '1' ";
    if($ca_id)
        $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " and length(ca_id) = '$len' order by ca_order, ca_id ";

    return $sql;
}


$searchTxt = util::param('qs');
if($searchTxt != '') {
    $searchTxt = urldecode($searchTxt);
}

//로그인 했을 경우 추가 처리
if(data::isLogin()) {
    //읽지 않은 메세지 카운트
    sql_fetch_data("
    SELECT  COUNT(1) cnt
    FROM    g5_alarm_detail
    WHERE   mb_no = ?
    AND     readed_at IS NULL", $alarmCnt, array(data::getLoginMember()['mb_no']));
}

//카테고리
sql_fetch_arrays("
SELECT  ca_id, ca_name
FROM    g5_shop_category
WHERE   ca_use = '1'", $categorys);


echo util::component("common/head", array(
    'loginInfo'    => data::getLoginMember(),
    'memberInfo'   => $memberInfo,
    'config'       => $config,
    'searchTxt'    => $searchTxt,
    'g5'           => $g5,
    'alarmCnt'     => $alarmCnt['cnt'],
    'categorys'    => $categorys
));