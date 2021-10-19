<?php
/**
 * 추천 상품 메인 리스트
 */

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$query = "
SELECT  it_img1, it_name, it_img1
FROM    g5_shop_item
WHERE   it_soldout = 0
AND     it_stock_qty > 0";
//$res    = sql_query($query);
//$rows   = sql_fetch_array($res);


sql_fetch_arrays("
    SELECT  it_id, it_img1, it_name, it_cust_price, it_price
    FROM    g5_shop_item
    WHERE   it_soldout = 0
    AND     it_stock_qty > 0
", $res);
?>

<?php foreach($res as $v) { ?>
    <!-- @todo : [승대] PPT 6페이지, DESC 7번-->
    <div>
        <a href="<?=url::getDetailUrl($v['it_id'])?>">
            <img style="width:100%;height:auto;" alt="" src="<?=url::getThumbnailUrl($v['it_img1'])?>" />
            <p><b><?=$v['it_name']?></b></p>
            <p>정상가 : <?=$v['it_cust_price']?> / 할인가 : <?=$v['it_price']?> / 할인율 : <?=util::percent($v['it_cust_price'], $v['it_price'])?>%</p>
        </a>
    </div>
<?php } ?>