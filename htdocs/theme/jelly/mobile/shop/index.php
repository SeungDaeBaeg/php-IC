<?php
include_once('./_common.php');

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>

<script src="<?php echo G5_JS_URL; ?>/swipe.js"></script>
<script src="<?php echo G5_JS_URL; ?>/shop.mobile.main.js"></script>

<?php echo display_banner('메인', 'mainbanner.10.skin.php'); ?>

<div class="idx_c">
     <?php include_once(G5_MSHOP_SKIN_PATH.'/main.event.skin.php'); // 이벤트 ?>
</div>
<?php echo display_banner('왼쪽', 'boxbanner.skin.php'); ?>
<div class="idx_c">
   

</div>



<div class="idx_c">
    <?php if($default['de_mobile_type3_list_use']) { ?>
    <div id="idx_new" class="sct_wrap">
        <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=3">최신상품</a></h2>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(3);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>
</div>
<?php if($default['de_mobile_type4_list_use']) { ?>
<div id="idx_best" class="sct_wrap">
    <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=4">인기상품</a></h2>
    <?php
    $list = new item_list();
    $list->set_mobile(true);
    $list->set_type(4);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_cust_price', true);
    $list->set_view('it_price', true);
    $list->set_view('it_icon', true);
    $list->set_view('sns', false);
    echo $list->run();
    ?>
</div>
<?php } ?>
<div class="idx_c" id="idx_tab">
    <ul class="tabs">
         <li class="tab-link current" data-tab="tab-1">히트상품</li>
         <li class="tab-link" data-tab="tab-2">추천상품</li>
         <li class="tab-link" data-tab="tab-3">할인상품</li>

    </ul>
    <?php if($default['de_mobile_type1_list_use']) { ?>
    <div class="sct_wrap current" id="tab-1">
            <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=1">히트상품</a></h2>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(1);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', false);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>



 
    <?php if($default['de_mobile_type2_list_use']) { ?>
    <div class="sct_wrap" id="tab-2">
        <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=2">추천상품</a></h2>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(2);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', false);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>



    <?php if($default['de_mobile_type5_list_use']) { ?>
    <div class="sct_wrap" id="tab-3">
        <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=5">할인상품</a></h2>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(5);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', false);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>

</div>

     
<!-- 메인리뷰-->
<?php
// 상품리뷰
$sql = " select a.is_id, a.is_subject, a.is_content, a.it_id, b.it_name
            from `{$g5['g5_shop_item_use_table']}` a join `{$g5['g5_shop_item_table']}` b on (a.it_id=b.it_id)
            where a.is_confirm = '1'
            order by a.is_id desc
            limit 0,5 ";
$result = sql_query($sql);

for($i=0; $row=sql_fetch_array($result); $i++) {
    if($i == 0) {
        echo '<div id="idx_review">'.PHP_EOL;
        echo '<h2><a href="'.G5_SHOP_URL.'/itemuselist.php">상품후기</a></h3>'.PHP_EOL;
        echo '<div class="review">'.PHP_EOL;
    }

    $review_href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
?>
    <div class="rv_li rv_<?php echo $i;?>">
        <div class="li_wr">
            <div class="rv_hd">
                <a href="<?php echo $review_href; ?>" class="prd_img"><?php echo get_itemuselist_thumbnail($row['it_id'], $row['is_content'], 50, 50); ?></a>
                <span class="rv_tit"><?php echo get_text(cut_str($row['is_subject'], 20)); ?></span>
                <a href="<?php echo $review_href; ?>" class="rv_prd"><?php echo $row['it_name']; ?></a>
            </div>
                
            <p><?php echo get_text(cut_str(strip_tags($row['is_content']), 100), 1); ?></p>
                
        </div>
    </div>
<?php
}

if($i > 0) {
    echo '</div>'.PHP_EOL;
    echo '</div>'.PHP_EOL;
}
?>



<script>
//상품 탭
$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.sct_wrap').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})

})

//후기
 $('.review').slick({
  dots: true,
  arrows: false,
  slidesToShow: 3,
  autoplay: true,
  autoplaySpeed: 2000,
  responsive: [
    {
      breakpoint: 970,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '20%',
        slidesToShow: 1
      }
    },
    {
      breakpoint: 670,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '10%',
        slidesToShow: 1
      }
    }
  ]
});

$("#container").removeClass("container").addClass("idx-container");
</script>

<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>