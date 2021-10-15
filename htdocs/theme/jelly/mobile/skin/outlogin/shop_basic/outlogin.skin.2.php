<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<!-- 로그인 후 외부로그인 시작 -->
<aside id="ol_after" class="ol">
   
    <h2>나의 회원정보</h2>
    <div id="ol_after_hd">
        <span class="profile_img">
            <?php echo get_member_profile_img($member['mb_id'], 60, 60); ?>
            <a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=register_form.php" id="ol_after_info"><i class="fa fa-cog" aria-hidden="true"></i><span class="sound_only">정보수정</span></a>
        </span>
        <strong><?php echo $nick ?>님</strong>
        <div id="ol_after_btn">
            <a href="<?php echo G5_BBS_URL ?>/logout.php" id="ol_after_logout" class="btn btn_b01">로그아웃</a>
        </div>
    </div>

  

    <ul id="ol_after_private">
        <li>
            <a href="<?php echo G5_SHOP_URL ?>/coupon.php" target="_blank" id="ol_after_scrap" class="win_scrap"><i class="fa fa-newspaper-o" aria-hidden="true"></i>쿠폰
            <strong><span><?php echo number_format($cp_count); ?></span></strong>
            </a>
        </li>
        <li id="ol_after_pt">
            <a href="<?php echo G5_BBS_URL ?>/point.php" target="_blank" class="win_scrap">
                <i class="fa fa-database"></i>포인트
                <strong><span><?php echo $point ?></strong>
            </a>
        </li>
        <li id="ol_after_memo">
            <a href="<?php echo G5_BBS_URL ?>/memo.php" target="_blank" class="win_scrap">
                <i class="fa fa-envelope-o"></i>쪽지
                <strong><span><?php echo $memo_not_read ?></span></strong>
            </a>
        </li>

        
    </ul>

   <ul id="ol_after_ul" class="cate">
        <li><a href="<?php echo G5_SHOP_URL; ?>/mypage.php"><i class="fa fa-user"></i>마이페이지</a></li>
        <li><a href="<?php echo G5_BBS_URL; ?>/faq.php"><i class="fa fa-question-circle-o"></i>FAQ</a></li>
        <li><a href="<?php echo G5_BBS_URL; ?>/qalist.php"><i class="fa fa-comments-o"></i>1:1문의</a></li>
        <li><a href="<?php echo G5_SHOP_URL; ?>/itemuselist.php"><i class="fa fa-camera"></i>사용후기</a></li>
        <li><a href="<?php echo G5_SHOP_URL; ?>/couponzone.php"><i class="fa fa-gift"></i>쿠폰존</a></li>
    </ul>

    <button type="button" class="btn_close"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">카테고리닫기</span></button>
</aside>

<script>
// 탈퇴의 경우 아래 코드를 연동하시면 됩니다.
function member_leave()
{
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?"))
        location.href = "<?php echo G5_BBS_URL ?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- 로그인 후 외부로그인 끝 -->
