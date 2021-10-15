<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);
?>

<?php
$max_width = $max_height = 0;
$bn_first_class = ' class="bn_first"';
$bn_slide_btn = '';
$bn_sl = ' class="bn_sl"';

$main_banners = array();

for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $main_banners[] = $row;

    if ($i==0) echo '<div id="main_bn">'.PHP_EOL.'<ul class="slide-wrap">'.PHP_EOL;
    //print_r2($row);
    // 테두리 있는지
    $bn_border  = ($row['bn_border']) ? ' class="sbn_border"' : '';;
    // 새창 띄우기인지
    $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';

    $bimg = G5_DATA_PATH.'/banner/'.$row['bn_id'];
    if (file_exists($bimg))
    {
        $banner = '';
        $size = getimagesize($bimg);

        if($size[2] < 1 || $size[2] > 16)
            continue;

        if($max_width < $size[0])
            $max_width = $size[0];

        if($max_height < $size[1])
            $max_height = $size[1];

        echo '<li>'.PHP_EOL;
        if ($row['bn_url'][0] == '#')
            $banner .= '<a href="'.$row['bn_url'].'">';
        else if ($row['bn_url'] && $row['bn_url'] != 'http://') {
            $banner .= '<a href="'.G5_SHOP_URL.'/bannerhit.php?bn_id='.$row['bn_id'].'&amp;url='.urlencode($row['bn_url']).'"'.$bn_new_win.' >';
        
            echo $banner.'<span  style="background-image:url('.G5_DATA_URL.'/banner/'.$row['bn_id'].');" class="bn-img"><span class="bn-txt-wr"><span class="bn-txt">'.$row['bn_alt'].'<br><span class="btn_detail">자세히보기</span></span></span></span>';
            if($banner)
                echo '</a>'.PHP_EOL;
        }
        else {
            echo $banner.'<span  style="background-image:url('.G5_DATA_URL.'/banner/'.$row['bn_id'].');" class="bn-img"><span class="bn-txt-wr"><span class="bn-txt">'.$row['bn_alt'].'</span></span></span>';
        }
        echo '</li>'.PHP_EOL;

       
    }
}

if ($i > 0) {
    echo '</ul>'.PHP_EOL;

    echo '</div>'.PHP_EOL;
?>

<script>
jQuery(function($){
    var slider = $('.slide-wrap').show().bxSlider({
        speed:800,
        pager: true,
        auto: true,
        useCSS : false,
        controls:true,
        pager:true,
        adaptiveHeight: true,
        onSlideAfter : function(){
            slider.startAuto();
        }
    });
});
</script>

<?php
}
?>