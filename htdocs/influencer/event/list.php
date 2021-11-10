<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
include_once('./event.header.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/event/event.css">', 0);

$ajax_event_url = G5_INFLUENCER_URL.'/event/ajax.event.php';
$event_list_url = G5_INFLUENCER_URL.'/event/list.php';
$img_src_url = G5_DATA_URL.'/event/';

//codeName 불러오기
$sql = "
SELECT code, code_name from g5_code 
WHERE meta_code = 'event' 
AND del_yn = 'N'";

sql_fetch_arrays($sql,$codes);

$type = $_GET['type'] ?? 'all';
$order = $_GET['order'] ?? 'new';
$order_name = '';
$today = date('Y-m-d');

$event_join = $_SESSION['event_join'];
unset($_SESSION['event_join']);

if($event_join === 'OK') {
    util::alert('이벤트 참여 신청이 되었습니다.');
}
else if($event_join === 'FAIL') {
    util::alert('이벤트 참여 신청이 안되었습니다.<br>관리자에게 연락바랍니다.');
}

$user = data::getLoginMember();
$mb_no = $user['mb_no'];
$mb_id = $user['mb_id'];

//mb_id는 로그인한 유저가 있을 경우 해당 이벤트를 참여했는지 안 했는지
$sql = "
SELECT event.ev_id, ev_subject, ev_thumbnail_content, private_id,
(select code_name from g5_code where code = ev_type and meta_code = 'event' and del_yn = 'N') code_name, ev_link, 
(select count(*) from g5_shop_party_join where ev_id = event.ev_id and mb_no = ?) party,
(select is_sample from g5_shop_party where ev_id = event.ev_id) is_sample 
FROM g5_shop_event event
WHERE ev_start_date <= ? 
AND ev_end_date >= ? 
AND ev_use = 1
AND (private_id is null OR private_id in (?))";

if($type !== 'all') {
    if($type === 'pri') {
        $sql .= " and private_id = '$mb_id'";
    }
    else {
        $sql .= " and ev_type = '".$type."'";
    }    
}

if($order === 'new') {
    $sql .= " order by ev_id desc";
    $order_name = '최신순';
}
else if($order === 'deadline') {
    $sql .= " order by ev_end_date";
    $order_name = '마감순';
}

sql_fetch_arrays($sql, $eventBox, array($mb_no,$today,$today,$mb_id));

?>
<?php
    /**
     * @todo : [승대] PPT 28페이지
     * 이벤트 페이지
     */
?>
<!--content start-->
<div class="event_box" id="event_box">
    <div class="event_top_box" id="event_top_box">
        <div class="event_type_box">
            <div data-id="all" data-type="type">전체</div>
            <?
                foreach($codes as $v) {
                    echo util::component('eventCodeBox', $v);
                }
            ?>
        </div>
        <div class="event_order_box">
            <div data-id="pri" data-type="type">프라이빗</div>
            <div class="selected new" data-id="<?=$order?>" data-type="order"><?=$order_name?></div>
        </div>
    </div>
    <div class="event_list_box" id="listBox">
        <?
            foreach($eventBox as $v) {
                $v['img_url'] = $img_src_url.$v['ev_id'].'_m';
                echo util::component('eventBox', $v);
            }
        ?>
    </div>
</div>

<script>
    $('#event_box div[data-type]').click(function(){
        var _this = $(this);
        var data_type = _this.data('type');
        var data_id = _this.data('id');
        switch(data_type) {
            case 'type':
                var order = url.getUrlParam('order');
                var event_list_url = "<?=$event_list_url?>";
                event_list_url += "?type=" + data_id;
                if(order) event_list_url += "&order="+order;
                location.href = event_list_url;
                break;
            case 'order':
                if(data_id === 'new') {
                    data_id = 'deadline';
                }
                else {
                    data_id = 'new';
                }
                
                var type = url.getUrlParam('type');
                var event_list_url = "<?=$event_list_url?>";
                event_list_url += "?order=" + data_id;
                if(type) event_list_url += "&type="+type;
                location.href = event_list_url;
                break;
            case 'link':
                util.clipboardCopy(this.getAttribute('target-link'));
                util.alert('<div style="width:50px;margin:0 auto;"><img src="/img/clip.png"></div></img>판매링크가<br> 복사 되었습니다.',{type:'instant'});
                break;
            case 'join':
                var is_sample = _this.data('sample');
                if(is_sample === 'N') {
                    data.ajax("<?=$ajax_event_url?>",{action:"joinEvent",ev_id:data_id,su_id:''},joinEventCB);
                }
                else {
                    location.href = '/influencer/item_detail_navi/sample_subscription.php?ev_id=' + data_id;
                }
                break;
        }
    });

    function joinEventCB(res) {
        util.alert('참여 신청되었습니다.');
        var t = $('.event_join_box[data-id='+res.ev_id+']');
        t.text('참여완료');
        t.data('type','finish');        
    }
    
    $(function(){
        $("#event_top_box div[data-id='<?=$type?>']").addClass('selected');
        //샘플신청 후 back으로 돌아왔을 때 새로고침으로 정보 업데이트
        if(window.performance.navigation.type == 2) location.reload();
    });


</script>

<!--content end -->

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>