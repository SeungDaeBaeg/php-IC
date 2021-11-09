<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');
util::loginCheck();

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/tail/info.css">', 0);

$user = data::getLoginMember();

/**
 * 내정보 화면
 * @todo : [승대] PPT 31페이지, 샘플 신청서
 */
?>

<!--content start-->
<div class="info_box">
    <div class="top_box">
        <?=$user['mb_id']?>님 반갑습니다.
        <div class="opt_box" id="option">톱니바퀴</div>
    </div>
    <div class="account_box">
        <div class="money_box f_jc_sb_box">
            <div class="info_common">수익금</div>
            <div class="info_common"><?=number_format($user['mb_commission'])?>원</div>
        </div>
        <div class="register_box info_common">계좌등록</div>
    </div>
    <div class="notice_box">
        <div class="notice_sub_box f_jc_sb_box">
            <div class="info_common">문의/공지</div>
            <div class="info_common">알림</div>
        </div>        
    </div>
    <div class="menu_box">
        <div class="menu_sub_box f_jc_sb_box">
            <div class="info_common">이벤트</div>
            <div class="info_common">마이샵</div>
            <div class="info_common">리포트</div>
            <div class="info_common">출금관리</div>
        </div>            
    </div>
    <div class="logout_box">
        <div class="logout_btn_box info_common" id="logout">로그아웃</div>
    </div>
</div>
<!--content end -->

<script>

    $('#logout').click(function() {
        location.href = "<?=G5_BBS_URL ?>"+'/logout.php';
    })

    $('#option').click(function() {
        location.href = '/influencer/tail/info_option.php';
    })

</script>

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>