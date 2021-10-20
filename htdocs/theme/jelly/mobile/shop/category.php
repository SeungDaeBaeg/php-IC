<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function get_mshop_category($ca_id, $len)
{
    global $g5;

    $sql = " select ca_id, ca_name from g5_shop_category
                where ca_use = '1' ";
    if($ca_id)
        $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " and length(ca_id) = '$len' order by ca_order, ca_id ";

    return $sql;
}
?>

<div id="category" class="menu">
    <button type="button" class="menu_close"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">카테고리닫기</span></button>
    <div class="btn_login">
        <input type="text" placeholder="상품을 검색하세요" />
        <button>검색</button>
    </div>

    <?=outlogin('theme/shop_basic', true) // 외부 로그인 ?>

    <div class="menu_wr">
        <ul class="cate">
            <li>
                <a>추천</a>
            </li>
            <li>
                <a>이벤트</a>
            </li>
            <li>
                <a>마이샵</a>
            </li>
            <li>
                <a>리포트</a>
            </li>
            <li>
                <a>출금관리</a>
            </li>
        </ul>

    </div>
</div>

<?=outlogin('theme/shop_basic', false) // 외부 로그인 ?>
<script>
$(function (){

    $("button.sub_ct_toggle").on("click", function() {
        var $this = $(this);
        $sub_ul = $(this).closest("li").children("ul.sub_cate");

        if($sub_ul.size() > 0) {
            var txt = $this.text();

            if($sub_ul.is(":visible")) {
                txt = txt.replace(/닫기$/, "열기");
                $this
                    .removeClass("ct_cl")
                    .text(txt);
            } else {
                txt = txt.replace(/열기$/, "닫기");
                $this
                    .addClass("ct_cl")
                    .text(txt);
            }

            $sub_ul.toggle();
        }
    });


    $(".content li.con").hide();
    $(".content li.con:first").show();   
    $(".cate_tab li a").click(function(){
        $(".cate_tab li a").removeClass("selected");
        $(this).addClass("selected");
        $(".content li.con").hide();
        //$($(this).attr("href")).show();
        $($(this).attr("href")).fadeIn();
    });
     
});
</script>
