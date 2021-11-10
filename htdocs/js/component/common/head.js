$( document ).ready( function() {
    var jbOffset = $( '#hd_wr' ).offset();
    $( window ).scroll( function() {
        if ( $( document ).scrollTop() > jbOffset.top ) {
            $( '#hd_wr' ).addClass( 'fixed' );
        }
        else {
            $( '#hd_wr' ).removeClass( 'fixed' );
        }
    });
});

$("#btn_cate").on("click", function() {
    $("#category").show();
});

$(".cate_bg").on("click", function() {
    $(".menu").hide();
});

$(".btn_ol").on("click", function() {
    $(".ol").show();
});

$(".ol .btn_close").on("click", function() {
    $(".ol").hide();
});

$("#btn_sch").on("click", function() {
    $("#hd_sch").show();
});

$("#hd_sch .btn_close").on("click", function() {
    $("#hd_sch").hide();
});

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

    $("#btn_search, #btn_search_mobile").click(function() {
        var searchTxt = encodeURIComponent($("#" + ($(this).attr("id") === 'btn_search' ? 'txt_search' : 'txt_search_mobile')).val());
        util.formSubmit('/influencer/search.php', [
            {name: 'qs',            value: searchTxt},
            {name: 'recommend',     value: url.getUrlParam('recommend')}
        ], {
            method: 'get',
            isNotIframe: true
        });
    });
});

function search_submit(f) {
    if (f.q.value.length < 2) {
        alert("검색어는 두글자 이상 입력하십시오.");
        f.q.select();
        f.q.focus();
        return false;
    }

    return true;
}