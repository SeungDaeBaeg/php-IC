<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);

?>
<!-- @todo [승대] 소셜계정으로 로그인 PPT 17페이지 -->
<div class="ol tnb_con ol_before_wr" style="display:none">
    <aside id="ol_before" class="ol">
        <div style="float:right" id="btn_close">X</div>
        <img src="<?=G5_DATA_URL; ?>/common/logo.jpg">
        <div>
            전상품 최저가 보장<br>
            365일 할인구매<br>
            SNS 채널 등록만 하면, 혜택이 쏟아진다<br>
        </div>
        <div><?php require_once (G5_THEME_MOBILE_PATH.'/'.G5_SKIN_DIR.'/social/social_outlogin.skin.1.php'); ?></div>
        <div>비회원 주문조회</div>
        <!-- 로그인 전 외부로그인 시작 -->

        <? if(SERVER_LOCAL == $_SERVER['REMOTE_ADDR']) { ?>
            <form name="foutlogin" action="<?=$outlogin_action_url ?>" onsubmit="return fhead_submit(this);" method="post" autocomplete="off">
                <button type="button" class="btn_close"><i class="fa fa-times"></i><span class="sound_only">닫기</span></button>
                <fieldset>
                    <input type="hidden" name="url" value="<?=$outlogin_url ?>">
                    <label for="ol_id" id="ol_idlabel" class="sound_only">회원아이디<strong>필수</strong></label>
                    <input type="text" id="ol_id" name="mb_id" required class="required frm_input" maxlength="20" placeholder="아이디">
                    <label for="ol_pw" id="ol_pwlabel" class="sound_only">비밀번호<strong>필수</strong></label>
                    <input type="password" name="mb_password" id="ol_pw" required class="required frm_input" maxlength="20" placeholder="비밀번호">
                    <div id="ol_auto">
                        <input type="checkbox" name="auto_login" value="1" id="auto_login">
                        <label for="auto_login" id="auto_login_label">자동로그인</label>
                    </div>
                    <input type="submit" id="ol_submit" value="로그인" class="btn_b02">
                    <a href="<?=G5_BBS_URL ?>/register.php"" class="btn_b01 btn_join"><b>회원가입</b></a>

                    <div id="ol_svc">
                        <a href="<?=G5_BBS_URL ?>/password_lost.php" id="ol_password_lost">회원정보찾기</a>
                    </div>
                </fieldset>
            </form>
        <? } ?>
    </aside>
</div>
<script>
    $omi = $('#ol_id');
    $omp = $('#ol_pw');
    $omi_label = $('#ol_idlabel');
    $omi_label.addClass('ol_idlabel');
    $omp_label = $('#ol_pwlabel');
    $omp_label.addClass('ol_pwlabel');

    $(function() {

        $("#auto_login").click(function(){
            if ($(this).is(":checked")) {
                if(!confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?"))
                    return false;
            }
        });
    });

    function fhead_submit(f)
    {
        return true;
    }

    $('#btn_close').click(function(){
        $('.ol_before_wr').hide();
    })
</script>
<!-- } 로그인 전 아웃로그인 끝 -->
