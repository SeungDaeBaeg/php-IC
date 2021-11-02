<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$today = date('Y-m-d');

$sql = "
SELECT bn_id,bn_url
FROM g5_shop_banner
WHERE bn_position = '아래'
AND bn_begin_time <= ? 
AND bn_end_time >= ?
AND bn_visible = 'Y'";

sql_fetch_arrays($sql,$banner_res,array($today,$today));

?>

<div>
    <?
        foreach($banner_res as $v) {
            echo util::component('bannerBox', $v);
        }
    ?>
</div>

<script>
    $('#banner_box input').click(function(){
        var type = $(this).data('type');
        if(type)
    })
</script>
