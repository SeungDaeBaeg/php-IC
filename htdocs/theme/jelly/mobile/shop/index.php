<?php
include_once('./_common.php');

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>

<script src="<?=G5_JS_URL; ?>/swipe.js"></script>
<script src="<?=G5_JS_URL; ?>/shop.mobile.main.js"></script>

<div class="mainbanner_box">
    <?=display_banner('메인', 'mainbanner.10.skin.php'); ?>
    <div id="main-counter">
        <span class="current">01</span><span class="counter_icon01">/</span><span class="total"></span><span class="counter_icon02">+</span>
    </div>
</div>

<!-- 중간 메뉴 -->
<div class="idx_c">
    <?php include_once(G5_MSHOP_SKIN_PATH . "/main.middle.menu.php"); ?>
</div>
<div class="main_container_bar"></div>
<!-- 추천 상품 -->
<div class="idx_c">
    <div class="font_style_title">
        인버스트샵의 <span class="main_font_color">베스트 상품</span>
    </div>
    <?php include_once(G5_MSHOP_SKIN_PATH.'/main.recommend.product.php'); ?>
</div>

<script>
    $("#container").removeClass("container").addClass("idx-container");
</script>

<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>