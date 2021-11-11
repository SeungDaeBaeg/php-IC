
<div class="main_container_best">
    <a href="<?=$param['detail_url']?>">
        <div class="label">
            <p>판매당</p>
            <span>170,000</span>원
        </div>
        <div class="product_body">
            <img style="width:100%;height:auto;" alt="" src="<?=url::getThumbnailUrl($param['it_img1'])?>" />
        </div>
        <div class="product_foot">
            <p class="font_style_product_title"><?=$param['it_name']?></p>
            <span class="product_text main_font_color">
                <span class="font_eng_number"><?=util::percent($param['it_cust_price'], $param['it_price'])?></span><span class="product_text_small">%</span>
            </span>
            <span class="product_text">
                <span class="font_eng_number"><?=$param['it_price']?></span><span class="product_text_small">원</span>
            </span>
            <span class="product_text product_text_small">
                <span class="font_eng_number"><?=$param['it_cust_price']?></span>원
            </span>
        </div>
    </a>
</div>