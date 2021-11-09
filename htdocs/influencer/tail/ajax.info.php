<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

    $action = $_POST['action'] ?? '';   

    switch($action) {
        case 'updateInfo':     
            $action();
            break;
        default:
            util::ajaxResult('해당 액션이 없습니다',-1);
    }

    function updateInfo() {
        $p = array('category','email','phone','agree','zip_code','addr1','addr2','sns_channel','name','other_url');
        $param = util::param($p, "파라미터가 없습니다.");
        $mb_no = data::getLoginMember()['mb_no'];
        
        $id = sql_update("g5_member",array(
            'mb_category'       =>  $param['category'],
            'mb_email'          =>  $param['email'],
            'mb_hp'             =>  $param['phone'],
            'mb_alert'          =>  $param['agree'],
            'mb_zip1'           =>  $param['zip_code'],
            'mb_addr1'          =>  $param['addr1'],
            'mb_addr2'          =>  $param['addr2'],
            'mb_sns_channel'    =>  $param['sns_channel'],
            'mb_name'           =>  $param['name'],
            'mb_other_url'      =>  $param['other_url']
        ),"mb_no = {$mb_no}");

        util::ajaxResult('success',0);
    }
?>