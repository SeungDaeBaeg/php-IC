<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>
<?php if ($category_option) { ?>
<!-- 카테고리 시작 { -->
<nav id="bo_cate">
    <h2><?=$qaconfig['qa_title'] ?> 카테고리</h2>
    <ul id="bo_cate_ul">
        <?=$category_option ?>
    </ul>
</nav>
<!-- } 카테고리 끝 -->
<?php } ?>
<div id="bo_list">



    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post">
    <input type="hidden" name="stx" value="<?=$stx; ?>">
    <input type="hidden" name="sca" value="<?=$sca; ?>">
    <input type="hidden" name="page" value="<?=$page; ?>">
 
    <div class="bo_fx">

        <?php if ($admin_href || $write_href) { ?>
        <ul>
            <?php if ($admin_href) { ?><li><a href="<?=$admin_href ?>" class="btn_admin btn_m"><i class="fa fa-cog" aria-hidden="true"></i><span class="sound_only">관리자</span></a></li><?php } ?>
            <?php if ($is_checkbox) { ?><li><input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn_m btn_b01"></li> <?php } ?>
            <?php if ($write_href) { ?><li><a href="<?=$write_href ?>" class="btn_b02 btn_m">문의등록</a></li><?php } ?>
        </ul>
        <?php } ?>

        
    </div>
    <?php if ($is_checkbox) { ?>
    <div id="list_chk" class="all_chk">
        <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
        <label for="chkall"><span class="chk_img"></span> 게시물 전체선택</label>
    </div>
    <?php } ?>

    <div class="qa_list">
        <ul>
            <?php
            for ($i=0; $i<count($list); $i++) {
            ?>
            <li class="bo_li<?php if ($is_checkbox) echo ' bo_adm'; ?>">

                <div class="li_title">
                    <?php if ($is_checkbox) { ?>
                    <span class="bo_chk li_chk">
                        <label for="chk_qa_id_<?=$i ?>"><span class="chk_img"></span><span  class="sound_only"><?=$list[$i]['subject']; ?></span></label>
                        <input type="checkbox" name="chk_qa_id[]" value="<?=$list[$i]['qa_id'] ?>" id="chk_qa_id_<?=$i ?>">
                    </span>
                    <?php } ?>
                    <strong><?=$list[$i]['category']; ?></strong>
                    <a href="<?=$list[$i]['view_href']; ?>" class="li_sbj">
                        <?=$list[$i]['subject']; ?><span> <?=$list[$i]['icon_file']; ?></span>
                    </a>
                </div>
                <div class="li_info">
                    <span><?=$list[$i]['name']; ?></span>
                    <span><i class="fa fa-clock-o" aria-hidden="true"></i> <?=$list[$i]['date']; ?></span>
                    
                    <div class="li_stat <?=($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?=($list[$i]['qa_status'] ? '<i class="fa fa-check-circle" aria-hidden="true"></i> 답변완료' : '<i class="fa fa-times-circle" aria-hidden="true"></i> 답변대기'); ?></div>

                </div>
            </li>
            <?php
            }
            ?>

            <?php if ($i == 0) { echo '<li class="empty_list">게시물이 없습니다.</li>'; } ?>
        </ul>
    </div>

    </form>

        <!-- 게시판 검색 시작 { -->
    <fieldset id="bo_sch">
        <legend>게시물 검색</legend>

        <form name="fsearch" method="get">
        <input type="hidden" name="sca" value="<?=$sca ?>">
        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="stx" value="<?=stripslashes($stx) ?>" required id="stx" class="sch_input required" size="15" maxlength="15">
        <button type="submit" value="검색" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i> <span class="sound_only">검색</span></button>
        </form>
    </fieldset>
    <!-- } 게시판 검색 끝 -->

</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?=$list_pages;  ?>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fqalist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]")
            f.elements[i].checked = sw;
    }
}

function fqalist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
            return false;
    }

    return true;
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->