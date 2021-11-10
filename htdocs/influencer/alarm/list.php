<?php
/**
 * 알람 리스트 페이지
 */

include_once('head.php');

sql_fetch_arrays("
SELECT  ad.id, a.title, a.created_at
FROM    g5_alarm a 
JOIN    g5_alarm_detail ad
ON      a.id = ad.g5_alarm_id
AND     ad.mb_no = ?", $alarmList, array(data::getLoginMember()['mb_no']));

?>

<!-- @todo: [승대] 알람 리스트 페이지 코딩해야 함. PPT 19페이지 -->

<? if(count($alarmList) > 0) { ?>
    <? foreach($alarmList as $v) { ?>
        <div style="width:100%;height:5em;background-color: #0099ff; padding-bottom: 5px;">
            <a href="/influencer/alarm/detail.php?id=<?=$v['id']?>">
                <p><?=$v['title']?></p>
                <p style="text-align: right;"><?=$v['created_at']?></p>
            </a>
        </div>
    <? } ?>
<? } else { ?>
    <!-- 알람 없음 -->
    <div style="text-align: center;">
        알람메세지가 존재하지 않습니다.
    </div>
<? } ?>


<? include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');