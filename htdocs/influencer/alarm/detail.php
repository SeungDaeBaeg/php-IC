<?php
/**
 * 알람 상세 페이지
 */
require_once '../_common.php';

util::loginCheck();
$alarmId = util::param('id', "알람 ID가 없습니다.");

//읽은 시간 업데이트
sql_update("g5_alarm_detail", array(
    'readed_at' => date('Y-m-d H:i:s')
), "id = {$alarmId} and readed_at IS NULL");

include_once('head/alarm.php');

sql_fetch_data("
SELECT  a.msg
FROM    g5_alarm a 
JOIN    g5_alarm_detail ad
ON      a.id = ad.g5_alarm_id
AND     ad.mb_no = ?
AND     ad.id = ?", $alarmData, array(data::getLoginMember()['mb_no'], $alarmId));
?>

<!-- @todo: [승대] 알람 상세 페이지 코딩해야 함. PPT 19페이지 -->

<div style="width:100%;height:5em;background-color: #0099ff; padding-bottom: 5px;">
    <?=$alarmData['msg']?>
</div>

<button onclick="javascript:history.back();" style="width:100%;">뒤로가기</button>

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>