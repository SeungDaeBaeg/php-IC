<?php
/**
 * 상품 박스
 */
?>
<div class="itemBox" style="position:relative;">
    <a href="<?=$detail_url?>">
        <? if($recommend) { ?>
            <div class="btn-add-recommend" data-it-id="<?=$it_id?>" style="position: absolute;left:5px;top:0px;width:5rem;height:5rem;background-color: red">추천</div>
        <? } ?>
        <? if($wish) { ?>
            <input type="checkbox" name="wish" value="<?=$it_id?>" style="position: absolute;right:0px;top:0px;">
            <div class="btn-wish" data-it-id="<?=$it_id?>" style="position: absolute;right:0px;bottom:38px;width:5rem;height:5rem;background-color: red">찜</div>
        <? } ?>
        <img style="width:100%;height:auto;" alt="" src="<?=url::getThumbnailUrl($it_img1)?>" />
        <p><b><?=$it_name?></b></p>
        <p>정상가 : <?=$it_cust_price?> / 할인가 : <?=$it_price?> / 할인율 : <?=util::percent($it_cust_price, $it_price)?>%</p>
    </a>
</div>