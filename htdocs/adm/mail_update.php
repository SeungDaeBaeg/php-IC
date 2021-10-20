<?php
$sub_menu = "200300";
include_once('./_common.php');

if ($w == 'u' || $w == 'd')
    check_demo();

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$ma_id = isset($_POST['ma_id']) ? (int) $_POST['ma_id'] : 0;
$ma_subject = isset($_POST['ma_subject']) ? strip_tags(clean_xss_attributes($_POST['ma_subject'])) : '';
$ma_content = isset($_POST['ma_content']) ? $_POST['ma_content'] : '';

if ($w == '')
{
    $sql = " insert g5_mail
                set ma_subject = '{$ma_subject}',
                     ma_content = '{$ma_content}',
                     ma_time = '".G5_TIME_YMDHIS."',
                     ma_ip = '{$_SERVER['REMOTE_ADDR']}' ";
    sql_query($sql);
}
else if ($w == 'u')
{
    $sql = " update g5_mail
                set ma_subject = '{$ma_subject}',
                     ma_content = '{$ma_content}',
                     ma_time = '".G5_TIME_YMDHIS."',
                     ma_ip = '{$_SERVER['REMOTE_ADDR']}'
                where ma_id = '{$ma_id}' ";
    sql_query($sql);
}
else if ($w == 'd')
{
	$sql = " delete from g5_mail where ma_id = '{$ma_id}' ";
    sql_query($sql);
}

goto_url('./mail_list.php');