<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
$event_page = G5_INFLUENCER_URL.'/event/list.php';
?>

<div id="main_middle_menu">
    <button><a href="<? echo $event_page ?>">이벤트</a></button>
    <button>마이샵</button>
    <button>리포트</button>
    <button>출금관리</button>
</div>
