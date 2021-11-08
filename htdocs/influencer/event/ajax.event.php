<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

    $action = $_POST['action'] ?? '';   

    switch($action) {
        case 'joinEvent':     
            $action();
            break;
        default:
            util::ajaxResult('해당 액션이 없습니다',-1);
    }

    function joinEvent() {
        $id = util::param(array("ev_id","su_id"), "이벤트 아이디가 없습니다.");
        $mb_no = data::getLoginMember()['mb_no'];

        $ev_id = $id['ev_id'] ?? '';
        $su_id = $id['su_id'] ?? '';

        $id = sql_insert("g5_shop_party_join",array(
            'ev_id'         =>  $ev_id,
            'mb_no'         =>  $mb_no,
            'su_id'         =>  $su_id
        ));

        if($id > 0) {
            util::ajaxResult('success',0,array("ev_id"=>$ev_id));
        }
        else {
            util::ajaxResult('관리자한테 문의해주세요.',-2);
        }
    }
?>