<?php
    // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
    add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal.css">', 11);
    add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal-default-theme.css">', 12);
    add_stylesheet('<link rel="stylesheet" href="'.get_social_skin_url().'/style.css?ver='.G5_CSS_VER.'">', 13);
    add_javascript('<script src="'.G5_JS_URL.'/remodal/remodal.js"></script>', 10);
    add_javascript('<script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.2.0.js"></script>',11);

    // 인플루언서 회원가입 URL
    $check_mb_id = https_url(G5_BBS_DIR, true).'/ajax.mb_id.php';

    sql_fetch_arrays("select ca_id,ca_name from g5_shop_category",$categorys);
?>

<!-- @todo [승대] 회원가입시 ppt 18번 왼쪽 -->
<div id="register_box">
    <div style="background-color:red;" id="register_influencer" onclick="btnClick(this.id)">
        인플루언서 회원가입<br>
        <br>
        SNS 채널을 등록하고, 더 많은 혜택은 받으세요.<br>
        나만의 쇼핑몰을 꾸미고, 상품을 판매할 수 있어요.<br>
        쇼핑도 하고! 판매 수익금도 챙기고!
    </div>
    <div style="background-color:blue" id="register_normal" onclick="btnClick(this.id)">
        일반 회원가입<br>
        <br>
        인버스트 쇼핑몰 가입으로, 온라인 최저가<br>
        상품을 구매할 수 있어요.
    </div>
</div>

<div id="register_influencer_box" style="display:none">
    <div>
        <div>인플루언서 회원가입</div>
        <div class="register_row">
            <div>아이디<span class="red_color">*</span></div>
            <div>
                <input type="text" placeholder="아이디입력" autocomplete="nope" id="input_id" onkeydown="inputKeyDown(this)" name="essential">
                <div class="red_color" id="error_id"></div>
            </div>
        </div>
        <div class="register_row">
            <div>이메일<span class="red_color">*</span></div>
            <div>
                <input type="text" autocomplete="nope" value="<?=isset($user_email)?$user_email:'';?>" id="input_email" onkeydown="inputKeyDown(this)">
                <div class="red_color" id="error_email"></div>
            </div>
        </div>
        <div class="register_row">
            <div>휴대폰 번호<span class="red_color">*</span></div>
            <div class="phone_number_box">
                <input type="text" autocomplete="nope" id="input_phone_0" onkeydown="inputKeyDown(this)">
                <input type="text" autocomplete="nope" id="input_phone_1" onkeydown="inputKeyDown(this)">
                <input type="text" autocomplete="nope" id="input_phone_2" onkeydown="inputKeyDown(this)">
                <button onclick="authClick()">인증요청</button>
                <div class="red_color" id="error_phone"></div>
            </div>            
        </div>
        <div class="register_row">
            <div>추천코드</div>
            <div>
                <input type="text" placeholder="추천코드 입력(선택)" autocomplete="nope" id="input_referer">
            </div>
        </div>
        <div class="register_row">
            <div>대표 SNS채널</div>
            <div>
                <select name="channel" id="input_channel">
                    <option value="instagram">인스타그램</option>
                    <option value="youtube">유튜브</option>
                    <option value="facebook">페이스북</option>
                    <option value="blog">블로그</option>
                    <option value="tiktok">틱톡</option>
                    <option value="other">기타</option>
                </select>
            </div>
        </div>
        <div class="register_row">
            <div>선호 카테고리</div>
            <div>
                <select name="category" id="input_category">
                    <?php
                        foreach($categorys as $category) {
                            echo '<option value="'.$category['ca_id'].'">'.$category['ca_name'].'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="agree_box">
            <div class="agree_row">
                <input type="checkbox" onclick="checkBoxAllClick(this)">
                <div>약관 모두 동의</div>
            </div>    
            <div class="agree_sub_box">
                <div class="agree_row">
                    <input type="checkbox" id="agree_1" onclick="agreeClick()">
                    <div>인플루언서 등록 약관에 동의합니다.</div>
                    <div class="arrow_box" id="arrow_1" onclick="arrowClick(this)">></div>
                </div>
                <div class="agree_form" id="agree_form_1">
                    <div>
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    </div>
                </div>
                <div class="agree_row">
                    <input type="checkbox" id="agree_2" onclick="agreeClick()">
                    <div>개인정보 수집 및 이용정책에 동의합니다.</div>
                    <div class="arrow_box" id="arrow_2" onclick="arrowClick(this)">></div>
                </div>
                <div class="agree_form" id="agree_form_2">
                    <div>
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    </div>
                </div>
                <div class="agree_row">
                    <input type="checkbox" id="agree_3" onclick="agreeClick()">
                    <div>고유식별 정보 수집에 동의합니다.</div>
                    <div class="arrow_box" id="arrow_3" onclick="arrowClick(this)">></div>
                </div>
                <div class="agree_form" id="agree_form_3">
                    <div>
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    sdjfklsdlkfsfl
                    </div>
                </div>
            </div>
            <div class="red_color" id="error_agree" style="height:19px;"></div>
        </div>
    </div>
    <div class="register_btn" onclick="registerClick()">
        가입하기
    </div>    
</div>


<form id="fregisterform" name="fregisterform" action="<?=$register_action_url; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?=$w; ?>">
    <input type="hidden" name="url" value="<?=$urlencode; ?>">
    <input type="hidden" name="mb_name" value="<?=$user_nick; ?>" >
    <input type="hidden" name="provider" value="<?=$provider_name;?>" >
    <input type="hidden" name="action" value="register">

    <input type="hidden" name="mb_id" value="<?=$user_id; ?>" id="reg_mb_id">
    <input type="hidden" name="mb_nick_default" value="<?=isset($user_nick)?get_text($user_nick):''; ?>">
    <input type="hidden" name="mb_nick" value="<?=isset($user_nick)?get_text($user_nick):''; ?>" id="reg_mb_nick">

    <input type="hidden" name="mb_email" value="<?=isset($user_email)?$user_email:''; ?>" id="reg_mb_email">
    
    <input type="hidden" name="mb_phone" id="reg_mb_phone">
    <input type="hidden" name="mb_referer" id="reg_mb_referer">
    <input type="hidden" name="mb_channel" id="reg_mb_channel">
    <input type="hidden" name="mb_category" id="reg_mb_category">
    <input type="hidden" name="mb_influencer" id="reg_mb_influencer" value="N">
</form>

<script>
    IMP.init("imp63259493");

    const fregisterform = document.fregisterform;    
    
    function btnClick(_id) {
        switch(_id) {
            case 'register_influencer':
                registerInfluencer();
                break;
            case 'register_normal':
                fregisterform.submit();
                break;
        }
    }

    function registerInfluencer() {
        document.getElementById('register_box').style.display = 'none';
        document.getElementById('register_influencer_box').style.display = 'block';
    }

    function checkBoxAllClick(_this) {
        document.getElementById('error_agree').textContent = '';

        if(_this.checked) {
            document.getElementById('agree_1').checked = true;
            document.getElementById('agree_2').checked = true;
            document.getElementById('agree_3').checked = true;
        }
        else {
            document.getElementById('agree_1').checked = false;
            document.getElementById('agree_2').checked = false;
            document.getElementById('agree_3').checked = false;
        }
        
    }

    function arrowClick(_this) {
        const form_id = _this.id.split('_')[1];
        
        if(_this.style.transform) {
            _this.style.transform = '';
            document.getElementById('agree_form_'+form_id).style['max-height'] = '0px';
        }
        else {
            _this.style.transform = 'rotate(90deg)';
            document.getElementById('agree_form_'+form_id).style['max-height'] = '50px';
        }
    }

    function registerClick() {
        const input_id = document.getElementById('input_id');
        const input_email = document.getElementById('input_email');
        const input_phone_0 = document.getElementById('input_phone_0');
        const input_phone_1 = document.getElementById('input_phone_1');
        const input_phone_2 = document.getElementById('input_phone_2');
        const phone_number = input_phone_0.value + input_phone_1.value + input_phone_2.value;
        const input_referer = document.getElementById('input_referer');
        const input_channel = document.getElementById('input_channel');
        const input_category = document.getElementById('input_category');
        const id_check = /[a-z0-9]/g;
        const email_check = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        const phone_check = /^\d{3}\d{3,4}\d{4}$/;
        
        if(input_id.value === '') {
            input_id.focus();
            document.getElementById('error_id').textContent = '아이디를 입력해주세요.';
            return;
        }

        if(!id_check.test(input_id.value)) {
            input_id.focus();
            document.getElementById('error_id').textContent = '영문 + 숫자로 입력해주세요.';
            return;
        }

        if(input_email.value === '') {
            input_email.focus();
            document.getElementById('error_email').textContent = '이메일을 입력해주세요.';
            return;
        }

        if(!email_check.test(input_email.value)) {
            input_email.focus();
            document.getElementById('error_email').textContent = '이메일 형식으로 입력해주세요.';
            return;
        }

        if(input_phone_0.value === '' || input_phone_1.value === '' || input_phone_2.value === '') {
            input_phone_0.focus();
            document.getElementById('error_phone').textContent = '휴대폰 번호를 입력해주세요.';
            return;
        }

        if(!(/\d{3}/.test(input_phone_0.value)) || !(/\d{3,4}/.test(input_phone_1.value)) || !(/\d{4}/.test(input_phone_2.value))) {
            input_phone_0.focus();
            document.getElementById('error_phone').textContent = '휴대폰 번호를 확인해주세요.';
            return;
        }

        if(!(document.getElementById('agree_1').checked && document.getElementById('agree_2').checked && document.getElementById('agree_3').checked)) {
            document.getElementById('error_agree').textContent = '약관에 동의해주세요.';
            return;
        }

        $.ajax({
            url:'<?=$check_mb_id ?>',
            type:'post',
            data:{reg_mb_id:input_id.value}
        }).done(function(msg){
            if(msg !== '') {
                input_id.focus();
                document.getElementById('error_id').textContent = msg;
                return;
            }
            else {
                fregisterform['mb_id'].value = input_id.value;
                fregisterform['mb_email'].value = input_email.value;
                fregisterform['mb_phone'].value = phone_number;
                fregisterform['mb_referer'].value = input_referer.value;
                fregisterform['mb_channel'].value = input_channel.options[input_channel.selectedIndex].value;
                fregisterform['mb_category'].value = input_category.options[input_category.selectedIndex].value;
                fregisterform['mb_influencer'].value = 'Y';                
                fregisterform.submit();
            }
        })        
    }

    function inputKeyDown(_this) {
        _this.parentNode.lastElementChild.textContent = '';
    }

    function agreeClick() {
        document.getElementById('error_agree').textContent = '';
    }

    function authClick() {
        IMP.certification({ // param
            merchant_uid: "test", // 주문 번호
            m_redirect_url : "http://test.inburstshop.com/plugin/social/register_member.php?provider=Naver&url=%2F", // 모바일환경에서 popup:false(기본값) 인 경우 필수, 예: https://www.myservice.com/payments/complete/mobile
            popup : false // PC환경에서는 popup 파라메터가 무시되고 항상 true 로 적용됨
            }, function (rsp) { // callback
                console.log(rsp);
                if (rsp.success) {

                } else {

                }
        });
    }    
</script>