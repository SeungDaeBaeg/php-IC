<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_INFLUENCER_URL.'/event/event.css">', 0);
add_javascript('<script src="'.G5_URL.'/js/influencer_common.js"></script>', 0);

$ev_id = $_GET['ev_id'] ?? '';

$sql = "select options, is_sample from g5_shop_party where ev_id = '".$ev_Id."'";

sql_fetch_data($sql,$res);
var_dump($res);

?>

<!--content start-->

<script>

</script>

<!--content end -->

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>