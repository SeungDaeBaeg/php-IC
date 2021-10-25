<?php
include_once('_common.php');

$g5['title'] = '인기검색어설정';
include_once('./head.php');
?>

<form name="fkeyword" id="fkeyword" method="post" action="./keywordupdate.php">
<div class="btn_confirm"><input type="submit" class="btn_save" value="저장"></div>

<div id="keyword" class="con_wr">
    <ol>
    <?php
    $save_file = G5_DATA_PATH.'/cache/theme/jelly/keyword.php';
    if(is_file($save_file))
        include($save_file);

    for($i=0; $i<10; $i++) {
    ?>
        <li class="li_clear">
            <div class="li_wr">
                <span class="rank"><?=($i + 1); ?></span>
                <label for="word_<?=$i; ?>" class="sound_only">검색어</label>
                <input type="text" name="word[]" id="word_<?=$i; ?>" class="frm_input" value="<?=get_text($keyword[$i]); ?>">
            </div>
        </li>
    <?php
    }
    ?>
    </ol>
</div>
</form>

<?php
include_once('./tail.php');
?>