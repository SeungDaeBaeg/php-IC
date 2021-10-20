<?php
/**
 * @todo: [승대] 출금관리 화면 코딩 필요
 * 출금 관리 페이지
 */
include_once('./_common.php');

define("_INDEX_", TRUE);
include_once(G5_SHOP_PATH.'/shop.head.php');
?>

<!--content start-->

<!-- 수익금 정보 -->
<table style="width:100%;">
    <tr>
        <th colspan="2">수입금 정보</th>
    </tr>
    <tr>
        <th>오늘 발생한 수익금</th>
        <td>1,000원</td>
    </tr>
    <tr>
        <th>오늘 발생한 수익금</th>
        <td>10,000원</td>
    </tr>
</table>

<hr/>

<!-- 필터링 -->
<div>
    <select name="view_type">
        <option value="day">일별보기</option>
        <option value="month">월별보기</option>
    </select>
    <input type="text" name="start_date" />
    <input type="text" name="end_date" />

    <button>조회</button>

    <!-- 엑셀 다운로드는 구현안하기로 했음 -->

    <p>수익금 : 10,000</p>
    <p>수익금 : 1,000</p>
</div>

<hr/>

<!-- 실적 테이블 -->
<table style="width:100%;">
    <tr>
        <th>기간</th>
        <th>수익금</th>
        <th>출금</th>
        <th>출금가능</th>
    </tr>
    <tr>
        <th>2021-01</th>
        <th>0</th>
        <th>0</th>
        <th>0</th>
    </tr>
    <tr>
        <th>합계</th>
        <th>0</th>
        <th>0</th>
        <th>0</th>
    </tr>
</table>
<!--content end -->
<?
include_once(G5_SHOP_PATH.'/shop.tail.php');
?>
