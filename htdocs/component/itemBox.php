<?php
/**
 * 상품 박스
 */
?>
<div>
    <a href="<?=$param['detail_url']?>">
        <img style="width:100%;height:auto;" alt="" src="<?=url::getThumbnailUrl($param['it_img1'])?>" />
        <p><b><?=$param['it_name']?></b></p>
        <p>정상가 : <?=$param['it_cust_price']?> / 할인가 : <?=$param['it_price']?> / 할인율 : <?=util::percent($param['it_cust_price'], $param['it_price'])?>%</p>
    </a>
</div>