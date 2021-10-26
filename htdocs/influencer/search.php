<?php
/**
 * 검색 결과 페이지
 */
include_once('./_common.php');

define("_INDEX_", true);
include_once(G5_SHOP_PATH.'/shop.head.php');
?>

<!--content start-->

<!-- @todo: [승대] 가로 필터링 PPT 21 페이지-->
<div style="width:100%;height:20px;">
    <button class="btn_top_filter" data-filter="1">판매량순</button>
    <button class="btn_top_filter" data-filter="2">수익금순</button>
    <button class="btn_top_filter" data-filter="3">낮은가격순</button>
    <button class="btn_top_filter" data-filter="4">최신순</button>
    <button class="btn_top_filter" data-filter="5">높은가격순</button>

    <button>상세필터 버튼</button>
</div>

<h2>검색 결과</h2>




<!--content end -->

<?
include_once(G5_SHOP_PATH.'/shop.tail.php');
