
<div id="category" class="menu">
    <button type="button" class="menu_close"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">카테고리닫기</span></button>

    <div>
        <? foreach($categorys as $category) { ?>
            <div class="category_list -mouse-pointer" data-ca-id="<?=$category['ca_id']?>">
                <?=$category['ca_name']?>
            </div>
        <? } ?>
    </div>
</div>

<script>
    $(function() {
        $(".menu_close").click(function() {
            $(".menu").hide();
        });

        $(".category_list").click(function() {
            window.location = "/influencer/search.php?category=" + $(this).data('ca-id');
        });
    });
</script>

<style>
    .category_list {
        position:relative;
        width:4rem;
        height:4rem;
        margin-right:0.2rem;
        margin-bottom:0.2rem;
        float:left;
        background-color: #00C73C;
    }
</style>