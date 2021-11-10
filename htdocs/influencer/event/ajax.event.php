<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

    $action = $_POST['action'] ?? '';   
    
    // ajax action값으로 밑에 함수를 호출;
    switch($action) {
        case 'joinEvent':     
            $action();
            break;
        default:
            util::ajaxResult('해당 액션이 없습니다',-1);
    }

    function joinEvent() {
        $id = util::param(array("ev_id","su_id"), "이벤트 아이디가 없습니다.");

        $set_id = data::setJoinEvent(intval($id['ev_id']),intval($id['su_id']));

        if($set_id > 0) {
            util::ajaxResult('success',0,array("ev_id"=>$id['ev_id']));
        }
        else {
            util::ajaxResult('관리자한테 문의해주세요.',-2);
        }
    }
?>