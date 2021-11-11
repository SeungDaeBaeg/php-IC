<?php
/**
 * 상품 박스
 */
?>
<div class="itemBox" style="position:relative;">
    <a href="<?=$detail_url?>">
        <? if($recommend) { ?>
            <div class="btn_add_recommend" data-it-id="<?=$it_id?>">추천</div>
        <? } ?>
        <? if($wish) { ?>
            <input type="checkbox" name="wish" value="<?=$it_id?>" class="chk_wish">
            <div class="btn_wish" data-it-id="<?=$it_id?>">찜</div>
        <? } ?>
        <img class="img_wish" alt="" src="<?=url::getThumbnailUrl($it_img1)?>" />
        <p><b><?=$it_name?></b></p>
        <p>정상가 : <?=$it_cust_price?> / 할인가 : <?=$it_price?> / 할인율 : <?=util::percent($it_cust_price, $it_price)?>%</p>
    </a>
</div>