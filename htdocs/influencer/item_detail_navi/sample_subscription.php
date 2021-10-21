<?php
/**
 * 샘플 신청
 */
include_once('./_common.php');

define("_INDEX_", TRUE);
include_once(G5_SHOP_PATH.'/shop.head.php');
?>

<!--content start-->


<h2>샘플 신청서

    <table style="width:100%">
        <tr>
            <th>샘플신청 상품</th>
            <td></td>
        </tr>
        <tr>
            <th>옵션1</th>
            <td></td>
        </tr>
        <tr>
            <th>옵션2</th>
            <td></td>
        </tr>
        <tr>
            <th>이름</th>
            <td></td>
        </tr>
        <tr>
            <th>연락처</th>
            <td></td>
        </tr>
        <tr>
            <th>이메일</th>
            <td></td>
        </tr>
        <tr>
            <th>SNS 아이디</th>
            <td></td>
        </tr>
        <tr>
            <th>SNS 팔로워/구독자수</th>
            <td></td>
        </tr>
        <tr>
            <th>주소</th>
            <td></td>
        </tr>
    </table>

    <p>
        <button>신청완료</button>
    </p>



<!--content end -->

<?
include_once(G5_SHOP_PATH.'/shop.tail.php');
?>