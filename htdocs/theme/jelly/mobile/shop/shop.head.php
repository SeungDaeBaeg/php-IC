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


$searchTxt = util::paramCheck('qs');
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

?>

<header id="hd">
    <?php if ((!$bo_table || $w == 's' ) && defined('_INDEX_')) { ?><h1><?=$config['cf_title'] ?></h1><?php } ?>

    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php if(defined('_INDEX_')) { // index에서만 실행
        include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
    } ?>


    <div id="hd_wr">
        <div id="logo">
            <div style="position:relative;width:100%;height:25px;float:left;text-align: center;">
                <div class="visible-mobile" style="position:absolute;left:0px;top:0px;height:25px;">
                    <? if(!empty(data::getLoginInfo())) { ?>
                        <i class="fa fa-money">
                            <a href="/influencer/withdraw.php">
                                <span style="margin-left:0.2rem;"><?=number_format(data::getLoginMember()['mb_save_money'])?>원</span>
                            </a>
                        </i>
                        <span class="sound_only">분류열기</span>
                    <? } else { ?>
                        <a class="btn_ol">로그인</a>
                    <? } ?>
                </div>

                <a href="<?=G5_SHOP_URL; ?>/">
                    <img src="<?=G5_DATA_URL; ?>/common/logo.jpg" alt="<?=$config['cf_title']; ?> 메인">
                </a>

                <? if(!empty(data::getLoginInfo())) { ?>
                    <div id="top_left_alram" class="visible-mobile" style="position:absolute;right:0px;top:0px;height:25px;line-height:25px;">
                        <a href="/influencer/alarm/list.php"><i class="fa fa-bell"></i><span class="sound_only">알람</span>(<?=number_format($alarmCnt['cnt'])?>)</a>
                    </div>
                <? } ?>
            </div>
<!--            <div style="width:10%;height:25px;float:left;">-->
<!--                <button type="button" id="btn_cate"><i class="fa fa-bars"></i><span class="sound_only">분류열기</span></button>-->
<!--            </div>-->
        </div>

        <!-- @todo: [승대] 상품 모바일용 검색창 -->
        <div class="visible-mobile">
            <input type="text" id="txt_search_mobile" style="width:79%;" value="<?=$searchTxt?>"/>
            <button style="width:20%" id="btn_search_mobile">검색</button>
        </div>

        <div class="visible-pc">
            <!-- PC 상단 -->

            <? if(!empty(data::getLoginInfo())) { ?>
                <i class="fa fa-money">
                    <a href="/influencer/withdraw.php">
                        <span style="margin-left:0.2rem;"><?=number_format(data::getLoginMember()['mb_save_money'])?>원</span>
                    </a>
                </i>

                | <a>이용가이드</a>
                | <span><?=data::getLoginMember()['mb_name']?>님</span>
                | <a href="<?=G5_BBS_URL?>/logout.php">로그아웃</a>
                | <a>나의 찜</a>
                | <a>알림</a>
                | <a>마이페이지</a>
            <? } else { ?>
                <a class="btn_ol">로그인</a>
            <? } ?>

            | <a>문의센터</a>
        </div>


        <div id="category" class="menu">
            <button type="button" class="menu_close"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">카테고리닫기</span></button>
            <div class="btn_login">
                <input type="text" id="txt_search" placeholder="상품을 검색하세요" value="<?=$searchTxt?>"/>
                <button id="btn_search">검색</button>
            </div>

            <?=outlogin('theme/shop_basic', true) // 외부 로그인 ?>

            <div class="menu_wr">
                <ul class="cate">
                    <li>
                        <a>추천</a>
                    </li>
                    <li>
                        <a>이벤트</a>
                    </li>
                    <li>
                        <a>마이샵</a>
                    </li>
                    <li>
                        <a href="/influencer/report.php">리포트</a>
                    </li>
                    <li>
                        <a href="/influencer/withdraw.php">출금관리</a>
                    </li>
                </ul>

            </div>
        </div>

        <?=outlogin('theme/shop_basic', false) // 외부 로그인 ?>

             <div id="hd_sch">
                <button type="button" class="btn_close"><i class="fa fa-times"></i></button>
                <div class="hd_sch_wr">
                    <form name="frmsearch1" action="<?=G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">

                    <div class="sch_inner">
                        <h2>상품 검색</h2>
                        <label for="sch_str" class="sound_only">상품명<strong class="sound_only"> 필수</strong></label>
                        <input type="text" name="q" value="<?=stripslashes(get_text(get_search_string($q))); ?>" id="sch_str" required placeholder="검색어를 입력해주세요">
                        <button type="submit"  class="sch_submit"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only"> 검색</span></button>
                    </div>
                    </form>
                     <?php
                    $save_file = G5_DATA_PATH.'/cache/theme/jelly/keyword.php';
                    if(is_file($save_file)) include($save_file);
                    ?>
                    <?php if(!empty($keyword)) { ?>
                    <div id="ppl_word">
                        <h3>인기검색어</h3>
                        <ol class="slides">
                        <?php
                        $seq = 1;
                        foreach($keyword as $word) {
                        ?>
                            <li><a href="<?=G5_SHOP_URL; ?>/search.php?q=<?=urlencode($word); ?>"><?=get_text($word); ?></a></li>
                        <?php
                            $seq++;
                        }
                        ?>
                        </ol>
                    </div>
                <?php } ?>
                </div>
            </div>
<!--            <div id="hd_btn">-->
<!--                <button type="button" id="btn_sch"><i class="fa fa-search"></i><span class="sound_only">검색열기</span></button>-->
<!--                <a href="--><?php //echo G5_SHOP_URL; ?><!--/cart.php" id="btn_cartop"><i class="fa fa-shopping-cart"></i><span class="sound_only">장바구니</span><span class="cart-count">--><?php //echo get_boxcart_datas_count(); ?><!--</span></a>-->
<!--            </div>-->
        </div>

        <script>
        function search_submit(f) {
            if (f.q.value.length < 2) {
                alert("검색어는 두글자 이상 입력하십시오.");
                f.q.select();
                f.q.focus();
                return false;
            }

            return true;
        }
        </script>

   </div>

    <?php if (false) { ?>
        <div class="hd_admin"><a href="<?=G5_ADMIN_URL; ?>" target="_blank">관리자</a> <a href="<?=G5_THEME_ADM_URL ?>" target="_blank">테마관리</a></div>
    <?php } ?>

    <script>
    $( document ).ready( function() {
        var jbOffset = $( '#hd_wr' ).offset();
        $( window ).scroll( function() {
            if ( $( document ).scrollTop() > jbOffset.top ) {
                $( '#hd_wr' ).addClass( 'fixed' );
            }
            else {
                $( '#hd_wr' ).removeClass( 'fixed' );
            }
        });
    });

    $("#btn_cate").on("click", function() {
        $("#category").show();
    });

    $(".menu_close").on("click", function() {
        $(".menu").hide();
    });
     $(".cate_bg").on("click", function() {
        $(".menu").hide();
    });

     $(".btn_ol").on("click", function() {
        $(".ol").show();
    });

     $(".ol .btn_close").on("click", function() {
        $(".ol").hide();
    });

    $("#btn_sch").on("click", function() {
        $("#hd_sch").show();
    });

     $("#hd_sch .btn_close").on("click", function() {
        $("#hd_sch").hide();
    });

    $(function (){
        $("button.sub_ct_toggle").on("click", function() {
            var $this = $(this);
            $sub_ul = $(this).closest("li").children("ul.sub_cate");

            if($sub_ul.size() > 0) {
                var txt = $this.text();

                if($sub_ul.is(":visible")) {
                    txt = txt.replace(/닫기$/, "열기");
                    $this
                        .removeClass("ct_cl")
                        .text(txt);
                } else {
                    txt = txt.replace(/열기$/, "닫기");
                    $this
                        .addClass("ct_cl")
                        .text(txt);
                }

                $sub_ul.toggle();
            }
        });


        $(".content li.con").hide();
        $(".content li.con:first").show();
        $(".cate_tab li a").click(function(){
            $(".cate_tab li a").removeClass("selected");
            $(this).addClass("selected");
            $(".content li.con").hide();
            //$($(this).attr("href")).show();
            $($(this).attr("href")).fadeIn();
        });

        $("#btn_search, #btn_search_mobile").click(function() {
console.log($(this).attr("id"));
            var searchTxt = encodeURIComponent($("#" + ($(this).attr("id") === 'btn_search' ? 'txt_search' : 'txt_search_mobile')).val());
console.log(searchTxt);
            util.formSubmit('/influencer/search.php', [
                {name: 'qs',     value: searchTxt}
            ], {
                method: 'get',
                isNotIframe: true
            });
        });
    });
   </script>
</header>

<div id="container" class="container">
    <?php if (!defined('_INDEX_') && !empty($g5['title'])) { ?><?php } ?>
