<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/naverpay.lib.php');

$query = $_SERVER['QUERY_STRING'];

$vars = array();

foreach(explode('&', $query) as $pair) {
    list($key, $value) = explode('=', $pair);
    $key = urldecode($key);
    $value = preg_replace("/[^A-Za-z0-9\-_]/", "", urldecode($value));
    $vars[$key][] = $value;
}

$itemIds = $vars['ITEM_ID'];

if (count($itemIds) < 1) {
    exit('ITEM_ID 는 필수입니다.');
}

header('Content-Type: application/xml;charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<response>
<?php
foreach($itemIds as $it_id) {
    $it = get_shop_item($it_id, true);
    if(!$it['it_id'])
        continue;

    $id          = $it['it_id'];
    $name        = $it['it_name'];
    $description = $it['it_basic'];
    $price       = get_price($it);
    $image       = get_naverpay_item_image_url($it_id);
    $quantity    = get_naverpay_item_stock($it_id);
    $ca_name     = '';
    $ca_name2    = '';
    $ca_name3    = '';
    $returnInfo  = get_naverpay_return_info($it['it_seller']);
    $option      = get_naverpay_item_option($it_id, $it['it_option_subject']);

    if($it['ca_id']) {
        $cat = sql_fetch(" select ca_name from g5_shop_category where ca_id = '{$it['ca_id']}' ");
        $ca_name = $cat['ca_name'];
    }
    if($it['ca_id2']) {
        $cat = sql_fetch(" select ca_name from g5_shop_category where ca_id = '{$it['ca_id2']}' ");
        $ca_name2 = $cat['ca_name'];
    }
    if($it['ca_id3']) {
        $cat = sql_fetch(" select ca_name from g5_shop_category where ca_id = '{$it['ca_id3']}' ");
        $ca_name3 = $cat['ca_name'];
    }
?>
<item id="<?=$id; ?>">
<?php if($it['ec_mall_pid']) { ?>
<mall_pid><![CDATA[<?=$it['ec_mall_pid']; ?>]]></mall_pid>
<?php } ?>
<name><![CDATA[<?=$name; ?>]]></name>
<url><?=shop_item_url($it_id); ?></url>
<description><![CDATA[<?=$description; ?>]]></description>
<image><?=$image; ?></image>
<thumb><?=$image; ?></thumb>
<price><?=$price; ?></price>
<quantity><?=$quantity; ?></quantity>
<category>
<first id="MJ01"><![CDATA[<?=$ca_name; ?>]]></first>
<second id="ML01"><![CDATA[<?=$ca_name2; ?>]]></second>
<third id="MN01"><![CDATA[<?=$ca_name3; ?>]]></third>
</category>
<?=$option; ?>
<?=$returnInfo; ?>
</item>
<?php
}
echo('</response>');