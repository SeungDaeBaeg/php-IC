<?php
/**
 * 마이샵 메인화면
 * @todo: [승대] 코딩 필요
 */


include_once('./_common.php');
include_once(G5_THEME_PATH . '/head.sub.php');

$mb_id = util::param("id");

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

//회원 정보 로드
$m = data::getLoginMember($mb_id);


//오늘 방문자 카운트
if(!$isAdmin) {
    sql_fetch_data("
    UPDATE  g5_influencer_myshop
    SET     in_today_count = in_today_count + 1
    WHERE   mb_no = ?", $r, array($mb_id));
}

//마이샵 정보 로드
sql_fetch_data("
SELECT  mb_no, in_myshop_name, in_myshop_cover_image, in_total_count, in_today_count, 
       (select ch_channel_type from g5_influencer_myshop_channel where mb_no = g5_influencer_myshop.mb_no and ch_channel_default = 'Y' limit 0, 1) as sns_default_type
FROM    g5_influencer_myshop
WHERE   mb_no = ?", $myshopData, array($m['mb_no']));

//마이샵이 없을 경우 설정 페이지로 이동
if(empty($myshopData)) {
    if($isAdmin) {
        util::location('/myshop/config.php');
    } else {
        //일반 회원
        util::location(G5_URL);
    }
}


//채널
$myshopName = $myshopData['in_myshop_name'] ?? $m['mb_name'] . '의 공구마켓';

//상품 정보 로드
$items = data::getAvailbleItems();
?>

<div style="width:100%; text-align: center;">
    <div style="width:80%;display: inline-block;">
        <button id="btnLinkBack">뒤로가기</button>

        <? if($isAdmin) { ?>
            <button id="btnConfig">설정</button>
        <? } ?>

        <!-- 제목 -->
        <h1><?=$myshopName?></h1>

        <!-- 배경커버 -->
        <img style="width:100%;height:auto;" src="<?=$myshopData['in_myshop_cover_image']?>" />

        <!-- SNS 뱃지 -->
        <img src="<?=url::getSnsChannelImage($myshopData['sns_default_type'])?>"/>

        <!-- 방문자 섹션 -->
        <div style="width:100%;background-color:red">
            전체 <?=number_format($myshopData['in_total_count'])?>명 / 오늘 <?=number_format($myshopData['in_today_count'])?>명
        </div>

        <button id="btnMyshopCopy" data-myshop-url="<?=url::getMyshopLink(data::getLoginMember()['mb_no'])?>">마이샵 링크</button>

        <h2>추천 상품</h2>

        <div id="recommendItemBoxList">
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
<script>
    $(function() {
        $("#btnConfig").click(function() {
            window.location = 'config.php';
        });

        $("#btnLinkBack").click(function() {
            window.location = '/';
        });

        $("#btnMyshopCopy").click(function() {
            util.clipboardCopy($(this).data('myshop-url'));

            util.alert("마이샵 링크를 복사하였습니다.", {
                type: 'instant'
            })
        });
    });
</script>
<?include_once(G5_SHOP_PATH.'/shop.tail.php');
