<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/event.css">', 0);

$ajax_event_url = G5_INFLUENCER_URL.'/event/ajax.event.php';
$event_list_url = G5_INFLUENCER_URL.'/event/list.php';
$img_src_url = G5_DATA_URL.'/event/';

//codeName 불러오기
$sql = "select code, code_name from g5_code where meta_code = 'event' and del_yn = 'N'";
sql_fetch_arrays($sql,$codes);

$type = $_GET['type'] ?? 'all';
$order = $_GET['order'] ?? 'new';
$order_name = '';
$today = date('Y-m-d');

$userData = data::getLoginMember();

//mb_id는 로그인한 유저가 있을 경우 해당 이벤트를 참여했는지 안 했는지
$sql = "
SELECT ev_id, ev_subject, ev_thumbnail_content, 
(select code_name from g5_code where code = ev_type and meta_code = 'event' and del_yn = 'N') code_name, ev_link, 
(select count(*) from g5_shop_party_join where ev_id = ev_id and mb_id = ?) party 
FROM g5_shop_event 
WHERE ev_start_date <= ? and ev_end_date >= ? and ev_use = 1";

if($type !== 'all') {
    $sql .= " and ev_type = '".$type."'";
}

if($order === 'new') {
    $sql .= " order by ev_id desc";
    $order_name = '최신순';
}
else if($order === 'deadline') {
    $sql .= " order by ev_end_date";
    $order_name = '마감순';
}

sql_fetch_arrays($sql, $eventBox, array($userData['mb_id'],$today,$today));

?>
<?php
    /**
     * @todo : [승대] PPT 28페이지
     * 이벤트 페이지
     */
?>
<!--content start-->
<div class="event_box">
    <div class="event_top_box">
        <div class="event_menu_box">
            <div class="selected" data-id="menu_0">진행 중인 이벤트</div>
            <div data-id="menu_1">참여내역</div>
        </div>
        <div class="event_type_box">
            <div data-id="type_all">전체</div>
            <?
                foreach($codes as $v) {
                    echo util::component('eventCodeBox', $v);
                }
            ?>
        </div>
        <div class="event_order_box">
            <div class="selected new" data-id="order_<?=$order?>"><?=$order_name?></div>
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
    $('div[data-id]').click(function(){
        var data_id = $(this).attr('data-id');
        var ids = data_id.split('_');
        switch(ids[0]) {
            case 'type':
                var order = url.getUrlParam('order');
                var event_list_url = "<?=$event_list_url?>";
                event_list_url += "?type=" + ids[1];
                if(order) event_list_url += "&order="+order;
                location.href = event_list_url;
                break;
            case 'order':
                if(ids[1] === 'new') {
                    ids[1] = 'deadline';
                }
                else {
                    ids[1] = 'new';
                }
                
                var type = url.getUrlParam('type');
                var event_list_url = "<?=$event_list_url?>";
                event_list_url += "?order=" + ids[1];
                if(type) event_list_url += "&type="+type;
                location.href = event_list_url;
                break;
            case 'link':
                util.clipboardCopy(this.getAttribute('target-link'));
                util.alert('판매링크가<br> 복사 되었습니다.','clip.png');
                break;
            case 'join':
                location.href = '/influencer/event/sample.php?ev_id=' + ids[1];
                break;
        }
    })
    
    $(function(){
        $("div[data-id='type_<?=$type?>']").addClass('selected');        
    })
    /* var getListparam = {
        action:'getList',
        order: 'new',
        type: ''
    }

    function btnClick(_this) {
        util.removeListClass(_this.parentNode.children,'selected');
        _this.classList.add('selected');
        var ids = _this.id.split('_');
        if(ids[0] === 'type') {
            getTypeList(ids[1]);
        }
        else if(ids[0] === 'order') {
            getOrderList(_this);
        }
        else if(ids[0] === 'link') {
            getLink(_this);
        }
        else if(ids[0] === 'join') {
            location.href = '/influencer/event/sample.php?ev_id=' + ids[1];
        }
    }

    function getLink(_this) {
        util.clipboardCopy(_this.getAttribute('target-link'));
        util.alert('판매링크가<br> 복사 되었습니다.','clip.png');
    }

    function getTypeList(type) {
        getListparam['type'] = type;
        if(type) history.pushState(null,null,location.origin+location.pathname+'?type='+type);
        else history.pushState(null,null,location.origin+location.pathname);
        data.ajaxCall('post','<?=$ajax_event_url ?>',getListparam,getListCB);
    }

    function getListCB(res) {
        var listBox = document.getElementById('listBox');
        var _html = '';
        if(res === null) {
            _html = gridNullList();
        }
        else {
            for(var i = 0; i < res.length; i++) {
                _html += gridList(res[i]);
            }  
        }
        
        listBox.innerHTML = _html;
    }

    function getOrderList(_this) {
        var order = getListparam['order'];
        if(order === 'new') {
            getListparam['order'] = 'deadline';
            _this.textContent = '마감순';            
        }
        else {
            getListparam['order'] = 'new';
            _this.textContent = '최신순';
        }
        data.ajaxCall('post','<?=$ajax_event_url ?>',getListparam,getListCB);
    }

    function gridList(item) {        
        var imgSrc = '<?=$img_src_url ?>';
        var _html = `
        <div class="event_item_box">
            <div class="event_item_thumbnail">
                <img src='${imgSrc+item.ev_id}_m'>
                <div class="event_badge">
                    ${item.code_name}
                </div>                
            </div>
            <div class="event_item_content">
                <div>${item.ev_subject}</div>
                ${item.ev_thumbnail_content}
                ${(item.ev_link)?
                    `<div class="event_join_box" id="link_${item.ev_id}" target-link="${item.ev_link}" onclick="btnClick(this)"><div class="ev_link_icon"></div></div>`:
                    `<div class="event_join_box" id="join_${item.ev_id}" onclick="btnClick(this)">참여신청</div>`}                                
            </div>
        </div>`;
        return _html;
    }

    function gridNullList() {
        var _html = `
        <div>진행 중인 이벤트가 없습니다.</div>
        `
        return _html;
    }

    function getCodeNameCB(res) {
        var typeBox = document.getElementById('typeBox');
        var codes = [];
        var type = '';
        var _html = '<div class="" id="type_" onclick="btnClick(this)">전체</div>';
        for(var i = 0; i < res.length; i++) {
            var code = res[i].code;
            var code_name = res[i].code_name;
            _html += `<div id="type_${code}" onclick="btnClick(this)">${code_name}</div>`;
            codes.push(code);
        }
        typeBox.innerHTML = _html;        

        if(codes.indexOf(getListparam.type) === -1) {
            getListparam.type = '';
        }

        document.getElementById('type_'+getListparam.type).click();
    }

    function initPage() {
        var getParam = url.getUrlListParam(['type','order','join']);
        getListparam.type = getParam.type;
        getListparam.order = (getParam.order === '')? 'new': getParam.order;
        if(getParam.join === 'success') {
            util.alert('참여성공!');
        }
        data.ajaxCall('post','<? echo $ajax_event_url ?>',{action:'getCodeName'},getCodeNameCB);
    }

    initPage(); */
        
</script>

<!--content end -->

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>