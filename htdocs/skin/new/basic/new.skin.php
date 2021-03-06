<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 선택삭제으로 인해 셀합치기가 가변적으로 변함
$colspan = 5;

if ($is_admin) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$new_skin_url.'/style.css">', 0);
?>

<!-- 전체게시물 검색 시작 { -->
<fieldset id="new_sch">
    <legend>상세검색</legend>
    <form name="fnew" method="get">
    <?=$group_select ?>
    <label for="view" class="sound_only">검색대상</label>
    <select name="view" id="view">
        <option value="">전체게시물
        <option value="w">원글만
        <option value="c">코멘트만
    </select>
    <label for="mb_id" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="mb_id" value="<?=$mb_id ?>" id="mb_id" required class="frm_input" size="40">
    <button type="submit" class="btn_submit"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
    <p>회원 아이디만 검색 가능</p>
    </form>
    <script>
    /* 셀렉트 박스에서 자동 이동 해제
    function select_change()
    {
        document.fnew.submit();
    }
    */
    document.getElementById("gr_id").value = "<?=$gr_id ?>";
    document.getElementById("view").value = "<?=$view ?>";
    </script>
</fieldset>
<!-- } 전체게시물 검색 끝 -->

<!-- 전체게시물 목록 시작 { -->
<form name="fnewlist" id="fnewlist" method="post" action="#" onsubmit="return fnew_submit(this);">
<input type="hidden" name="sw"       value="move">
<input type="hidden" name="view"     value="<?=$view; ?>">
<input type="hidden" name="sfl"      value="<?=$sfl; ?>">
<input type="hidden" name="stx"      value="<?=$stx; ?>">
<input type="hidden" name="bo_table" value="<?=$bo_table; ?>">
<input type="hidden" name="page"     value="<?=$page; ?>">
<input type="hidden" name="pressed"  value="">

<?php if ($is_admin) { ?>
<div class="admin_new_btn">
    <button type="submit" onclick="document.pressed=this.title" title="선택삭제" class="btn_b01 btn"><i class="fa fa-trash-o" aria-hidden="true"></i><span class="sound_only">선택삭제</span></button>
</div>
<?php } ?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
        <?php if ($is_admin) { ?>
        <th scope="col" class="chk_box">
        	<input type="checkbox" id="all_chk" class="selec_chk">
            <label for="all_chk">
            	<span></span>
				<b class="sound_only">목록 전체</b>
            </label>
        </th>
        <?php } ?>
        <th scope="col">그룹</th>
        <th scope="col">게시판</th>
        <th scope="col">제목</th>
        <th scope="col">이름</th>
        <th scope="col">일시</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $i<count($list); $i++)
    {
        $num = $total_count - ($page - 1) * $config['cf_page_rows'] - $i;
        $gr_subject = cut_str($list[$i]['gr_subject'], 20);
        $bo_subject = cut_str($list[$i]['bo_subject'], 20);
        $wr_subject = get_text(cut_str($list[$i]['wr_subject'], 80));
    ?>
    <tr>
        <?php if ($is_admin) { ?>
        <td class="td_chk chk_box">
            <input type="checkbox" name="chk_bn_id[]" value="<?=$i; ?>" id="chk_bn_id_<?=$i; ?>" class="selec_chk">
            <label for="chk_bn_id_<?=$i; ?>">
            	<span></span>
            	<b class="sound_only"><?=$num?>번</b>
            </label>
            <input type="hidden" name="bo_table[<?=$i; ?>]" value="<?=$list[$i]['bo_table']; ?>">
            <input type="hidden" name="wr_id[<?=$i; ?>]" value="<?=$list[$i]['wr_id']; ?>">
        </td>
        <?php } ?>
        <td class="td_group"><a href="./new.php?gr_id=<?=$list[$i]['gr_id'] ?>"><?=$gr_subject ?></a></td>
        <td class="td_board"><a href="<?=get_pretty_url($list[$i]['bo_table']); ?>"><?=$bo_subject ?></a></td>
        <td><a href="<?=$list[$i]['href'] ?>" class="new_tit"><?=$list[$i]['comment'] ?><?=$wr_subject ?></a></td>
        <td class="td_name"><?=$list[$i]['name'] ?></td>
        <td class="td_date"><?=$list[$i]['datetime2'] ?></td>
    </tr>
    <?php }  ?>

    <?php if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<?=$write_pages ?>

<?php if ($is_admin) { ?>
<div class="admin_new_btn">
    <button type="submit" onclick="document.pressed=this.title" title="선택삭제" class="btn_b01 btn"><i class="fa fa-trash-o" aria-hidden="true"></i><span class="sound_only">선택삭제</span></button>
</div>
<?php } ?>
</form>

<?php if ($is_admin) { ?>
<script>
$(function(){
    $('#all_chk').click(function(){
        $('[name="chk_bn_id[]"]').attr('checked', this.checked);
    });
});

function fnew_submit(f)
{
    f.pressed.value = document.pressed;

    var cnt = 0;
    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_bn_id[]" && f.elements[i].checked)
            cnt++;
    }

    if (!cnt) {
        alert(document.pressed+"할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if (!confirm("선택한 게시물을 정말 "+document.pressed+" 하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다")) {
        return false;
    }

    f.action = "./new_delete.php";

    return true;
}
</script>
<?php } ?>
<!-- } 전체게시물 목록 끝 -->