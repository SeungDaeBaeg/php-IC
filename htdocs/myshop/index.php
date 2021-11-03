<?php
/**
 * 마이샵 메인화면
 * @todo: [승대] 코딩 필요
 */


include_once('./_common.php');
include_once(G5_THEME_PATH.'/head.sub.php');

$mb_id = util::paramCheck("id");

//관리자 화면
$isAdmin = false;
if(empty($mb_id)) {
    $mb_id = data::getLoginMember()['mb_id'];
    if(empty($mb_id)) {
        util::alert("로그인해주세요.");
        util::location(G5_URL);
    }

    $isAdmin = true;
}

if(!data::isInfluencer($mb_id)) {
    util::alert("해당 회원은 인플루언서가 아닙니다.");
    util::location(G5_URL);
}

$m = data::getLoginMember($mb_id);

//상품 정보 로드
$items = data::getAvailbleItems();
?>



<div style="width:100%; text-align: center;">
    <? if($isAdmin) { ?>
        <button>설정</button>
    <? } ?>


    <div style="width:80%;display: inline-block;">

        <!--제목-->
        <h1><?=$m['mb_name']?>의 공구마켓</h1>

        <!--배경커버 -->
        <img style="width:100%;height:auto;" src="https://ic.linkprice.com/data/banner/4" />

        <!-- 방문자 섹션 -->
        <div style="width:100%;background-color:red">
            전체 0명 / 오늘 0명
        </div>

        <h2>추천 상품</h2>

        <div>
            <?
            foreach($items as $v) {
                echo util::component("itemBox", array(
                    'it_id'         => $v['it_id'],
                    'detail_url'    => url::getDetailUrl($v['it_id']),
                    'it_img1'       => $v['it_img1'],
                    'it_name'       => $v['it_name'],
                    'it_cust_price' => $v['it_cust_price'],
                    'it_price'      => $v['it_price'],
                    'recommend'     => $isAdmin
                ));
            }
            ?>
        </div>
    </div>
</div>
<?
include_once(G5_SHOP_PATH.'/shop.tail.php');
