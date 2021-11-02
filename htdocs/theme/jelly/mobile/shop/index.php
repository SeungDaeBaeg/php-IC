<?php
include_once('./_common.php');

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>

<script src="<?=G5_JS_URL; ?>/swipe.js"></script>
<script src="<?=G5_JS_URL; ?>/shop.mobile.main.js"></script>

<?=display_banner('메인', 'mainbanner.10.skin.php'); ?>


<!-- 중간 메뉴 -->
<div class="idx_c">
    <?php include_once(G5_MSHOP_SKIN_PATH . "/main.middle.menu.php"); ?>
</div>

<!-- 배너 -->
<div class="idx_c">
    <?php include_once(G5_MSHOP_SKIN_PATH . "/main.banner.php"); ?>
</div>

<!-- 추천 상품 -->
<div class="idx_c">
    <?php include_once(G5_MSHOP_SKIN_PATH.'/main.recommend.product.php'); ?>
</div>

<script>
    $("#container").removeClass("container").addClass("idx-container");
</script>

<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>