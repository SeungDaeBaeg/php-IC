<?php
/**
 * 이벤트 관리 페이지
 */
include_once('../_common.php');

$ev_id = $_GET['ev_id'] ?? '';

$sql = "select party.options, party.is_sample, event.ev_subject from g5_shop_party party, g5_shop_event event where party.ev_id = '".$ev_id."' and party.ev_id = event.ev_id";

sql_fetch_data($sql,$res);

$userData = data::getLoginMember();

//샘플 신청서가 없는 참여신청일 때
if($res['is_sample'] === 'N') {
    $sql = "select count(1) cnt from g5_shop_party_join where ev_id = ? and mb_id = ?";
    sql_fetch_data($sql,$res,array($ev_id,$userData['mb_id']));
    
    if($res["cnt"]) {
        util::alert("신청된 기록이 있습니다",true);
    }
    else {
        $id = sql_insert("g5_shop_party_join",array(
            'ev_id' => $ev_id,
            'mb_id' => $userData['mb_id']
        ));
        
        util::location("/influencer/event/list.php?join=success");
    }
    exit;
}

define("_INDEX_", TRUE);
include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/event.css">', 0);
add_javascript(G5_POSTCODE_JS, 0);

$ajax_event_url = G5_INFLUENCER_URL.'/event/ajax.event.php';


?>
<!--content start-->
<div class="sample_head">
    샘플 신청서
</div>
<div class="smaple_box">
    <table>
        <tr>
            <td>상품명</td>
            <td><?=$res['ev_subject']?></td>
        </tr>
        <?
            $options = explode('|',$res['options']);
            for($i = 0; $i < count($options); $i++) {
                echo '<tr>';
                echo '<td>옵션'.($i+1).'</td>';
                $option = explode(',',$options[$i]);
                echo '<td>';
                echo '<select name="option_'.$i.'">';
                for($j = 0; $j <count($option); $j++) {
                    $v = $option[$j];
                    echo '<option value="'.$v.'">'.$v.'</option>';
                }
                echo '</select></td></tr>';
            }
        ?>
        <tr>
            <td>이름</td>
            <td><?=$userData['mb_name']?></td>
        </tr>
        <tr>
            <td>연락처</td>
            <td><?=$userData['mb_hp']?></td>
        </tr>
        <tr>
            <td>이메일</td>
            <td><?=$userData['mb_email']?></td>
        </tr>
        <tr>
            <td>SNS 아이디</td>
            <td><input type="text"></td>
        </tr>
        <tr>
            <td>SNS 팔로워/구독자수</td>
            <td><input type="text"></td>
        </tr>
        <tr>
            <td>SNS 계정링크</td>
            <td><input type="text"></td>
        </tr>
        <tr>
            <td rowspan=3>주소</td>
            <td><?=$userData['mb_zip1']?></td>
        </tr>
        <tr>
            <td><?=$userData['mb_addr1']?></td>
        </tr>
        <tr>
            <td><?=$userData['mb_addr2'].$userData['mb_addr3']?></td>
        </tr>
    </table>
    <button onclick="submit()">신청완료</button>
</div>

<script>

function submit() {
    data.ajax('<?=$ajax_event_url?>', {action:'getCodeName'}, getCodeNameCB);
}

</script>

<!--content end -->

<?
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>