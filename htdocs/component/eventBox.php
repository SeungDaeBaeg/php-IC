<?php
/**
 * 이벤트 박스
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
            <div class="event_join_box" id="link_<?=$param['ev_id']?>" target-link="<?=$param['ev_link']?>" onclick="btnClick(this)">
                <div class="ev_link_icon"></div>
            </div>
        <? } else { ?>
            <div class="event_join_box" id="join_<?=$param['ev_id']?>" onclick="btnClick(this)">참여신청</div>
        <? } ?>
    </div>
</div>