<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');
util::loginCheck();

$action = util::paramCheck("action");

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

$sns_value = ['naver','instagram','facebook','youtube'];
$sns_name = ['네이버','인스타그램','페이스북','유튜브'];

$ajax_info_url = G5_INFLUENCER_URL.'/tail/ajax.info.php';
$link_url = G5_SOCIAL_LOGIN_URL.'/link.php';

if(!empty($action)) {
    $opt = array(
        "type" => 'instant',
        "delay" => 2000
    );
    util::alert('수정완료',$opt);
}

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
                    <input type="text" value="<?=$user['mb_name']?>" id="name">
                </td>
            </tr>
            <tr>
                <td class="th">성별</td>
                <td>
                    <div class="radio_box">
                        <div>
                            <label>
                                <input type="radio" name="sex" value="m" <?if($user['mb_sex'] === 'm') echo 'checked'?>>
                                <span>남자</span>
                            </label>
                        </div>
                        <div>
                            <label>
                                <input type="radio" name="sex" value="w" <?if($user['mb_sex'] === 'w') echo 'checked'?>>
                                <span>여자</span>
                            </label>
                        </div>
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
                        <div id="cert">본인인증</div>
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
                    echo '<td><div data-id="'.$v.'">연결하기</div></td>';
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

        var param = {
            'urlcode' : '030002', //KMC 어드민에서 등록한 URLCODE (https://ac2.linkprice.com)
            'nation': 0,
            'app': 0
        }
        
        //안드로이드나 iOS일 경우 파라미터 추가
        if(navigator.userAgent.indexOf('Mobile') > -1) {
            param.app = 1;
        }

        $.ajax({
            method: 'post',
            url: 'https://api.linkprice.com/certification/person_check.php',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            },
            data: param
        }).then(response => {
            window.open('', 'popForm', "toolbar=no, width=540, height=467, directories=no, status=no, scrollorbars=no, resizable=no");
            
            var solution = JSON.parse(response);

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

            // 폼 객체 삭제
            var child = document.getElementById("reqKMCISForm");
            child.parentNode.removeChild(child);
        });
    })

    $('.info_option_box div[name=category]').click(function(){
        var _this = $(this);
        if(_this.hasClass('sel')) _this.removeClass('sel');
        else _this.addClass('sel');
    })

    $('.info_option_box #update').click(function(){
        var name        = $('.info_option_box #name').val();
        var sex         = $('.info_option_box [name=sex]:checked').val();
        var email1      = $('.info_option_box #email1').val();
        var email2      = $('.info_option_box #email2').val();
        var email       = email1 + '@' + email2;
        var agree       = $('.info_option_box [name=agree]:checked').val();   
        var zip_code    = $('.info_option_box #zip_code').val();
        var addr1       = $('.info_option_box #addr1').val();
        var addr2       = $('.info_option_box #addr2').val();
        var category    = [];
        var sns_channel = $('.info_option_box [name=sns]:checked').val();

        if(_.isEmpty(name)) {
            util.alert('이름을 입력해주세요',{type:'instant'});
            return;
        }

        if (!email.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
            util.alert('이메일을 확인해주세요',{type:'instant'});
            return;
        }

        $(".info_option_box .category_box .sel").each(function(k, v) {
            category.push($(v).data('id'));
        });
        category = category.join('');

        data.ajax("<?=$ajax_info_url?>",{
            action      : 'updateInfo',
            category    : category,
            sex         : sex,
            email       : email,
            agree       : agree,
            zip_code    : zip_code,
            addr1       : addr1,
            addr2       : addr2,
            sns_channel : sns_channel,
            name        : name
        },updateCB);
    })

    function updateCB(res) {
        if(res.code === 0) {
            util.alert('정보 수정 완료',{type:'instant'});
        }
    }

    $('.info_option_box .channel_box div[data-id]').click(function(){
        var id = $(this).data('id');
        location.href = "<?=$link_url?>" + "?sns="+id;
    })

</script>

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>