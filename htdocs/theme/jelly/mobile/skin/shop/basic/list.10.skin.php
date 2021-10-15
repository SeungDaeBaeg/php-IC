<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);
add_javascript('<script src="'.G5_THEME_JS_URL.'/jquery.shop.list.js"></script>', 10);
?>

<?php if(!defined('G5_IS_SHOP_AJAX_LIST') && $config['cf_kakao_js_apikey']) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>

<!-- 메인상품진열 10 시작 { -->
<?php
$is_gallery_list = ($this->ca_id && isset($_COOKIE['ck_itemlist'.$this->ca_id.'_type'])) ? $_COOKIE['ck_itemlist'.$this->ca_id.'_type'] : '';
if(!$is_gallery_list){
    $is_gallery_list = 'gallery';
}
$li_width = ($is_gallery_list === 'gallery') ? intval(100 / $this->list_mod) : 100;
$li_width_style = ' style="width:'.$li_width.'%;"';
$ul_sct_class = ($is_gallery_list === 'gallery') ? 'sct_10' : 'sct_20';

for ($i=0; $row=sql_fetch_array($result); $i++) {
    if ($i == 0) {
        if ($this->css) {
            echo "<ul id=\"sct_wrap\" class=\"{$this->css}\">\n";
        } else {
            echo "<ul id=\"sct_wrap\" class=\"sct ".$ul_sct_class." list_prd\">\n";
        }
    }

    if($i % $this->list_mod == 0)
        $li_clear = ' sct_clear';
    else
        $li_clear = '';

    echo "<li class=\"sct_li{$li_clear}\"><div class=\"li_wr is_view_type_list\">\n";

    echo "<div class=\"sct_img\">\n";

    if ($this->href) {
        echo "<a href=\"{$this->href}{$row['it_id']}\">\n";
    }

    if ($this->view_it_img) {
        echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
    }

      echo "<button type=\"button\" class=\"btn_wish\" data-it_id=\"{$row['it_id']}\"><span class=\"sound_only\">위시리스트</span><i class=\"fa fa-heart-o\" aria-hidden=\"true\"></i></button>\n";

    if ($this->href) {
        echo "</a>\n";
    }

    echo item_icon2($row);

    echo "</div>\n";

    echo "<div class=\"txt_wr\">\n";

    if ($this->view_it_id) {
        echo "<div class=\"sct_id\">&lt;".stripslashes($row['it_id'])."&gt;</div>\n";
    }


    if ($this->href) {
        echo "<div class=\"sct_txt\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
    }

    if ($this->view_it_name) {
        echo stripslashes($row['it_name'])."\n";
    }

    if ($this->href) {
        echo "</a></div>\n";
    }


    if ($this->view_it_basic && $row['it_basic']) {
        echo "<div class=\"sct_basic\">".stripslashes($row['it_basic'])."</div>\n";
    }

    if ($this->view_it_cust_price || $this->view_it_price) {

        echo "<div class=\"sct_cost\">\n";

        if ($this->view_it_cust_price && $row['it_cust_price']) {
            echo "<span class=\"sct_discount\">".display_price($row['it_cust_price'])."</span>\n";
        }

        if ($this->view_it_price) {
            echo display_price(get_price($row), $row['it_tel_inq'])."\n";
        }

        echo "</div>\n";

    }

    $s_core  =  (int)$row['it_use_avg']; 
    if ($s_core > 0 ) { 
        echo "<span class=\"sct_star\"><img src=".G5_SHOP_URL."/img/s_star".$s_core.".png></span>"; 
    } 

    echo "</div>\n";
    echo "</div></li>\n";
}

if ($i > 0) echo "</ul>\n";

if($i == 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>
<!-- } 상품진열 10 끝 -->

<?php if( !defined('G5_IS_SHOP_AJAX_LIST') ) { ?>
<script>
jQuery(function($){
    var li_width = "<?php echo intval(100 / $this->list_mod); ?>",
        img_width = "<?php echo $this->img_width; ?>",
        img_height = "<?php echo $this->img_height; ?>",
        list_ca_id = "<?php echo $this->ca_id; ?>";

    function shop_list_type_fn(type){
        var $ul_sct = $("ul.sct");

        if(type == "gallery") {
            $ul_sct.removeClass("sct_20").addClass("sct_10")
            .find(".sct_li").attr({"style":"width:"+li_width+"%"});
        } else {
            $ul_sct.removeClass("sct_10").addClass("sct_20")
            .find(".sct_li").removeAttr("style");
        }
        
        if (typeof g5_cookie_domain != 'undefined') {
            set_cookie("ck_itemlist"+list_ca_id+"_type", type, 1, g5_cookie_domain);
        }
    }

    $("button.sct_lst_view").on("click", function() {
        var $ul_sct = $("ul.sct");

        if($(this).hasClass("sct_lst_gallery")) {
            shop_list_type_fn("gallery");
        } else {
            shop_list_type_fn("list");
        }
    });
});

$(".container").removeClass("container");

</script>
<?php } ?>