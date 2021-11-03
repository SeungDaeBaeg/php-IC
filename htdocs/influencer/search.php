<?php
/**
 * 검색 결과 페이지
 *
 * @todo: [승대] 검색결과 페이지 코딩 필요
 */
include_once('./_common.php');

define("_INDEX_", true);
include_once(G5_SHOP_PATH.'/shop.head.php');

$qs       = util::paramCheck("qs");
$sort     = util::paramCheck("sort");
$category = util::paramCheck("category");
$event    = util::paramCheck("event");


$where    = "";

// 검색어 필터
if(!empty($qs)) {
    $qs       = explode(" ", trim(urldecode($qs)));
    $qs_where = implode("|", $qs);
    $where    .= "AND it_name REGEXP '".$qs_where."'";
}

// 이벤트 필터
if(!empty($event)) {
    $events  = explode("|", trim($event));
    $where  .= "AND ev_id IN (SELECT ev_id FROM g5_shop_event WHERE ev_type IN ('".implode("','", $events)."'))";
} else {
    $events = array();
}

// 카테고리 필터
if(!empty($category)) {
    $categorys   = explode("|", trim($category));
    $where      .= "AND ca_id IN ('".implode("','", $categorys)."')";
} else {
    $categorys   = array();
}

// 정렬
if(!empty($sort)) {
    switch ($sort) {
        case '1':
            //판매량
            $where .= "ORDER BY it_sum_qty DESC'";
            break;
        case '2':
            //수익금순
            $where .= "ORDER BY (it_price * it_sum_qty) DESC";
            break;
        case '3':
            //낮은가격
            $where .= "ORDER BY it_price ASC";
            break;
        case '4':
            //최신순
            $where .= "ORDER BY it_time DESC";
            break;
        case '5':
            //높은 가격순
            $where .= "ORDER BY it_price DESC";
            break;
    }
}


// 쿼리
$items = data::getAvailbleItems($where);

// 카테고리 정보 로드
sql_fetch_arrays("
SELECT  ca_id, ca_name
FROM    g5_shop_category", $category);

$sortArr = array(
    '1' => '판매량순',
    '2' => '수익금순',
    '3' => '낮은가격순',
    '4' => '최신순',
    '5' => '높은가격순'
);

$eventArr = array(
    'grb'   => '공동구매',
    'exp'   => '체험단',
    'exh'   => '기획전',
    'rep'   => '기자단',
    'prc'   => '할인쿠폰'
);

?>

<!--content start-->

<!-- @todo: [승대] 가로 필터링 PPT 21 페이지-->
<div style="width:100%;height:20px;">
    <? foreach($sortArr as $key => $name) { ?>
        <button class="btn_top_filter" data-filter="<?=$key?>" <?=$key == $sort ? 'check!':''?> ><?=$name?></button>
    <? } ?>

    <button id="btn_detail_filter">상세필터 버튼</button>
</div>

<!-- 상세필터 버튼 start -->
<div id="div_detail_filter" style="display: none; width:100%;height:400px; background-color: #1ABC9C">
    <h2>이벤트</h2>

    <? foreach($eventArr as $key => $name) { ?>
        <p><input type="checkbox" name="event" value="<?=$key?>" <?=in_array($key, $events) ? 'checked':''?> /><?=$name?></p>
    <? } ?>

    <h2>카테고리</h2>
    <? foreach($category as $v) { ?>
        <p><input type="checkbox" name="category" value="<?=$v['ca_id']?>" <?=in_array($v['ca_id'], $categorys) ? 'checked':''?>  /> <?=$v['ca_name']?></p>
    <? } ?>

    <button class="btn_top_filter" style="width:100%;">검 색 적 용</button>
</div>
<!-- 상세필터 버튼 end -->

<h2>검색 결과</h2>

<? if(count($items) <= 0) { ?>
    <p>검색결과가 존재하지 않습니다.</p>
<? } ?>

<?
foreach($items as $v) {
    echo util::component('itemBox', array(
        'it_id'         => $v['it_id'],
        'detail_url'    => url::getDetailUrl($v['it_id']),
        'it_img1'       => $v['it_img1'],
        'it_name'       => $v['it_name'],
        'it_cust_price' => $v['it_cust_price'],
        'it_price'      => $v['it_price']
    ));
}
?>

<script>
    $("button.btn_top_filter").click(function() {
        var qs = url.getUrlParams();
        var sort = $(this).data("filter") ?? url.getUrlParam('sort');

        var events = [];
        var categorys = [];
        $("input[name='event']:checked").each(function() {
            events.push($(this).val());
        });
        $("input[name='category']:checked").each(function() {
            categorys.push($(this).val());
        });

        qs = _.set(qs, 'sort', sort);
        if(events.length > 0)    qs = _.set(qs, 'event', events.join('|'));
        if(categorys.length > 0) qs = _.set(qs, 'category', categorys.join('|'));

        window.location = '?' + url.httpBuildQuery(qs);
    });

    $("button#btn_detail_filter").click(function() {
        var $div = $("div#div_detail_filter");
        if($div.is(":visible")) {
            $div.hide();
        } else {
            $div.show();
        }
    });
</script>

<!--content end -->

<?
include_once(G5_SHOP_PATH.'/shop.tail.php');
