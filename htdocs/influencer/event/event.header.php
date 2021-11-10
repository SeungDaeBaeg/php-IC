<?php
    add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/event/event.header.css">', 0);
?>

<div class="event_header_box" id="event_header_box">
    <div class="event_menu_box">
        <div data-id="list" data-type="menu">진행 중인 이벤트</div>
        <div data-id="party" data-type="menu">참여내역</div>
        <div data-id="reqsm" data-type="menu">샘플신청내역</div>
    </div>
</div>

<script>
    $('#event_header_box div[data-type=menu]').click(function(){
        var data_id = $(this).data('id');
        location.href = `./${data_id}.php`;
    });
    
    $(function(){
        var url = location.pathname;
        var s = url.lastIndexOf('/');
        var e = url.lastIndexOf('.');
        var id = url.substring(s+1,e);
        $(`#event_header_box div[data-id=${id}]`).addClass('selected');
    })
</script>