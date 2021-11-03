<?php
/**
 * 상품 박스
 */
?>
<div>
    <a href="<?=$detail_url?>">
        <? if($recommend) { ?>
            <a class="btn-add-recommend" data-it-no="<?=$it_no?>">추천</a>
        <? } ?>
        
        <img style="width:100%;height:auto;" alt="" src="<?=url::getThumbnailUrl($it_img1)?>" />
        <p><b><?=$it_name?></b></p>
        <p>정상가 : <?=$it_cust_price?> / 할인가 : <?=$it_price?> / 할인율 : <?=util::percent($it_cust_price, $it_price)?>%</p>
    </a>
</div>