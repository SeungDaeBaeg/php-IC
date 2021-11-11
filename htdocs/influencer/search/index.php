<?php
/**
 * 검색 결과 페이지
 *
 * @todo: [승대] 검색결과 페이지 코딩 필요
 */
include_once('../_common.php');

define("_INDEX_", true);
include_once(G5_THEME_MOBILE_PATH.'/shop/shop.head.php');

$qs         = util::param("qs");
$sort       = util::param("sort");
$recommend  = util::param("recommend");
$wish       = util::param("wish");
$category   = util::param("category");
$event      = util::param("event");


$where    = "";

if(!empty($recommend)) {
    // 추천상품 필터
    $where .= " AND it_recommend = 'Y'";

    $title = "추천상품";
} else if(!empty($wish)) {
    //찜한 상품 필터
    $mb_id  = data::getLoginMember()['mb_id'];
    $where .= " AND it_id IN (SELECT it_id FROM g5_shop_wish WHERE mb_id='{$mb_id}' AND wi_use = 1)";

    $title = "나의 찜";
} else {
    $title = "검색 결과";
}

// 검색어 필터
if(!empty($qs)) {
    $qs       = explode(" ", trim(urldecode($qs)));
    $qs_where = implode("|", $qs);
    $where    .= " AND it_name REGEXP '".$qs_where."'";
}

// 이벤트 필터
if(!empty($event)) {
    $events  = explode(",", trim($event));
    $where  .= " AND ev_id IN (SELECT ev_id FROM g5_shop_event WHERE ev_type IN ('".implode("','", $events)."'))";
} else {
    $events = array();
}

// 카테고리 필터
if(!empty($category)) {
    $categorys   = explode(",", trim($category));
    $where      .= " AND ca_id IN ('".implode("','", $categorys)."')";
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

    <button id="btn_top_filter" class="btn_top_filter" style="width:100%;">검 색 적 용</button>
</div>
<!-- 상세필터 버튼 end -->

<h2><?=$title?></h2>
<? if(!empty($wish)) { ?>
    <input type="checkbox" name="wish_all"> <span>일괄선택</span>
    <button id="btn_wish_cancel">찜하기 취소</button>
    <button id="btn_myshop_get">마이샵 담기</button>
<? } ?>

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
        'it_price'      => $v['it_price'],
        'wish'          => $wish
    ));
}
?>

<script>
    $("button#btn_top_filter").click(function() {
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

        if(!_.isEmpty(sort)) qs = _.set(qs, 'sort', sort);
        qs = _.set(qs, 'event', events.length > 0 ? events.join(',') : '');
        qs = _.set(qs, 'category', categorys.length > 0 ? categorys.join(',') : '');

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

    // 나의찜 화면에서 일괄선택 로직
    $('input[name=wish_all]').change(function(e) {
        var t = $('input[name=wish]');
        t.each(function() {
            $(this)[0].checked = e.target.checked;
        });
    });

    // 나의찜 화면에서 찜하기취소 버튼 클릭
    $('button#btn_wish_cancel').click(function() {
        // 체크된 상품들 배열 푸쉬
        var t = $('input[name=wish]');
        var ids = [];
        t.each(function() {
            var el = $(this)[0];
            if(el.checked) ids.push(el.value+'');
        });
        
        if(ids.length === 0) {
            util.alert('찜하기 취소할 아이템이 없습니다.',{type:'instant'});
            return;
        }

        data.ajax('/myshop/ajax.setWish.php', {
            it_id: ids,
        }, function(res) {
            if(res.code === 0) {
                util.alert('찜하기 취소를 하였습니다.',{cb:function(){location.reload();}});
            }
        });
    });

    //마이샵 담기
    $("#btn_myshop_get").click(function() {

        var it_ids = [];

        $(".chk_wish:checked").each(function() {
            it_ids.push($(this).val());
        });

        data.ajax('ajax.setMyshop.php', {
            it_ids: it_ids
        }, function(res) {
            util.alert(res.msg, {
                type: 'instant'
            });
        });
    });
</script>

<!--content end -->

<?
include_once(G5_THEME_MOBILE_PATH . '/shop/shop.tail.php');
