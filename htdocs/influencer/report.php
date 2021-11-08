<?php
/**
 * 인플루언서 센터 리포트
 */
include_once('./_common.php');

define("_INDEX_", true);

include_once(G5_SHOP_PATH.'/shop.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


$groupby    = util::param('groupby') ?? 'day';
$buy_confirm= util::param('buy_confirm') ?? '';
$is_myshop  = util::param('is_myshop') ?? 'N';
$start_date = util::param('start_date') ?? date('Y-m-d', strtotime('-7 day'));
$end_date   = util::param('end_date') ?? date('Y-m-d');
$page       = util::param('page') ?? 1;

$extraWhere = "";

//마이샵
if($is_myshop === 'Y')  $extraWhere .= " AND from_purchase = 'MYSHOP' ";

//확정수익금
if($buy_confirm == 'Y') $extraWhere .= " AND buy_confirm = 'Y' ";

$extraGroupBy = "";
switch($groupby) {
    case 'day':
        //일별
        $extraColumn = "CONCAT(substr(yyyymmdd, 1, 4), '-', substr(yyyymmdd, 5, 2), '-', substr(yyyymmdd, 7, 2))";
        $extraGroupBy .= " GROUP BY yyyymmdd";
        break;
    case 'month':
        //월별
        $extraColumn = "CONCAT(substr(yyyymmdd, 1, 4), '-', substr(yyyymmdd, 5, 2))";
        $extraGroupBy .= " GROUP BY substr(yyyymmdd, 1, 4)";
        break;
    case 'product':
        //상품별
        $extraColumn = "(select it_name from g5_shop_item where it_id = g5_translog.it_no)";
        $extraGroupBy .= " GROUP BY it_no";
        break;
}

//페이지네이션 구현부
sql_fetch_data("
SELECT  COUNT(1) AS cnt, SUM(sales) as total_sales, SUM(commission) as total_commission
FROM    g5_translog
WHERE   mb_no = ?
AND     yyyymmdd BETWEEN ? AND ?" . $extraWhere, $translogCnt, array(
    data::getLoginMember()['mb_no'],
    str_replace('-', '', $start_date),
    str_replace("-", "", $end_date)
));

$list = util::pagination(
    $translogCnt['cnt'],
    $page,
    function($data) use($extraColumn, $extraWhere, $extraGroupBy, $start_date, $end_date)  {
        //데이터 구현부
        sql_fetch_arrays("
        SELECT  {$extraColumn} as groupby, count(1) as total_cnt, SUM(sales) as total_sales, SUM(commission) as total_commission
        FROM    g5_translog
        WHERE   mb_no = ?
        AND     yyyymmdd BETWEEN ? AND ?"
        . $extraWhere
        . $extraGroupBy .
        " LIMIT ?, ?", $translogs, array(
            data::getLoginMember()['mb_no'],
            str_replace('-', '', $start_date),
            str_replace("-", "", $end_date),
            $data['limit_idx'],
            $data['page_set']
        ));

        return $translogs;
    }
);

?>

<!--content start-->

<div style="width:100%;flot:left;background-color: #00C73C;padding:10px;">
    <select id="groupby">
        <option value="day"     <?=($groupby === 'day') ? 'selected' : ''?> >일별보기</option>
        <option value="month"   <?=($groupby === 'month') ? 'selected' : ''?> >월별보기</option>
        <option value="product" <?=($groupby === 'product') ? 'selected' : ''?> >상품별보기</option>
    </select>

    <select id="buy_confirm">
        <option value="Y" <?=($buy_confirm === 'Y') ? 'selected' : ''?> >확정수익금</option>
        <option value=""  <?=($buy_confirm === '') ? 'selected' : ''?>>예상수익금</option>
    </select>

    <input type="text" id="start_date" value="<?=$start_date?>" readonly /> ~ <input type="text" id="end_date" value="<?=$end_date?>" readonly />

    <label for="is_myshop">마이샵</label>
    <input type="checkbox" id="is_myshop" value="Y" <?=$is_myshop === 'Y' ? 'checked' : ''?> />
</div>

<div style="width:50%;float:left;background-color: #1ABC9C;text-align: center;padding:10px">판매액 <div><?=number_format($translogCnt['total_sales'])?></div></div>
<div style="width:50%;float:left;background-color: #00b0a2;text-align: center;padding:10px">수익금 <div><?=number_format($translogCnt['total_commission'])?></div></div>

<button id="report_submit" style="width:100%;">조회</button>

<table border="1" style="width:100%;">
    <tr>
        <th>일자</th>
        <th>판매수</th>
        <th>판매액</th>
        <th>수익금</th>
    </tr>
    <? foreach($list['list'] as $v) { ?>
        <tr>
            <td><?=$v['groupby']?></td>
            <td><?=number_format($v['total_cnt'])?></td>
            <td><?=number_format($v['total_sales'])?></td>
            <td><?=number_format($v['total_commission'])?></td>
        </tr>
    <? } ?>

    <tr>
        <!--페이지네이션-->
        <td colspan="4">
            <p style="text-align: center">
                <?=$list['pagination']?>
            </p>
        </td>
    </tr>
</table>

<script>
    $(function() {
        $("#start_date, #end_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

        $("#report_submit").click(function() {
            util.formSubmit('', [
                {name: 'groupby',    value: $("#groupby").val()},
                {name: 'buy_confirm',    value: $("#buy_confirm").val()},
                {name: 'start_date', value: $("#start_date").val()},
                {name: 'end_date',   value: $("#end_date").val()},
                {name: 'is_myshop',  value: $("#is_myshop:checked").val() ?? 'N'}
            ], {
                isNotIframe: true,
                method: 'get'
            });
        });
    });
</script>

<!--content end -->

<? include_once(G5_SHOP_PATH.'/shop.tail.php');
