<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
}
else {
    $g5_head_title = $g5['title']; // 상태바에 표시될 제목
    $g5_head_title .= " | ".$config['cf_title'];
}

if($_SERVER['REMOTE_ADDR'] == SERVER_LOCAL) {
    $g5_head_title .= ' 로컬서버';
} else if($_SERVER['REMOTE_ADDR'] == SERVER_STAGING) {
    $g5_head_title .= ' 테스트서버';
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location'])
    $g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/'.G5_ADMIN_DIR.'/') || $is_admin == 'super') $g5['lo_url'] = '';

/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<?php
if (G5_IS_MOBILE) {
    echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">'.PHP_EOL;
    echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
    echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">'.PHP_EOL;
} else {
    echo '<meta http-equiv="imagetoolbar" content="no">'.PHP_EOL;
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">'.PHP_EOL;
}

if($config['cf_add_meta'])
    echo $config['cf_add_meta'].PHP_EOL;
?>
<title><?=$g5_head_title; ?></title>
<?php
$shop_css = '';
if (defined('_SHOP_')) $shop_css = '_shop';
echo '<link rel="stylesheet" href="'.G5_THEME_CSS_URL.'/'.(G5_IS_MOBILE?'mobile':'default').$shop_css.'.css?ver='.G5_CSS_VER.'">'.PHP_EOL;

?>


<link rel="stylesheet" href="<?=G5_THEME_URL?>/mobile/skin/shop/basic/style.css">

<!--[if lte IE 8]>
<script src="<?=G5_JS_URL ?>/html5.js"></script>
<![endif]-->
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "<?=G5_URL ?>";
var g5_bbs_url   = "<?=G5_BBS_URL ?>";
var g5_is_member = "<?=isset($is_member)?$is_member:''; ?>";
var g5_is_admin  = "<?=isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile = "<?=G5_IS_MOBILE ?>";
var g5_bo_table  = "<?=isset($bo_table)?$bo_table:''; ?>";
var g5_sca       = "<?=isset($sca)?$sca:''; ?>";
var g5_editor    = "<?=($config['cf_editor'] && $board['bo_use_dhtml_editor'])?$config['cf_editor']:''; ?>";
var g5_cookie_domain = "<?=G5_COOKIE_DOMAIN ?>";
var g5_theme_shop_url = "<?=G5_THEME_SHOP_URL; ?>";
var g5_logined_id = "<?=data::getLoginMember()['mb_no']?>";

</script>
<script src="<?=G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script src="<?=G5_JS_URL ?>/lodash.js?ver=<?=G5_JS_VER; ?>"></script>
<? if (defined('_SHOP_') && !G5_IS_MOBILE) { ?>
    <script src="<?=G5_JS_URL ?>/jquery.shop.menu.js?ver=<?=G5_JS_VER; ?>"></script>
<? } else { ?>
    <script src="<?=G5_JS_URL ?>/jquery.menu.js?ver=<?=G5_JS_VER; ?>"></script>
<? } ?>
<script src="<?=G5_JS_URL ?>/common.js?ver=<?=G5_JS_VER; ?>"></script>
<script src="<?=G5_JS_URL ?>/influencer_common.js"></script>
<script src="<?=G5_JS_URL ?>/wrest.js?ver=<?=G5_JS_VER; ?>"></script>
<script src="<?=G5_JS_URL ?>/placeholders.min.js"></script>
<script src="<?=G5_JS_URL ?>/jquery.bxslider.js"></script>
<link rel="stylesheet" href="<?=G5_JS_URL ?>/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?=G5_CSS_URL ?>/alert.css">
<?php
if(G5_IS_MOBILE) {
    echo '<script src="'.G5_JS_URL.'/modernizr.custom.70111.js"></script>'.PHP_EOL; // overflow scroll 감지
}
if(!defined('G5_IS_ADMIN'))
    echo $config['cf_add_script'];
?>
</head>
<body<?=isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>

<!-- form submit용 hidden iframe -->
<iframe name="formSubmitIframe" style="visibility: hidden;display: none;"></iframe>

<?php
//[IC] 공통 컴포넌트 로드
echo util::component('common/alert');
?>