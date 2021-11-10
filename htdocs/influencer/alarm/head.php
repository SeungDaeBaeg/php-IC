<?php
/**
 * @todo: [승대] 알람페이지 코딩 필요
 * 알람 상단
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/influencer/_common.php';

util::loginCheck();

define("_INDEX_", true);
include_once(G5_THEME_MOBILE_PATH.'/shop/shop.head.php');
?>

<p>읽지 않은 메세지 <?=$alarmCnt['cnt']?>통</p>

<p>
    <a href="/influencer/alarm/list.php">
        <button style="width:49%">알람 (<?=$alarmCnt['cnt']?>)</button>
    </a>

    <a href="/bbs/board.php?bo_table=notice">
        <button style="width:49%">공지사항</button>
    </a>
</p>