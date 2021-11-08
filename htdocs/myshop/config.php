<?php
/**
 * 마이샵 설정 화면
 * @todo: [승대] 마이샵 설정 화면 코딩
 */

include_once('./_common.php');

$action = util::param('action');


if($action === 'insert') {
    $myshopName             = util::param('myshop_name');
    $configChannelIds       = util::param('config_channel_ids');
    $configChannelTypes     = util::param('config_channel_types');
    $configChannelUrls      = util::param('config_channel_urls');
    $configChannelDefault   = util::param('config_channel_default');

    $mbNo                   = data::getLoginMember()['mb_no'];

    if(!empty($myshopCoverImage)) {
        $isFileUpload = util::upload(G5_DATA_PATH.'/myshop/coverImage', $mbNo);
        if ($isFileUpload === false) {
            util::alert("파일업로드에 실패하였습니다.", function() {
                util::reload();
            });
        }
    }

    sql_fetch_data("
    SELECT  count(1) cnt
    FROM    g5_influencer_myshop
    WHERE   mb_no = ?", $myshop, array($mbNo));


    if($myshop['cnt'] > 0) {
        //update
        $result = sql_update('g5_influencer_myshop', array(
            'in_myshop_name'    => $myshopName
        ), "mb_no = {$mbNo}");
    } else {
        //insert
        $result = sql_insert("g5_influencer_myshop", array(
            'mb_no'             => $mbNo,
            'in_myshop_name'    => $myshopName
        )) > 0;
    }

    //채널 정보 입력
    foreach($configChannelIds as $cnt => $chId) {
        if(empty($configChannelUrls[$cnt])) {
            continue;
        }

        $default = ($configChannelDefault == $cnt) ? 'Y' : 'N';

        if(empty($chId)) {
            //insert
            sql_insert("g5_influencer_myshop_channel", array(
                'mb_no'                 => $mbNo,
                'ch_channel_type'       => $configChannelTypes[$cnt],
                'ch_channel_url'        => $configChannelUrls[$cnt],
                'ch_channel_default'    => $default
            ));
        } else {
            //update
            sql_update("g5_influencer_myshop_channel", array(
                'ch_channel_type'       => $configChannelTypes[$cnt],
                'ch_channel_url'        => $configChannelUrls[$cnt],
                'ch_channel_default'    => $default
            ), "mb_no = {$mbNo} and ch_id = {$chId}");
        }
    }

    //파일 업로드
    if($result) {
        $uploadResult       = util::upload(G5_DATA_PATH . '/myshop/coverImage', "myshop_cover_image", $mbNo);

        if($uploadResult['code'] === 0) {
            sql_update('g5_influencer_myshop', array(
                'in_myshop_cover_image' => $uploadResult['file_name']
            ), "mb_no = {$mbNo}");
        }
    }

    util::alert("수정되었습니다.", function() {
        return util::location(G5_URL . '/myshop');
    });
}


include_once(G5_THEME_PATH . '/head.sub.php');


$mb_id = util::param("id");

//관리자 화면
$isAdmin = false;
if(empty($mb_id)) {
    $mb_id = data::getLoginMember()['mb_id'];
    if(empty($mb_id)) {
        util::alert("로그인해주세요.");
        util::location(G5_URL);
    }

    $isAdmin = true;
}

if(!$isAdmin) {
    util::alert("권한이 없습니다.");
    util::location(G5_URL . '/myshop');
}

//인플루언서 마이샵 정보 로드
sql_fetch_data("
SELECT  in_id, mb_no, in_myshop_name, in_myshop_cover_image, in_total_count, in_today_count
FROM    g5_influencer_myshop
WHERE   mb_no = ?", $myshopData, array(data::getLoginMember()['mb_no']));

if(empty($myshopData)) {
    $myshopData = array(
        'in_myshop_name'        => '',
        'in_myshop_cover_image' => ''
    );
} else {
    //채널 정보 로드
    sql_fetch_arrays("
    SELECT  ch_id, ch_channel_type, ch_channel_url, ch_channel_default
    FROM    g5_influencer_myshop_channel
    WHERE   mb_no = ?", $channelData, array(data::getLoginMember()['mb_no']));
}



?>
<form action="" name="frm" method="post" target="formSubmitIframe" enctype="multipart/form-data">
    <input type="hidden" name="action" value="insert" />

    <h2>마이샵 이름</h2>
    <p><input type="text" name="myshop_name" value="<?=$myshopData['in_myshop_name']?>" /></p>

    <h2>배경 커버</h2>
    <? if(!empty(url::getMyshopCoverImage($myshopData['in_myshop_cover_image']))) { ?>
        <img src="<?=url::getMyshopCoverImage($myshopData['in_myshop_cover_image'])?>" />
    <? } ?>


    <p><input type="file" name="myshop_cover_image" /></p>

    <h2>채널정보 (필수사항)</h2>

    <? for($i=0; $i<3; $i++) { ?>
        <? if(empty($channelData[$i])) {
            $channelData[$i] = array(
                'ch_id'              => '',
                'ch_channel_default' => empty($channelData[$i]) && $i === 0 ? 'Y' : 'N',
                'ch_channel_type'    => '',
                'ch_channel_name'    => ''
            );
        }
        $c = $channelData[$i];?>

        <div>
            <input type="hidden" name="config_channel_ids[]" value="<?=$c['ch_id']?>"/>

            <!-- 대표채널 지정 -->
            <input type="radio" name="config_channel_default" value="<?=$i?>" <?=$c['ch_channel_default'] == 'Y' ? 'checked' : ''?> />

            <!-- 채널 종류 -->
            <select name="config_channel_types[]">
                <option value="INSTAGRAM" <?=$c['ch_channel_type'] === 'INSTAGRAM' ? 'selected' : ''?> >인스타그램</option>
                <option value="YOUTUBE" <?=$c['ch_channel_type'] === 'YOUTUBE' ? 'selected' : ''?> >유튜브</option>
                <option value="BLOG" <?=$c['ch_channel_type'] === 'BLOG' ? 'selected' : ''?> >블로그</option>
                <option value="FACEBOOK" <?=$c['ch_channel_type'] === 'FACEBOOK' ? 'selected' : ''?> >페이스북</option>
                <option value="ETC" <?=$c['ch_channel_type'] === 'ETC' ? 'selected' : ''?> >기타</option>
            </select>

            <!-- 채널 주소 -->
            <input type="text" name="config_channel_urls[]" value="<?=$c['ch_channel_url'] ?? ''?>"/>
        </div>
    <? } ?>

    <div id="config_channel_save" style="width:50px; height:50px;background-color: blue;">저장</div>
    <div id="config_channel_cancel" style="width:50px; height:50px;background-color: red;">취소</div>
</form>

<script>
    $("#config_channel_save").click(function() {
        var isSubmit = true;
        var myshopName = $("input[name='myshop_name']").val();

        if(_.trim(myshopName) === '') {
            util.alert("마이샵 이름을 입력해주세요.");
            return false;
        }

        $("input[name='config_channel_urls[]']").each(function() {
            if(_.trim($(this).val()) === '') return true;
            if(!url.isValidURL($(this).val())) {
                util.alert('URL 형식이 맞지 않습니다.');
                isSubmit = false;
                return false;
            }
        });

        if(isSubmit) document.frm.submit();
    });

    $("#config_channel_cancel").click(function() {
        console.log('test');
        window.location = '/myshop';
    });
</script>

<?include_once(G5_SHOP_PATH.'/shop.tail.php');