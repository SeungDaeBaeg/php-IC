<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$mb_id = isset($_POST['reg_mb_id']) ? trim($_POST['reg_mb_id']) : '';
$mb_recommend = isset($_POST['reg_mb_recommend']) ? trim($_POST['reg_mb_recommend']) : '';
$mb_hp = isset($_POST['reg_mb_hp']) ? trim($_POST['reg_mb_hp']) : '';

set_session('ss_check_mb_id', '');

if ($msg = empty_mb_id($mb_id))     die($msg);
if ($msg = valid_mb_id($mb_id))     die($msg);
if ($msg = count_mb_id($mb_id))     die($msg);
if ($msg = exist_mb_id($mb_id))     die($msg);
if ($msg = reserve_mb_id($mb_id))   die($msg);
if ($msg = exist_mb_recommend($mb_recommend))   die($msg);
if ($msg = exist_join_mb_hp($mb_hp))   die($msg);

set_session('ss_check_mb_id', $mb_id);