$(function() {
    $(".btn-add-recommend").click(function(event) {
        //이벤트 버블링 중복 방지
        event.preventDefault();

        var $t = $(this);
        data.ajax('/myshop/ajax.setRecommend.php', {
            it_id: $t.data('it-id'),
        }, function(res) {
            // 등록이 성공하면 추천 상품을 최상단으로 보낸다.
            if(res.code === 0) {
                $t.closest('div.itemBox').remove();
                $("div#recommendItemBoxList").prepend($t.closest('div.itemBox').wrap("<div/>").parent().html());
            }

            util.alert(res.msg);
        });
    });

    $(".btn-wish").click(function(event) {
        //이벤트 버블링 중복 방지
        event.preventDefault();

        var $t = $(this);
        data.ajax('/myshop/ajax.setWish.php', {
            it_id: $t.data('it-id')+'',
        }, function(res) {
            if(res.code === 0) {
                if(res.use === 0) $t[0].textContent = '찜 취소됨';
                else $t[0].textContent = '찜';
            }

            util.alert(res.msg);
        });
    });
});