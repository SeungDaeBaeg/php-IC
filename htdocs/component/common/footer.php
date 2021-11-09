</div>

<!-- footer 시작 -->

<!-- @todo: [승대] 하단 네비게이션바 -->
<div id="ft">
    <div class="ft_nabi visible-mobile">
        <div class="ft_item" id="cate_btn">
            <div class="ft_item_icon category_icon"></div>
            <div class="ft_nabi_txt">카테고리</div>
        </div>
        <div class="ft_item" id="reco_btn">
            <div class="ft_item_icon star_icon"></div>
            <div class="ft_nabi_txt">추천상품</div>
        </div>
        <div class="ft_item" id="info_btn">
            <div class="ft_item_icon myinfo_icon"></div>
            <div class="ft_nabi_txt">내정보</div>
        </div>
        <div class="ft_item" id="wish_btn">
            <div class="ft_item_icon heart_icon"></div>
            <div class="ft_nabi_txt">나의찜</div>
        </div>
    </div>
    <a href="#" id="ft_to_top"><i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></a>
</div>

<?php
$file = $_SERVER['SCRIPT_NAME'];
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?=G5_JS_URL?>/sns.js"></script>

<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
    $(function() {
        var $sv_use = $(".sv_use");
        var count = $sv_use.length;

        $sv_use.each(function() {
            $(this).css("z-index", count);
            $(this).css("position", "relative");
            count = count - 1;
        });
    });
</script>
<![endif]-->

<!-- form submit용 hidden iframe -->
<iframe name="formSubmitIframe" style="visibility: hidden;display: none;"></iframe>

<!--
모달창
@todo: [승대] 모달창 코딩 필요
-->
<div id="icAlert" style="position:fixed;background-color:green;width:600px;height:auto;margin-left:-300px;margin-top:-100px;left:50%;top:50%;z-index:99999;display: none;">
    <div id="icAlertMsg" style="text-align: center;margin-top:10px;margin-bottom: 10px; font-weight: bold; font-size: 16pt;"></div>
    <div id="icAlertBottom" style="text-align: center; margin-top:10px;margin-bottom: 10px; display: none;">
        <button id="icAlertOk" style="width:60px;height:30px;font-size: 12pt;">확인</button>
        <button id="icAlertCancel" style="width:60px;height:30px;font-size: 12pt;">취소</button>
    </div>
</div>

</body>
</html>
<?=html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>

<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
    $(function() {
        var $sv_use = $(".sv_use");
        var count = $sv_use.length;

        $sv_use.each(function() {
            $(this).css("z-index", count);
            $(this).css("position", "relative");
            count = count - 1;
        });
    });
</script>
<![endif]-->

</body>
</html>

<?=html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>

<script>
    $("#cate_btn").on("click", function() {
        $("#category").show();
    });
    $("#reco_btn").on("click", function() {
        location.href = '/influencer/tail/recommend.php';
    });
    $("#info_btn").on("click", function() {
        location.href = '/influencer/tail/info.php';
    });
    $("#wish_btn").on("click", function() {
        location.href = '/influencer/tail/wish.php';
    });
</script>