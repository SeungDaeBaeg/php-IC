<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

    $action = $_POST['action'] ?? '';    
    $res = (object)['status'=>'OK'];

    switch($action) {
        case 'getList': 
        case 'getCodeName':        
            $action($res);
            break;
        default:
            $res->status = "OPPS";
            $res->msg = "액션이 이상합니다.";
            echo json_encode($res);
    }

    function getList($res) {
        $type = $_POST['type'] ?? '';
        $order = $_POST['order'] ?? '';
        $today = date('Y-m-d');

        $userData = data::getLoginMember();

        $sql = "select ev_id, ev_subject, ev_thumbnail_content, (select code_name from g5_code where code = ev_type and meta_code = 'event' and del_yn = 'N') code_name, ev_link, (select count(*) from g5_shop_party_join where ev_id = ev_id and mb_id = ?) party from g5_shop_event where ev_start_date <= ? and ev_end_date >= ? and ev_use = 1";
        if($type) {
            $sql .= " and ev_type = '".$type."'";
        }

        if($order === 'new') {
            $sql .= " order by ev_id desc";
        }
        else if($order === 'deadline') {
            $sql .= " order by ev_end_date";
        }
        sql_fetch_arrays($sql,$list,array($userData['mb_id'],$today,$today));
        $res->data = $list;
        echo json_encode($res);
    }

    function getCodeName($res) {
        $sql = "select code, code_name from g5_code where meta_code = 'event' and del_yn = 'N'";
        sql_fetch_arrays($sql,$codes);
        $res->data = $codes;
        echo json_encode($res);
    }
?>