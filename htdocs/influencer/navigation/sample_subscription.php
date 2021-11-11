<?php
/**
 * 샘플 신청
 */

include_once('../_common.php');
util::loginCheck();

$id = util::param(array("it_id","ev_id"), "상품 아이디가 없습니다.");
$action = util::param("action");

$it_id = $id['it_id'];
$ev_id = $id['ev_id'];
$mb_no = data::getLoginMember()['mb_no'];

//이벤트로 들어온거는 event_party_join들어가서 참여내역을 보여줘야된다.
if($ev_id) {
    $sql = "
    SELECT  party.it_id
    FROM    g5_shop_party party, g5_shop_event event
    WHERE   party.ev_id = ?
    AND     party.ev_id = event.ev_id";
    sql_fetch_data($sql,$res, array($ev_id));
    
    $it_id = $res['it_id'];
}

if(!empty($action)) {
    $params = util::param(array('it_id', 'name', 'hp', 'email', 'sns_id', 'sns_followers', 'sns_link', 'sns_channel', 'zip_code', 'addr1', 'addr2', 'options', 'referer'));

    $p = array(
        'it_id'             => $params['it_id'],
        'io_id'             => $params['options'],
        'mb_no'             => $mb_no,
        'join_name'         => $params['name'],
        'join_hp'           => $params['hp'],
        'join_zip_code'     => $params['zip_code'],
        'join_addr1'        => $params['addr1'],
        'join_addr2'        => $params['addr2'],
        'sns_channel'       => $params['sns_channel'],
        'sns_follower'      => $params['sns_followers'],
        'sns_link'          => $params['sns_link']
    );

    if($ev_id) {
        $p['ev_id'] = $ev_id;
    }

    $id = sql_insert("g5_shop_party_join", $p);

    if($ev_id) {
        util::alert($id > 0 ? "정상적으로 등록이 완료되었습니다." : "등록되지 않았습니다. 관리자에게 문의해주세요.", function() use($params) {
            return util::location($params['referer']);
        });
    } else {
        util::alert($id > 0 ? "정상적으로 등록이 완료되었습니다." : "등록되지 않았습니다. 관리자에게 문의해주세요.", function() use($it_id) {
            return util::location('/shop/item.php?it_id=' . $it_id);
        });
    }
}

//상품 정보
$item = data::getAvailbleItems(" and it_id = $it_id");

$it_option_subjects = explode(",", $item['it_option_subject']) ?? array();

//상품 옵션 정보
$options = item::getItemOptions($it_id);

//회원 정보
$member = data::getLoginMember();

//마이페이지 수정 바로가기
$my_url = G5_INFLUENCER_URL.'/tail/info_option.php';

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

/**
 * 
 * @todo : [승대] PPT 31페이지, 샘플 신청서
 */
?>

<!--content start-->

<h2>샘플 신청서</h2>
<table style="width:100%" border="1">
    <tr>
        <th>샘플신청 상품</th>
        <td>
            <p><?=$item[0]['it_name']?></p>
        </td>
    </tr>

    <? if(count($options) > 0) { ?>
        <? foreach($options as $subject => $option) { ?>
            <tr>
                <th><?=$subject?></th>
                <td>
                    <select name="options[<?=$subject?>]" class="options">
                        <? foreach($option as $o) { ?>
                            <option value="<?=$o?>"><?=$o?></option>
                        <? } ?>
                    </select>
                </td>
            </tr>
        <? } ?>
    <? } ?>
    <tr>
        <th>이름</th>
        <td>
            <p>
                <input type="text" id="name" value="<?=$member['mb_name']?>" readonly />
            </p>
        </td>
    </tr>
    <tr>
        <th>연락처</th>
        <td>
            <p>
                <input type="text" id="hp" value="<?=$member['mb_hp']?>" readonly />
            </p>
        </td>
    </tr>
    <tr>
        <th>이메일</th>
        <td>
            <p>
                <input type="text" id="email" value="<?=$member['mb_email']?>" readonly />
            </p>
        </td>
    </tr>
    <tr>
        <th>SNS 채널</th>
        <td>
            <input type="text" id="sns_channel" value="test" />
        </td>
    </tr>
    <tr>
        <th>SNS 아이디</th>
        <td>
            <input type="text" id="sns_id" value="test" />
        </td>
    </tr>
    <tr>
        <th>SNS 팔로워/구독자수</th>
        <td>
            <input type="number" id="sns_followers" value="3" />
        </td>
    </tr>
    <tr>
        <th>SNS 계정링크</th>
        <td>
            <input type="text" id="sns_link" value="https:www.test.com" />
        </td>
    </tr>
    <tr style="height: 18px;">
        <th rowspan="3">주소</th>
        <td>
            <input type="text" id="zip_code" value="<?=$member['mb_zip1']?>" readonly />
        </td>
    </tr>
    <tr style="height: 18px;">
        <td>
            <input type="text" id="addr1" value="<?=$member['mb_addr1']?>" readonly />
        </td>
    </tr>
    <tr style="height: 18px;">
        <td>
            <input type="text" id="addr2" value="<?=$member['mb_addr2']?>" />
            <button id="post_btn">우편번호</button>
        </td>
    </tr>
</table>

<input type="hidden" id="referer" value="<?=$_SERVER['HTTP_REFERER']?>" />

<p>
    <button id="subscription_complete" style="width:100%;">신청완료</button>
</p>

<div>
<pre>
샘플 신청서에 입력된 정보로 상품 발송되오니,
기입된 정보 확인 후 신청해 주세요.

정보 변경은 <a href="<?=$my_url?>">마이페이지(바로가기)</a>에서 가능하오니,
정보 변경 후 다시 신청해 주세요.
</pre>
</div>

<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script>
    $("#post_btn").click(function() {
        new daum.Postcode({
            oncomplete: function(data) {
                console.log(data);
                $("#addr1").val(data.roadAddress);
                $("#zip_code").val(data.zonecode);
            }
        }).open();
    });

    //form submit
    $("#subscription_complete").click(function() {
        //선택한 옵션 처리
        var options = [];
        $("select.options").each(function(k, v) {
            options.push($(v).val());
        });
        options = options.join('');
        
        util.formSubmit("", [
            {name: "action",            value: true},
            {name: "it_id",             value: "<?=$it_id?>",               validation: "상품 아이디가 없습니다."},
            {name: "name",              value: $("#name").val(),            validation: "이름을 입력해주세요."},
            {name: "hp",                value: $("#hp").val(),              validation: "핸드폰을 입력해주세요."},
            {name: "email",             value: $("#email").val(),           validation: "이메일을 입력해주세요."},
            {name: "sns_id",            value: $("#sns_id").val(),          validation: "SNS 아이디를 입력해주세요."},
            {name: "sns_followers",     value: $("#sns_followers").val(),   validation: function(value) {
                if($.trim(value) === '') {
                    alert("SNS 팔로워/구독자수를 입력해주세요.");
                    return false;
                } else if(!_.isNumber(parseInt(value))) {
                    alert("숫자를 입력해주세요.");
                    return false;
                }
                return true;
            }},
            {name: "sns_link",          value: $("#sns_link").val(),        validation: function(value) {
                if($.trim(value) === '') {
                    alert('SNS 계정링크를 입력해주세요.');
                    return false;
                } else if(!url.isValidURL(value)) {
                    alert("계정 링크 형식이 맞지 않습니다.");
                    return false;
                }
                return true;
            }},
            {name: "sns_channel",       value: $("#sns_channel").val(),     validation: "SNS 채널을 입력해주세요."},
            {name: "zip_code",          value: $("#zip_code").val(),        validation: "우편번호를 입력해주세요."},
            {name: "addr1",             value: $("#addr1").val(),           validation: "주소를 입력해주세요."},
            {name: "addr2",             value: $("#addr2").val(),           validation: "주소상세를 입력해주세요."},
            {name: "options",           value: options},
            {name: "referer",           value: $('#referer').val()}
        ]);
    });
</script>


<!--content end -->

<? include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');