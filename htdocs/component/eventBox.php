<?php
/**
 * 이벤트 리스트박스
 * @todo : [승대] PPT 28페이지, 이벤트 페이지 출력
 */
?>
<div class="event_item_box">
    <div class="event_item_thumbnail">
        <img src="<?=$img_url?>">
        <div class="event_badge">
            <?=$code_name?>
        </div>                
    </div>
    <div class="event_item_content">
        <div><?=$ev_subject?></div>
        <?=$ev_thumbnail_content?>
        <? if($ev_link) { ?>
            <div class="event_join_box" target-link="<?=$ev_link?>" data-type="link">
                <div class="ev_link_icon"></div>
            </div>
        <? } else { ?>
            <? if($party === '0') { ?>
                <div class="event_join_box" data-id="<?=$ev_id?>" data-type="join" data-sample=<?=$is_sample?>>참여신청</div>
            <? } else { ?>
                <div class="event_join_box" data-type="finish">참여완료</div>
            <? } ?>
        <? } ?>
    </div>
</div>