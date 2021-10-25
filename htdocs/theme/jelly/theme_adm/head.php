<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once('./head.sub.php');
add_stylesheet('<link rel="stylesheet" href="./css/style.css">', 0);

$adm_menu = array(
    array('url' => 'footerinfo.php',   'text' => '하단정보'),
    array('url' => 'keyword.php',   'text' => '인기검색어'),
);
?>

<div id="head">
    <h1><a href="./index.php">jelly 테마관리</a></h1>
    <div class="go_home"><a href="<?=G5_URL ?>">홈으로가기</a></div>
    <button type="button" class="al_mn"><i class="fa fa-bars" aria-hidden="true"></i></button>
    <div id="left_menu">
        <h2>테마설정메뉴</h2>
        <ul>
        <?php
        foreach($adm_menu as $menu) {
            if(basename($_SERVER['SCRIPT_NAME']) == $menu['url'])
                $menu_on = ' class="menu_on"';
            else
                $menu_on = '';
        ?>
            <li><a href="./<?=$menu['url']; ?>"<?=$menu_on; ?>><?=$menu['text']; ?></a></li>
        <?php
        }
        ?>
        </ul>
    </div>
</div>

<script>
$(".al_mn").click(function(){
    $("#left_menu").toggle();
});
</script>
<div id="container">
    <h2 id="container_title"><?=$g5['title'] ?></h2>
    <div class="container_wr">