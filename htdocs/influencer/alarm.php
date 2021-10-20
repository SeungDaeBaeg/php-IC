<?php
/**
 * @todo: [승대] 알람페이지 코딩 필요
 * 알람 페이지
 */

if(empty($_REQUEST['p'])) {
    $_REQUEST['p'] = 'alarm';
}



?>

<p>읽지 않은 메세지 0통</p>


<p>
    <a href="<?=$_SERVER['PHP_SELF']?>?p=alarm">알람</a>
    <a href="<?=$_SERVER['PHP_SELF']?>?p=notice">공지사항</a>
</p>