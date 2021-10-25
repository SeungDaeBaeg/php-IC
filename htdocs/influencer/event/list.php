<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_INFLUENCER_URL.'/event/event.css">', 0);
add_javascript('<script src="'.G5_URL.'/js/influencer_common.js"></script>', 0);

$ajax_event_url = G5_INFLUENCER_URL.'/event/ajax.event.php';
$img_src_url = G5_DATA_URL.'/event/';

?>

<!--content start-->
<div class="event_box">
    <div class="event_top_box">
        <div class="event_menu_box">
            <div class="selected" id="menu_0" onclick="btnClick(this)">진행 중인 이벤트</div>
            <div id="menu_1" onclick="btnClick(this)">참여내역</div>
        </div>
        <div class="event_type_box" id="typeBox">
            
        </div>
        <div class="event_order_box">
            <div class="selected new" id="order" onclick="btnClick(this)">최신순</div>
        </div>
    </div>
    <div class="event_list_box" id="listBox">
    </div>
</div>

<script>
    const getListparam = {
        action:'getList',
        order: 'new',
        type: ''
    }

    function btnClick(_this) {
        removeClass(_this.parentNode.children,'selected');
        _this.classList.add('selected');
        const ids = _this.id.split('_');
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
            //ajaxCall('post','<? echo $ajax_event_url ?>',{action:'getPartyById',ev_id:ids[1]},getPartyByIdCB);
            location.href = '/influencer/event/party.php?ev_id=' + ids[1];
        }
    }

    function getLink(_this) {
        clipboardCopy(_this.getAttribute('target-link'));
        showModal('info','판매링크가<br> 복사 되었습니다.');
    }

    function getTypeList(types) {
        let type = '';

        if(types !== 'all') type = types;

        getListparam['type'] = type;

        ajaxCall('post','<? echo $ajax_event_url ?>',getListparam,getListCB);
    }

    function getListCB(res) {
        const listBox = document.getElementById('listBox');
        let _html = '';
        if(res === null) {
            _html = gridNullList();
        }
        else {
            for(let i = 0; i < res.length; i++) {
                _html += gridList(res[i]);
            }  
        }
        
        listBox.innerHTML = _html;
    }

    function getOrderList(_this) {
        const order = getListparam['order'];
        if(order === 'new') {
            getListparam['order'] = 'deadline';
            _this.textContent = '마감순';            
        }
        else {
            getListparam['order'] = 'new';
            _this.textContent = '최신순';
        }
        ajaxCall('post','<? echo $ajax_event_url ?>',getListparam,getListCB);
    }

    function gridList(item) {        
        const imgSrc = '<? echo $img_src_url ?>';
        const _html = `
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
        const _html = `
        <div>진행 중인 이벤트가 없습니다.</div>
        `
        return _html;
    }

    function getCodeNameCB(res) {
        const typeBox = document.getElementById('typeBox');
        let _html = '<div class="selected" id="type_all" onclick="btnClick(this)">전체</div>';
        for(let i = 0; i < res.length; i++) {
            const code = res[i].code;
            const code_name = res[i].code_name;
            _html += `<div id="type_${code}" onclick="btnClick(this)">${code_name}</div>`;
        }
        typeBox.innerHTML = _html;        
    }

    ajaxCall('post','<? echo $ajax_event_url ?>',{action:'getCodeName'},getCodeNameCB);
    ajaxCall('post','<? echo $ajax_event_url ?>',getListparam,getListCB);

        
</script>

<!--content end -->

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>