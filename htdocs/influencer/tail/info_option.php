<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');
util::loginCheck();

$action = util::param("action");

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/tail/info_option.css">', 0);

$user = data::getLoginMember();
$email_option = ['naver.com','gmail.com','hanmail.net','nate.com','daum.net','hotmail.com'];
$email = explode('@',$user['mb_email']);

if($email[0] == '@') $email = '';

$sql = "
SELECT ca_id, ca_name
FROM g5_shop_category
WHERE ca_use = 1
";

sql_fetch_arrays($sql,$cats);
$select_category = $user['mb_category'];
$select_categorys = explode('',$select_category);

$sns_value = ['naver','instagram','facebook','youtube','other'];
$sns_name = ['네이버','인스타그램','페이스북','유튜브','기타'];

$ajax_info_url = G5_INFLUENCER_URL.'/tail/ajax.info.php';
$link_url = G5_SOCIAL_LOGIN_URL.'/link.php';
$cert_url = G5_INFLUENCER_URL.'/cert/kmc.php';

if(!empty($action)) {
    $opt = array(
        "type" => 'instant',
        "delay" => 2000
    );
    util::alert('수정완료',$opt);
}

$sql = "
SELECT msl_type 
FROM g5_member_sns_link 
WHERE del_yn = 'N'
AND mb_no = ?";

sql_fetch_arrays($sql,$sns_res,array($user['mb_no']));
$sns_type = array();
foreach($sns_res as $v) {
    $sns_type[] = $v['msl_type'];
}

$mb_sex = ($user['mb_sex'] === 'M') ? '남성' : '여성';

/**
 * 내정보 화면
 * @todo : [승대] PPT 50페이지, 개인정보 변경
 */
?>

<!--content start-->
<div class="info_option_box">
    <div class="top_box">
        <?=$user['mb_id']?>님 반갑습니다.
        <div class="opt_box" id="option">톱니바퀴</div>
    </div>
    <div class="content_box">
        <div class="title_box">개인정보 입력</div>
        <table>
            <tr>
                <td class="th">이름</td>
                <td>
                    <input type="text" value="<?=$user['mb_name']?>" id="name" readonly>
                </td>
            </tr>
            <tr>
                <td class="th">성별</td>
                <td>
                    <div class="sex_box">
                        <div><?=$mb_sex?></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="th">이메일</td>
                <td>
                    <div class="email_box">
                        <input type="text" value="<?=$email[0]?>" id="email1">
                        @
                        <input type="text" value="<?=$email[1]?>" id="email2">
                        <select id="email_option">
                            <option value="direct">집적입력</option>
                            <?
                                foreach($email_option as $option) {
                                    echo '<option>'.$option.'</option>';
                                }
                            ?>
                        </select>
                    </div>                    
                </td>
            </tr>
            <tr>
                <td class="th">수집동의</td>
                <td>
                    <div class="radio_box">
                        <div>
                            <label>
                                <input type="radio" name="agree" value="Y" <?if($user['mb_alert'] === 'Y') echo 'checked'?>>
                                <span>알림 ON</span>
                            </label>
                        </div>
                        <div>
                            <label>
                                <input type="radio" name="agree" value="N" <?if($user['mb_alert'] === 'N') echo 'checked'?>>
                                <span>알림 OFF</span>
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="th">휴대폰 번호</td>
                <td>
                    <div class="phone_box">
                        <input class="phone_input" type="text" id="info_update_phone" value="<?=$user['mb_hp']?>" readonly>
                        <div class="cert" id="cert">본인인증</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan=3 class="th">주소</td>
                <td>
                    <div class="addr_box">
                        <input type="text" placeholder="우편번호" id="zip_code" value="<?=$user['mb_zip1']?>">
                        <div class="addr_search" id="addr_search">주소검색</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" placeholder="기본주소" id="addr1" value="<?=$user['mb_addr1']?>">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" placeholder="상세주소" id="addr2" value="<?=$user['mb_addr2']?>">
                </td>
            </tr>
        </table>
        <div class="title_box">관심사</div>
        <?
            foreach($cats as $k => $v) {
                if($k % 4 === 0) echo '<div class="category_box">';
                $cmd = false;
                foreach($select_categorys as $v1) {
                    if($v1 === $v["ca_id"]) $cmd = true;
                }
                if($cmd) {
                    echo '<div class="sel" name="category" data-id="'.$v["ca_id"].'">'.$v["ca_name"].'</div>';
                }
                else {
                    echo '<div name="category" data-id="'.$v["ca_id"].'">'.$v["ca_name"].'</div>';
                }    
                if($k % 4 === 3) echo '</div>';
            }
            echo '</div>';
        ?>
        <div class="title_box">채널정보 입력</div>
        <table class="channel_box">
            <tr>
                <td>대표</td>
            </tr>
            <?
                for($i = 0; $i < count($sns_value); $i++) {
                    $v = $sns_value[$i];
                    $n = $sns_name[$i];
                    echo '<tr>';
                    if($user['mb_sns_channel'] === $v) echo '<td><input type="radio" name="sns" value="'.$v.'" checked></td>';
                    else echo '<td><input type="radio" name="sns" value="'.$v.'"></td>';
                    echo '<td>'.$n.'</td>';
                    if($v !== 'other') {
                        if(in_array($v,$sns_type)) echo '<td><div class="sns_detail_box" data-id="'.$v.'">연결해지하기</div><div class="sns_detail" data-detail="'.$v.'" >상세정보</div></td>';
                        else echo '<td><div data-id="'.$v.'">연결하기</div></td>';
                    }
                    else {
                        echo '<td><input class="other_input" id="other_url" value="'.$user['mb_other_url'].'" placeholder="채널 URL을 입력해주세요"></td>';
                    }
                    echo '</tr>';
                }
            ?>
        </table>
        <div class="update_box" id="update">수정완료</div>
    </div>    
</div>
<!--content end -->
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script>
    var cert_name = "<?=$user['mb_name']?>";
    var cert_phone = "<?=$user['mb_hp']?>";

    $(".info_option_box #addr_search").click(function() {
        new daum.Postcode({
            oncomplete: function(data) {
                $("#addr1").val(data.roadAddress);
                $("#zip_code").val(data.zonecode);
            }
        }).open();
    });

    $('.info_option_box #option').click(function() {
        location.href = '/influencer/tail/info_option.php';
    });

    $('.info_option_box #email_option').change(function(){
        $('#email_option option:selected').each(function(){
            if($(this).val() === 'direct') {
                $('#email2').val('');
                $('#email2').attr('disabled',false);
            }
            else {
                $('#email2').val($(this).val());
                $('#email2').attr('disabled',true);
            }
        })
    });

    $('.info_option_box #cert').click(function(){
        
        var param=  {
            from : 'update',
            url_code : '032001'
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
    })

    $('.info_option_box div[name=category]').click(function(){
        var _this = $(this);
        if(_this.hasClass('sel')) _this.removeClass('sel');
        else _this.addClass('sel');
    })

    $('.info_option_box #update').click(function(){
        var _this       = $(this);
        if(_this.hasClass('disabled')) return;
        _this.addClass('disabled');

        var name        = $('.info_option_box #name').val();
        var phone       = $('.info_option_box #info_update_phone').val();
        var email1      = $('.info_option_box #email1').val();
        var email2      = $('.info_option_box #email2').val();
        var email       = email1 + '@' + email2;
        var agree       = $('.info_option_box [name=agree]:checked').val();   
        var zip_code    = $('.info_option_box #zip_code').val();
        var addr1       = $('.info_option_box #addr1').val();
        var addr2       = $('.info_option_box #addr2').val();
        var category    = [];
        var sns_channel = $('.info_option_box [name=sns]:checked').val();
        var other_url   = $('.info_option_box #other_url').val();

        if(cert_name !== name || cert_phone !== phone) {
            util.alert('이름과 핸드폰을 학인해주세요.',{type:'instant'});
            _this.removeClass('disabled');
            return;
        }

        if (!email.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
            util.alert('이메일을 확인해주세요',{type:'instant'});
            _this.removeClass('disabled');
            return;
        }

        $(".info_option_box .category_box .sel").each(function(k, v) {
            category.push($(v).data('id'));
        });

        category = category.join('');

        data.ajax("<?=$ajax_info_url?>",{
            action      : 'updateInfo',
            category    : category,
            phone       : phone,
            email       : email,
            agree       : agree,
            zip_code    : zip_code,
            addr1       : addr1,
            addr2       : addr2,
            sns_channel : sns_channel,
            name        : name,
            other_url   : other_url
        },updateCB);
    })

    function updateCB(res) {
        if(res.code == 0) {
            _this.removeClass('disabled');
            util.alert('정보 수정 완료',{type:'instant'});
        }
    }

    $('.info_option_box .channel_box div[data-detail]').click(function(){
        var detail = $(this).data('detail');
        data.ajax("<?=$ajax_info_url?>",{
            action      : 'getSnsInfo',
            sns         : detail
        },getSnsCB)
    });

    function getSnsCB(res) {
       /*  res.item.msl_follower
        res.item.msl_post
        res.item.msl_title
        res.item.msl_url */
    }

    $('.info_option_box .channel_box div[data-id]').click(function(){
        var id = $(this).data('id');
        if(id === 'facebook') {
            FB.init({
                appId      : '224463146286770',
                cookie     : true,
                xfbml      : true,
                version    : 'v12.0'
            });
            FB.login(
                facebookCB,
                {scope: 'public_profile,email'}
            );    
        }
        else if(id === 'instagram') {
            FB.init({
                appId      : '185660146991931',
                cookie     : true,
                xfbml      : true,
                version    : 'v12.0'
            });
            FB.login(
                instagramCB,
                {scope: 'public_profile,email,instagram_basic,pages_show_list,pages_read_engagement'}
            ); 
        }
        else location.href = "<?=$link_url?>" + "?sns="+id;
    });

    function facebookCB(response) {
        location.href = "<?=$link_url?>" + "?hauth_done=facebook&token="+response['authResponse']['accessToken'];
    }

    function instagramCB(response) {
        location.href = "<?=$link_url?>" + "?hauth_done=instagram&token="+response['authResponse']['accessToken'];
    }

    $(document).ready(function() {
        var res = url.getUrlListParam(['sns','code','type']);
        console.log(res);
        if(res.code != 0) {
            if(res.code == 1) util.alert('연동되지 않았습니다. 관리자에게 문의주세요');
            else if(res.code == 2) util.alert('중복된 연결 계정이있습니다. 관리자에게 문의주세요');
            else if(res.code == 3) util.alert('유튜브 채널이 없습니다 만들어주세요');
            else if(res.code == 4) util.alert('블로그 게시물을 한개이상 등록해주세요.',{type:'instant'});
            else if(res.code == 5) util.alert('동의를 해주세요.',{type:'instant'});
        }
        else {
            codeZeroAlert(res.sns,res.type);
        }

        url.removeGetParams();
    });

    function codeZeroAlert(sns,type) {
        if(type === 'insert') {
            util.alert(sns + ' 채널 연결이 잘 되었습니다.',{type:'instant'});
        }
        else if(type === 'delete') {
            util.alert(sns + '채널 해지되었습니다.',{type:'instant'});
        }
    }

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>