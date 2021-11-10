<?php
/**
 * 이벤트 참여내역 페이지
 */
include_once('../_common.php');
util::loginCheck();

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
include_once('./event.header.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/event/party.css">', 0);

$ajax_event_url = G5_INFLUENCER_URL.'/event/ajax.event.php';

?>

<div class="party_box">
    <div class="title_box">나의 이벤트 참여내역</div>
    <div class="menu_box">
        <select name="gubun" id="gubun">
            <option value="all">전체</option>
            <option value="normal">일반</option>
            <option value="private">프라이빗</option>
        </select>
        <select name="process" id="process">
            <option value="all">진행단계</option>
            <option value="1">신청중</option>
            <option value="2">선정완료</option>
            <option value="3">등록중</option>
            <option value="4">등록완료</option>
            <option value="5">종료</option>
            <option value="6">취소</option>
            <option value="7">미선정</option>            
        </select>
    </div>
</div>

<script>
    var param = {
        gubun: 'all',
        process: 'all',
        kine: 'all'
    }
    
    $('select').change(function(){
        param[this.name] = this.value
        ajaxCall();
    });

    function ajaxCall() {
        data.ajax()
    }
</script>

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>