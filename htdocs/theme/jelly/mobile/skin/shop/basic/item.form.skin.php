<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<? if($config['cf_kakao_js_apikey']) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?=G5_JS_URL?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?=$config['cf_kakao_js_apikey']?>");
</script>
<? } ?>

<form name="fitem" action="<?=$action_url?>" method="post" onsubmit="return fitem_submit(this);">
<input type="hidden" name="it_id[]" value="<?=$it['it_id']?>">
<input type="hidden" name="sw_direct">
<input type="hidden" name="url">

<div id="sit_ov_wrap">
    <!-- 상품이미지 미리보기 시작 { -->
    <div id="sit_pvi">
        <ul id="sit_pvi_big">
        <?php
        $big_img_count = 0;
        $thumbnails = array();
        for($i=1; $i<=10; $i++) {
            if(!$it['it_img'.$i])
                continue;

            $img = get_it_thumbnail($it['it_img'.$i], $default['de_mimg_width'], $default['de_mimg_height']);

            if($img) {
                // 썸네일
                $thumb = get_it_thumbnail($it['it_img'.$i], 80, 80);
                $thumbnails[] = $thumb;
                $big_img_count++;

                echo '<li><a href="'.G5_SHOP_URL.'/largeimage.php?it_id='.$it['it_id'].'&amp;no='.$i.'" target="_blank" class="popup_item_image">'.$img.'</a></li>';
            }
        }

        if($big_img_count == 0) {
            echo '<img src="'.G5_SHOP_URL.'/img/no_image.gif" alt="">';
        }
        ?>
        </ul>
        <?php
        // 썸네일
        $thumb1 = true;
        $thumb_count = 0;
        $total_count = count($thumbnails);
        if($total_count > 0) {
            echo '<div class="sit_pvi_thumb">';
            foreach($thumbnails as $val) {
                $sit_pvi_last ='';
                if ($thumb_count % 5 == 0) $sit_pvi_last = 'class="li_last"';
                    echo '<a href="" data-slide-index="'.$thumb_count.'">'.$val.'</a>';
                $thumb_count++;
            }
            echo '</div>';
        }
        ?>
        
        <script>
        $(document).ready(function(){
            $('#sit_pvi_big').show().bxSlider({
                speed:800,
                pagerCustom: '.sit_pvi_thumb',
                controls:false,
                auto: true,
                mode: 'fade'
            });
        });
        </script>
    </div>

    <section id="sit_ov" class="2017_renewal_itemform">
        <h2>상품간략정보</h2>
        <div class="sit_ov_wr">

            <strong id="sit_title"><?=stripslashes($it['it_name'])?></strong>

            <!-- @todo [승대] PPT 10P DESC 2 -->

            <div class="sit_price">
                <? if (!$it['it_use']) { // 판매가능이 아닐 경우 ?>
                    <div class="price_wr price_no">
                        <strong>판매가격</strong>
                        <span>판매중지</span>
                    </div>
                <? } else if ($it['it_tel_inq']) { // 전화문의일 경우 ?>
                    <div class="price_wr price_call">
                        <strong>판매가격</strong>
                        <span>전화문의</span>
                    </div>
                <? } else { // 전화문의가 아닐 경우?>
                    <? if ($it['it_cust_price'] > 0) { // 1.00.03?>
                        <div class="price_wr price_og">
                            <strong>시중가격</strong>
                            <span><?=display_price($it['it_cust_price'])?></span>
                        </div>
                        <div class="price_wr price">
                            <strong>판매가격</strong>
                            <span>
                                <?=display_price(get_price($it))?>

                                <?=util::percent($it['it_cust_price'], $it['it_price'])?>% 할인
                            </span>
                        </div>
                    <? } else { ?>
                        <div class="price_wr price">
                            <strong>판매가격</strong>
                            <span>
                                <?=display_price(get_price($it))?>
                            </span>
                        </div>
                    <? } ?>

                    <input type="hidden" id="it_price" value="<?=get_price($it)?>">

                <? } ?>
            </div>

            <div>
                <? if($it['it_sc_type'] == 1 || $it['it_sc_price'] <= 0) { ?>
                    무료 배송
                <? } else { ?>
                    배송비 <?=number_format($it['it_sc_price'])?>원
                <? } ?>

                <? if($it['it_earn_price'] > 0 && $it['it_earn_percent'] > 0) { ?>
                    <p>제품 판매 <?=number_format($it['it_earn_price'])?>원 적립
                        <? if($it['it_earn_percent'] > 0) { ?>
                            (<?=$it['it_earn_percent']?>% 적립)
                        <? } ?>
                    </p>
                <? } ?>
            </div>

            <div class="sit_ov_tbl">
                <div>
                    <a>판매가이드</a>
                    <a>상품정보</a>
                    <a>교환/환불</a>
                </div>
            </div>

            <!--판매가이드 -->
            <div class="site_ov_tbl">

            </div>

            <!--상품정보 -->
            <div class="site_ov_tbl">

            </div>

            <!--배송정보 -->
            <div class="site_ov_tbl">

            </div>

            <? if($is_orderable) { ?>
                <p id="sit_opt_info">
                    상품 선택옵션 <?=$option_count?> 개, 추가옵션 <?=$supply_count?> 개
                </p>
            <? } ?>

            <?php
            $sns_title = get_text($it['it_name']).' | '.get_text($config['cf_title']);
            $sns_url  = G5_SHOP_URL.'/item.php?it_id='.$it['it_id'];
            if ($score = get_star_image($it['it_id'])) { ?>
                <img src="<?=G5_SHOP_URL?>/img/s_star<?=$score?>.png" alt="" class="sit_star" width="100">(리뷰<strong><?=$it['it_use_cnt']?></strong>건)
            <? } ?>



            <div class="sit_ov_tbl">
                <table>
      
                <tbody>
                    <? if ($it['it_maker']) { ?>
                        <tr>
                            <th scope="row">제조사</th>
                            <td><?=$it['it_maker']?></td>
                        </tr>
                    <? } ?>

                    <? if ($it['it_origin']) { ?>
                        <tr>
                            <th scope="row">원산지</th>
                            <td><?=$it['it_origin']?></td>
                        </tr>
                    <? } ?>

                    <? if ($it['it_brand']) { ?>
                        <tr>
                            <th scope="row">브랜드</th>
                            <td><?=$it['it_brand']?></td>
                        </tr>
                    <? } ?>
                    <? if ($it['it_model']) { ?>
                        <tr>
                            <th scope="row">모델</th>
                            <td><?=$it['it_model']?></td>
                        </tr>
                    <? } ?>
                

                    <?php
                    /* 재고 표시하는 경우 주석 해제
                    <tr>
                        <th scope="row">재고수량</th>
                        <td><?=number_format(get_it_stock_qty($it_id))?> 개</td>
                    </tr>
                    */
                    ?>

                    <? if ($config['cf_use_point']) { // 포인트 사용한다면 ?>
                        <tr>
                            <th scope="row"><label for="disp_point">포인트</label></th>
                            <td>
                                <?php
                                if($it['it_point_type'] == 2) {
                                    echo '구매금액(추가옵션 제외)의 '.$it['it_point'].'%';
                                } else {
                                    $it_point = get_item_point($it);
                                    echo number_format($it_point).'점';
                                }
                                ?>
                            </td>
                        </tr>
                    <? } ?>
                <?php
                $ct_send_cost_label = '배송비결제';

                if($it['it_sc_type'] == 1)
                    $sc_method = '무료배송';
                else {
                    if($it['it_sc_method'] == 1)
                        $sc_method = '수령후 지불';
                    else if($it['it_sc_method'] == 2) {
                        $ct_send_cost_label = '<label for="ct_send_cost">배송비결제</label>';
                        $sc_method = '<select name="ct_send_cost" id="ct_send_cost">
                                          <option value="0">주문시 결제</option>
                                          <option value="1">수령후 지불</option>
                                      </select>';
                    }
                    else
                        $sc_method = '주문시 결제';
                }
                ?>
                <tr>
                    <th><?=$ct_send_cost_label?></th>
                    <td><?=$sc_method?></td>
                </tr>
                <? if($it['it_buy_min_qty']) { ?>
                <tr>
                        <th>최소구매수량</th>
                        <td><?=number_format($it['it_buy_min_qty'])?> 개</td>
                    </tr>
                <? } ?>
                <? if($it['it_buy_max_qty']) { ?>
                <tr>
                    <th>최대구매수량</th>
                    <td><?=number_format($it['it_buy_max_qty'])?> 개</td>
                </tr>
                <? } ?>
                </tbody>
                </table>
            </div>
            <script>
            $(".btn_ist").click(function(){
                $(".sit_ov_tbl table").toggle();
            });
            </script>
        </div>

        <div id="sit_star_sns">

            <!-- @todo 공유하기 start-->
<!--            <a href="javascript:item_wish(document.fitem, '--><?php //echo $it['it_id']?><!--');" id="sit_btn_wish"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="sound_only"> 위시리스트</span></a>-->
<!--            <button type="button" class="btn_sns_share"><i class="fa fa-share-alt" aria-hidden="true"></i><span class="sound_only">sns 공유</span></button>-->
            <!-- 공유하기 end -->

            <? if(false) { //@todo 추천하기 ?>
                <div class="sns_area">
                    <?=get_sns_share_link('facebook', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/facebook.png')?>
                    <?=get_sns_share_link('twitter', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/twitter.png')?>
                    <?=get_sns_share_link('googleplus', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/gplus.png')?>
                    <?=get_sns_share_link('kakaotalk', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/sns_kakao.png')?>
                    <?php
                    $href = G5_SHOP_URL.'/iteminfo.php?it_id='.$it_id;
                    ?>
                    <a href="javascript:popup_item_recommend('<?=$it['it_id']?>');" id="sit_btn_rec"><i class="fa fa-envelope-o" aria-hidden="true"></i><span class="sound_only">추천하기</span></a>
                </div>
            <? } ?>
        </div>

        <script>
        $(".btn_sns_share").click(function(){
            $(".sns_area").show();
        });
        $(document).mouseup(function (e){
            var container = $(".sns_area");
            if( container.has(e.target).length === 0)
            container.hide();
        });
        </script>

         <section id="sit_buy">
        <h2> 구매기능</h2>
        <button type="button" class="btn_close">닫기</button>
        <div class="buy_wr">
            <?php
            if($option_item) {
            ?>
            <section class="sit_option_wr">
                <h3>선택옵션</h3>
         
                <?php // 선택옵션
                echo $option_item;
                ?>
         
            </section>
            <?php
            }
            ?>

            <?php
            if($supply_item) {
            ?>
            <section class="sit_option_wr">
                <h3>추가옵션</h3>
       
                <?php // 추가옵션
                echo $supply_item;
                ?>
            </section>
            <?php
            }
            ?>

            <? if ($it['it_use'] && !$it['it_tel_inq'] && !$is_soldout) { ?>
            <div id="sit_sel_option">
            <?php
            if(!$option_item) {
                if(!$it['it_buy_min_qty'])
                    $it['it_buy_min_qty'] = 1;
            ?>
                <ul id="sit_opt_added">
                    <li class="sit_opt_list">
                        <input type="hidden" name="io_type[<?=$it_id?>][]" value="0">
                        <input type="hidden" name="io_id[<?=$it_id?>][]" value="">
                        <input type="hidden" name="io_value[<?=$it_id?>][]" value="<?=$it['it_name']?>">
                        <input type="hidden" class="io_price" value="0">
                        <input type="hidden" class="io_stock" value="<?=$it['it_stock_qty']?>">
                        <div class="opt_name">
                            <span class="sit_opt_subj"><?=$it['it_name']?></span>
                        </div>
                        <div class="opt_count">
                            <label for="ct_qty_<?=$i?>" class="sound_only">수량</label>
                           <button type="button" class="sit_qty_minus"><i class="fa fa-minus" aria-hidden="true"></i><span class="sound_only">감소</span></button>
                            <input type="text" name="ct_qty[<?=$it_id?>][]" value="<?=$it['it_buy_min_qty']?>" id="ct_qty_<?=$i?>" class="num_input" size="5">
                            <button type="button" class="sit_qty_plus"><i class="fa fa-plus" aria-hidden="true"></i><span class="sound_only">증가</span></button>
                            <span class="sit_opt_prc">+0원</span>
                        </div>
                    </li>
                </ul>
                <script>
                $(function() {
                    price_calculate();
                });
                </script>
            <? } ?>
            </div>

            <div id="sit_tot_price"></div>
            <? } ?>

            <? if($is_soldout) { ?>
            <p id="sit_ov_soldout">상품의 재고가 부족하여 구매할 수 없습니다.</p>
            <? } ?>

            <div id="sit_ov_btn">
                <? if ($is_orderable) { ?>
                <button type="submit" onclick="document.pressed=this.value;" value="장바구니" id="sit_btn_cart" class="btn_b03">장바구니</button>
                <button type="submit" onclick="document.pressed=this.value;" value="바로구매" id="sit_btn_buy" class="btn_b02">바로구매</button>
                <? } ?>
                <? if(!$is_orderable && $it['it_soldout'] && $it['it_stock_sms']) { ?>
                <a href="javascript:popup_stocksms('<?=$it['it_id']?>');" id="sit_btn_buy" class="btn_b02">재입고알림</a>
                <? } ?>

                <? if ($naverpay_button_js) { ?>
                <div class="naverpay-item"><?=$naverpay_request_js.$naverpay_button_js?></div>
                <? } ?>
            </div>
        </div>
    </section>

    </section>

    <div id="sit_buy_op">
        <!-- @todo: [승대] 인플루언서 센터 PPT 12P, 하단 구매버튼 코딩작업 -->
        <div style="position: absolute; bottom: 4em; left: 0em; width:100%;">
            <? if(data::getLoginMember()['mb_is_influencer'] == 'Y') { ?>
                <button type="button" class="btn_b02" style="width:24%;height:30px;">샘플신청</button>
                <button type="button" class="btn_b02" style="width:24%;height:30px;">찜하기</button>
                <button type="button" class="btn_b02" style="width:24%;height:30px;">마이샵</button>
                <button type="button" class="btn_b02" style="width:24%;height:30px;">판매링크</button>
            <? } else { ?>
                <button type="button" id="buy_op_btn" class="btn_b02">
                    구매하기
                </button>
            <? } ?>
        </div>
    </div>
   
    <script>
        $(document).ready(function(){
            $("#buy_op_btn").click(function(){
                $("#sit_buy").slideToggle();
            });
            $("#sit_buy .btn_close").click(function(){
                $("#sit_buy").slideToggle();
            });
        });
    </script>
</div>

<div id="sit_tab">
    <ul class="tab_tit">
        <li><button type="button" rel="#sit_gui" class="selected">판매가이드</button></li>
        <li><button type="button" rel="#sit_inf" class="">상품정보</button></li>
        <li><button type="button" rel="#sit_dvex" class="">배송정보</button></li>
    </ul>
    <ul class="tab_con">

        <!-- 판매가이드 시작 -->
        <li id="sit_gui">
            <h2 class="contents_tit"><span>판매 가이드</span></h2>

            <div id="sit_inf_sell_guide">
                <?=conv_content($it['it_sell_guide'], 1)?>
            </div>
        </li>

        <!-- 상품 정보 시작  -->
        <li id="sit_inf">
            <h2 class="contents_tit"><span>상품 정보</span></h2>

            <? if ($it['it_explan']) { // 상품 상세설명 ?>
                <h3>상품 상세설명</h3>

                <div id="sit_inf_explan">
                    <?=conv_content($it['it_explan'], 1)?>
                </div>
            <? } ?>
        </li>
    </ul>
    <ul>
        <li style="margin: 0 auto;max-width: 1200px;padding: 20px;text-align: left;">
            <? if ($default['de_baesong_content']) { // 배송정보 내용이 있다면 ?>
                <!-- 배송정보 시작 { -->
                <div>
                    <h3>배송정보</h3>
                    <?=conv_content($default['de_baesong_content'], 1)?>
                </div>
                <!-- } 배송정보 끝 -->
            <? } ?>
        </li>
    </ul>

</div>

<script>
$(function (){
    $(".tab_con>li").hide();
    $(".tab_con>li:first").show();   
    $(".tab_tit li button").click(function(){
        $(".tab_tit li button").removeClass("selected");
        $(this).addClass("selected");
        $(".tab_con>li").hide();
        $($(this).attr("rel")).show();
    });
});
</script>
</form>


<script>
$(window).bind("pageshow", function(event) {
    if (event.originalEvent.persisted) {
        document.location.reload();
    }
});

$(function(){
  
    // 상품이미지 크게보기
    $(".popup_item_image").click(function() {
        var url = $(this).attr("href");
        var top = 10;
        var left = 10;
        var opt = 'scrollbars=yes,top='+top+',left='+left;
        popup_window(url, "largeimage", opt);

        return false;
    });
});


// 상품보관
function item_wish(f, it_id)
{
    f.url.value = "<?=G5_SHOP_URL?>/wishupdate.php?it_id="+it_id;
    f.action = "<?=G5_SHOP_URL?>/wishupdate.php";
    f.submit();
}

// 추천메일
function popup_item_recommend(it_id)
{
    if (!g5_is_member)
    {
        if (confirm("회원만 추천하실 수 있습니다."))
            document.location.href = "<?=G5_BBS_URL?>/login.php?url=<?=urlencode(G5_SHOP_URL."/item.php?it_id=$it_id")?>";
    }
    else
    {
        url = "<?=G5_SHOP_URL?>/itemrecommend.php?it_id=" + it_id;
        opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
        popup_window(url, "itemrecommend", opt);
    }
}

// 재입고SMS 알림
function popup_stocksms(it_id)
{
    url = "<?=G5_SHOP_URL?>/itemstocksms.php?it_id=" + it_id;
    opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
    popup_window(url, "itemstocksms", opt);
}

function fsubmit_check(f)
{
    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

    if($(".sit_opt_list").size() < 1) {
        alert("상품의 선택옵션을 선택해 주십시오.");
        return false;
    }

    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?=$it['it_buy_min_qty']?>);
    var max_qty = parseInt(<?=$it['it_buy_max_qty']?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}

// 바로구매, 장바구니 폼 전송
function fitem_submit(f)
{
    f.action = "<?=$action_url?>";
    f.target = "";

    if (document.pressed == "장바구니") {
        f.sw_direct.value = 0;
    } else { // 바로구매
        f.sw_direct.value = 1;
    }

    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

    if($(".sit_opt_list").size() < 1) {
        alert("상품의 선택옵션을 선택해 주십시오.");
        return false;
    }

    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?=$it['it_buy_min_qty']?>);
    var max_qty = parseInt(<?=$it['it_buy_max_qty']?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}

$("#container").removeClass("container").addClass("view-container");

</script>
<?php /* 2017 리뉴얼한 테마 적용 스크립트입니다. 기존 스크립트를 오버라이드 합니다. */ ?>
<script src="<?=G5_JS_URL?>/shop.override.js"></script>