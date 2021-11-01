<?php
/**
 * 이벤트 리스트박스
 * @todo : [승대] PPT 28페이지, 이벤트 페이지 출력
 */
?>
<div class="event_item_box">
    <div class="event_item_thumbnail">
        <img src="<?=$param['img_url']?>">
        <div class="event_badge">
            <?=$param['code_name']?>
        </div>                
    </div>
    <div class="event_item_content">
        <div><?=$param['ev_subject']?></div>
        <?=$param['ev_thumbnail_content']?>
        <? if($param['ev_link']) { ?>
            <div class="event_join_box" target-link="<?=$param['ev_link']?>" data-id="link_true">
                <div class="ev_link_icon"></div>
            </div>
        <? } else { ?>
            <div class="event_join_box" data-id="join_<?=$param['ev_id']?>">참여신청</div>
        <? } ?>
    </div>
</div>