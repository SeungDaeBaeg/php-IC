<?php
/**
 * @todo: [승대] 출금관리 화면 코딩 필요
 * 출금 관리 페이지
 */
include_once('../_common.php');

if(!data::isInfluencer()) {
    util::location('/');
}

define("_INDEX_", TRUE);
include_once(G5_SHOP_PATH.'/shop.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


$startDate = util::param('start_date') ?? date('Y-m-d', strtotime('-1 month'));
$endDate   = util::param('end_date') ?? date('Y-m-d');
$viewType  = util::param('view_type') ?? 'day';


$extraGroupby = '';
if($viewType === 'day') {
    $extraColumn    = "CONCAT(substr(yyyymmdd, 1, 4), '-', substr(yyyymmdd, 5, 2), '-', substr(yyyymmdd, 7, 2))";
    $extraGroupby   = 'group by yyyymmdd';
} else if($viewType === 'month') {
    $extraColumn    = "CONCAT(substr(yyyymmdd, 1, 4), '-', substr(yyyymmdd, 5, 2))";
    $extraGroupby   = 'GROUP BY substr(yyyymmdd, 1, 6)';
}


//출금 가능한 수익금
sql_fetch_arrays("
SELECT  {$extraColumn} as groupby, SUM(commission) AS total_commission
FROM    g5_translog
WHERE   yyyymmdd >= ?
AND     yyyymmdd <= ?
AND     mb_no = ?
AND     buy_confirm = 'Y'
{$extraGroupby}", $translog, array(
    str_replace('-', '', $startDate),
    str_replace('-', '', $endDate),
    data::getLoginMember()['mb_no']
));

//출금 이력
sql_fetch_arrays("
SELECT  {$extraColumn} as groupby, SUM(commission) AS total_commission
FROM    g5_payable
WHERE   yyyymmdd >= ?
AND     yyyymmdd <= ?
AND     paid = 'PAID'
AND     mb_no = ?
{$extraGroupby}", $payable, array(
    str_replace('-', '', $startDate),
    str_replace('-', '', $endDate),
    data::getLoginMember()['mb_no']
));

$list = array();

if(count($translog) > 0) {
    foreach($translog as $v) {
        $list[$v['groupby']]['enable'] = $v['total_commission'];
    }
}


if(count($payable) > 0) {
    foreach($payable as $v) {
        $list[$v['groupby']]['payable'] = $v['total_commission'];
    }
}

$totalEnable  = 0;
$totalPayable = 0;

?>

<!--content start-->

<!-- 수익금 정보 -->
<table border="1" style="width:100%;">
    <tr>
        <th colspan="2">수입금 정보</th>
    </tr>
    <tr>
        <th>오늘 발생한 수익금</th>
        <td><?=number_format($todayCommission)?>원</td>
    </tr>
    <tr>
        <th>출금 가능한 수익금</th>
        <td><?=number_format(data::getLoginMember()['mb_commission'])?>원</td>
    </tr>
</table>

<p style="text-align: center">
    <button id="btnWithdraw">출금신청</button>
</p>

<hr/>

<!-- 필터링 -->
<div style="text-align: center;">
    <select id="viewType">
        <option value="day"     <?=$viewType === 'day' ? 'selected' : ''?> >일별보기</option>
        <option value="month"   <?=$viewType === 'month' ? 'selected' : ''?>>월별보기</option>
    </select>
    <input type="text" id="startDate" readonly value="<?=$startDate?>" />
    <input type="text" id="endDate" readonly value="<?=$endDate?>"/>

    <button id="formSubmit">조회</button>

    <!-- 엑셀 다운로드는 구현안하기로 했음 -->
</div>

<hr/>

<!-- 실적 테이블 -->
<table border="1" style="width:100%;">
    <tr>
        <th>기간</th>
        <th>수익금</th>
        <th>출금</th>
        <th>출금가능</th>
    </tr>

    <? foreach($list as $ymd => $commission) { ?>
        <? $totalEnable += $commission['enable']; $totalPayable += $commission['payable']; ?>
        <tr>
            <th><?=$ymd?></th>
            <th><?=$commission['enable'] ?? 0?></th>
            <th><?=$commission['payable'] ?? 0?></th>
            <th><?=$commission['enable'] - $commission['enable'] ?? 0?></th>
        </tr>
    <? } ?>
    <tr>
        <th>합계</th>
        <th><?=$totalEnable?></th>
        <th><?=$totalPayable?></th>
        <th>0</th>
    </tr>
</table>

<script>
    $(function() {
        $("#start_date, #end_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

        $("#btnWithdraw").click(function() {
            util.alert(`
            <p>출금하시겠습니까?</p>
            <p>얼마? : <input type='number' id='withdrawCommission' value="0" /></p>
            `, {
                type: 'confirm',
                cb: function() {
                    var comm = parseInt($("#withdrawCommission").val());
                    if(comm <= 0) {
                        setTimeout(function() {
                            util.alert("값을 올바르게 입력해주세요.", {type:'instant'});
                        }, 500);
                        return false;
                    }

                    data.ajax('ajax.withdraw.php', {
                        commission: comm
                    }, function(res) {
                        setTimeout(function() {
                            util.alert(res.msg);
                        }, 500);
                    });
                }
            });
        });

        $("#formSubmit").click(function() {
            util.formSubmit('', [
                {name: 'view_type',  value: $("#viewType").val()},
                {name: 'start_date', value: $("#startDate").val()},
                {name: 'end_date',   value: $("#endDate").val()}
            ], {
                isNotIframe: true,
                method: 'get'
            });
        });
    });
</script>
<!--content end -->
<? include_once(G5_SHOP_PATH.'/shop.tail.php');
