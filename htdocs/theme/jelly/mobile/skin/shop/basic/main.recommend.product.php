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

foreach($res as $v) {
    // @todo : [승대] PPT 6페이지, DESC 7번
    echo util::component('itemBox', array(
        'it_id'         => $v['it_id'],
        'detail_url'    => url::getDetailUrl($v['it_id']),
        'it_img1'       => $v['it_img1'],
        'it_name'       => $v['it_name'],
        'it_cust_price' => $v['it_cust_price'],
        'it_price'      => $v['it_price']
    ));
}