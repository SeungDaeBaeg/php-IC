<?php
    // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
    add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal.css">', 11);
    add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal-default-theme.css">', 12);
    add_stylesheet('<link rel="stylesheet" href="'.get_social_skin_url().'/style.css?ver='.G5_CSS_VER.'">', 13);
    add_javascript('<script src="'.G5_JS_URL.'/remodal/remodal.js"></script>', 10);

    // 인플루언서 회원가입 URL
    $check_mb_id = https_url(G5_BBS_DIR, true).'/ajax.mb_id.php';
    $cert_url = G5_INFLUENCER_URL.'/cert/kmc.php';

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
                <input type="text" autocomplete="nope" id="input_phone" onkeydown="inputKeyDown(this)" readonly>
                <button onclick="authClick()">본인인증</button>
                <div class="red_color" id="error_phone"></div>
            </div>            
        </div>
        <div class="register_row">
            <div>추천코드</div>
            <div>
                <input type="text" placeholder="추천코드 입력(선택)" autocomplete="nope" id="input_referer" onkeydown="inputKeyDown(this)">
                <div class="red_color" id="error_referer"></div>
            </div>
        </div>
        <div class="register_row">
            <div>대표 SNS채널</div>
            <div>
                <select name="channel" id="input_channel">
                    <option value="naver">블로그</option>
                    <option value="instagram">인스타그램</option>
                    <option value="facebook">페이스북</option>
                    <option value="youtube">유튜브</option>                    
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
                        <?require_once($_SERVER['DOCUMENT_ROOT'].'/terms.html');?>
                    </div>
                </div>
                <div class="agree_row">
                    <input type="checkbox" id="agree_2" onclick="agreeClick()">
                    <div>개인정보 수집 및 이용정책에 동의합니다.</div>
                    <div class="arrow_box" id="arrow_2" onclick="arrowClick(this)">></div>
                </div>
                <div class="agree_form" id="agree_form_2">
                    <div>
                        <?require_once($_SERVER['DOCUMENT_ROOT'].'/privacy.html');?>
                    </div>
                </div>
                <div class="agree_row">
                    <input type="checkbox" id="agree_3" onclick="agreeClick()">
                    <div>고유식별 정보 수집에 동의합니다.</div>
                    <div class="arrow_box" id="arrow_3" onclick="arrowClick(this)">></div>
                </div>
                <div class="agree_form" id="agree_form_3">
                    <div>
                    <?require_once($_SERVER['DOCUMENT_ROOT'].'/unique.html');?>
                    </div>
                </div>
            </div>
            <div class="red_color" id="error_agree" style="height:19px;"></div>
        </div>
    </div>
    <div class="register_btn" id="register_btn">
        가입하기
    </div>    
</div>


<form id="fregisterform" name="fregisterform" action="<?=$register_action_url; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?=$w; ?>">
    <input type="hidden" name="url" value="<?=$urlencode; ?>">
    <input type="hidden" name="mb_name" id="reg_mb_name" value="<?=$user_nick; ?>" >
    <input type="hidden" name="mb_sex" id="reg_mb_sex">
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
    var fregisterform = document.fregisterform;
    var kmc_hp = '';
    
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

    $('#register_btn').click(function(){
        var _this = $(this);
        if(_this.hasClass('disabled')) return;

        _this.addClass('disabled');
        
        const input_id = document.getElementById('input_id');
        const input_email = document.getElementById('input_email');        
        const input_phone = document.getElementById('input_phone');
        const input_referer = document.getElementById('input_referer');
        const input_channel = document.getElementById('input_channel');
        const input_category = document.getElementById('input_category');
        const id_check = /[a-z0-9]/g;
        const email_check = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        const phone_check = /^\d{3}\d{3,4}\d{4}$/;
        
        if(input_id.value === '') {
            input_id.focus();
            document.getElementById('error_id').textContent = '아이디를 입력해주세요.';
            _this.removeClass('disabled');
            return;
        }

        if(!id_check.test(input_id.value)) {
            input_id.focus();
            document.getElementById('error_id').textContent = '영문 + 숫자로 입력해주세요.';
            _this.removeClass('disabled');
            return;
        }

        if(input_email.value === '') {
            input_email.focus();
            document.getElementById('error_email').textContent = '이메일을 입력해주세요.';
            _this.removeClass('disabled');
            return;
        }

        if(!email_check.test(input_email.value)) {
            input_email.focus();
            document.getElementById('error_email').textContent = '이메일 형식으로 입력해주세요.';
            _this.removeClass('disabled');
            return;
        }

        if(input_phone.value === '' || kmc_hp !== input_phone.value) {
            document.getElementById('error_phone').textContent = '본인인증을 해주세요';
            _this.removeClass('disabled');
            return;
        }

        if(!(document.getElementById('agree_1').checked && document.getElementById('agree_2').checked && document.getElementById('agree_3').checked)) {
            document.getElementById('error_agree').textContent = '약관에 동의해주세요.';
            _this.removeClass('disabled');
            return;
        }        

        $.ajax({
            url:'<?=$check_mb_id ?>',
            type:'post',
            data:{reg_mb_id:input_id.value,reg_mb_recommend:input_referer.value,reg_mb_hp:input_phone.value}
        }).done(function(msg){
            if(msg !== '') {
                if(trim(msg) === 'not_ref') {
                    input_referer.focus();
                    document.getElementById('error_referer').textContent = '추천인 아이디가 없습니다.';
                }
                else if(msg === 'dup_hp') {
                    document.getElementById('error_phone').textContent = '중복된 핸드폰 번호가 있습니다.';
                }
                else {
                    input_id.focus();
                    document.getElementById('error_id').textContent = msg;
                }
                _this.removeClass('disabled');
                return;
            }
            else {
                fregisterform['mb_id'].value = input_id.value;
                fregisterform['mb_email'].value = input_email.value;
                fregisterform['mb_phone'].value = input_phone.value;
                fregisterform['mb_referer'].value = input_referer.value;
                fregisterform['mb_channel'].value = input_channel.options[input_channel.selectedIndex].value;
                fregisterform['mb_category'].value = input_category.options[input_category.selectedIndex].value;
                fregisterform['mb_influencer'].value = 'Y';                
                fregisterform.submit();
            }
        })
    })

    function inputKeyDown(_this) {
        _this.parentNode.lastElementChild.textContent = '';
    }

    function agreeClick() {
        document.getElementById('error_agree').textContent = '';
    }

    function authClick() {
        document.getElementById('error_phone').textContent = '';
        var param=  {
            from : 'join',
            url_code : '032002'
        };

        //안드로이드나 iOS일 경우 파라미터 추가
        if(navigator.userAgent.indexOf('Mobile') > -1) {
            param.app = 1;
        }

        data.ajax("<?=$cert_url?>",param,certCB);

        function certCB(solution) {
            window.open('', 'popForm', "toolbar=no, width=540, height=467, directories=no, status=no, scrollorbars=no, resizable=no");
            
            var f = document.createElement("form");
            f.setAttribute('id',"reqKMCISForm");
            f.setAttribute('name',"reqKMCISForm");
            f.setAttribute('target', (navigator.userAgent.indexOf('lp_android') > -1 || navigator.userAgent.indexOf('lp_ios') > -1) ? '_self' : 'popForm');
            f.setAttribute('method',"post");
            f.setAttribute('action',solution.item.call_url);

            var i = document.createElement("input"); //input element, text
            i.setAttribute('type',"hidden");
            i.setAttribute('name',"tr_cert");
            i.setAttribute('value',solution.item.tr_cert);

            var j = document.createElement("input"); //input element, text
            j.setAttribute('type',"hidden");
            j.setAttribute('name',"tr_url");
            j.setAttribute('value',solution.item.tr_url);

            f.appendChild(i);
            f.appendChild(j);

            document.body.appendChild(f);
            document.reqKMCISForm.submit();

            var child = document.getElementById("reqKMCISForm");
            child.parentNode.removeChild(child);
        }
    }    
</script>