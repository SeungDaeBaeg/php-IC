<?php
/**
 * 상단 메뉴
 */

echo 'test';

print_r($_SESSION);
?>


<? if(!$is_not_head) { ?>
    <header id="hd">
        <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

        <div id="hd_wr">
            <div id="logo">
                <div>
                    <div class="visible-mobile" style="position:absolute;left:0px;top:0px;height:25px;">
                        <? if(!empty($loginInfo)) { ?>
                            <i class="fa fa-money">
                                <a href="/influencer/withdraw">
                                    <span style="margin-left:0.2rem;"><?=number_format($loginInfo['mb_commission'])?>원</span>
                                </a>
                            </i>
                            <span class="sound_only">분류열기</span>
                        <? } else { ?>
                            <a href="/bbs/login.php?url=<?=urlencode($_SERVER['REQUEST_URI'])?>">로그인</a>
                        <? } ?>
                    </div>

                    <a href="<?=G5_URL; ?>/">
                        <img src="<?=G5_DATA_URL; ?>/common/logo.jpg" alt="<?=$config['cf_title']; ?> 메인">
                    </a>

                    <? if(!empty($loginInfo)) { ?>
                        <div id="top_left_alram" class="visible-mobile" style="position:absolute;right:0px;top:0px;height:25px;line-height:25px;">
                            <a href="/influencer/alarm/list.php"><i class="fa fa-bell"></i><span class="sound_only">알람</span>(<?=number_format($alarmCnt)?>)</a>
                        </div>
                    <? } ?>
                </div>
            </div>

            <!-- @todo: [승대] 상품 모바일용 검색창 -->
            <div class="visible-mobile">
                <input type="text" id="txt_search_mobile" style="width:79%;" value="<?=$searchTxt?>"/>
                <button style="width:20%" id="btn_search_mobile">검색</button>

                <!-- 좌측 카테고리 -->
                <?=util::component('common/leftSideBar', array('categorys' => $categorys))?>
            </div>

            <div class="visible-pc position-relative">
                <!-- PC 상단 -->

                <? if(!empty($loginInfo)) { ?>

                    <div id="top_left_menu">
                        <i class="fa fa-money">
                            <a href="/influencer/withdraw">
                                <span style="margin-left:0.2rem;">
                                    수익금 <?=number_format($loginInfo['mb_commission'])?>원
                                </span>
                            </a>
                        </i>

                        | <a>이용가이드</a>
                    </div>

                    <div id="top_right_menu">
                        <span><?=$loginInfo['mb_name']?>님</span>
                        | <a href="<?=G5_BBS_URL?>/logout.php">로그아웃</a>
                        | <a href="/influencer/search?wish=Y">나의 찜</a>
                        | <a href="/influencer/alarm/list.php">알림</a>
                        | <a href="/influencer/tail/info.php">마이페이지</a>
                        | <a>문의센터</a>
                    </div>
                <? } else { ?>
                    <a href="/bbs/login.php?url=<?=urlencode($_SERVER['REQUEST_URI'])?>">로그인</a> |
                    <a>문의센터</a>
                <? } ?>

                <hr/>

                <div class="btn_login">
                    <input type="text" id="txt_search" placeholder="상품을 검색하세요" value="<?=$searchTxt?>"/>
                    <button id="btn_search">검색</button>
                </div>

                <div class="menu_wr">
                    <ul class="cate">
                        <li>
                            <a href="/influencer/search?recommend=Y">추천</a>
                        </li>
                        <li>
                            <a href="/influencer/event/list.php">이벤트</a>
                        </li>
                        <li>
                            <a href="/myshop">마이샵</a>
                        </li>
                        <li>
                            <a href="/influencer/report.php">리포트</a>
                        </li>
                        <li>
                            <a href="/influencer/withdraw">출금관리</a>
                        </li>
                    </ul>

                </div>
            </div>

            <?=outlogin('theme/shop_basic', !empty($memberInfo)) // 외부 로그인 ?>

            <div id="hd_sch">
                <button type="button" class="btn_close"><i class="fa fa-times"></i></button>
                <div class="hd_sch_wr">
                    <form name="frmsearch1" action="<?=G5_SHOP_URL?>/search.php" onsubmit="return search_submit(this);">

                        <div class="sch_inner">
                            <h2>상품 검색</h2>
                            <label for="sch_str" class="sound_only">상품명<strong class="sound_only"> 필수</strong></label>
                            <input type="text" name="q" value="<?=stripslashes(get_text(get_search_string($q))); ?>" id="sch_str" required placeholder="검색어를 입력해주세요">
                            <button type="submit"  class="sch_submit"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only"> 검색</span></button>
                        </div>
                    </form>
                    <?php
                    $save_file = G5_DATA_PATH.'/cache/theme/jelly/keyword.php';
                    if(is_file($save_file)) include($save_file);
                    ?>
                    <?php if(!empty($keyword)) { ?>
                        <div id="ppl_word">
                            <h3>인기검색어</h3>
                            <ol class="slides">
                                <?php
                                $seq = 1;
                                foreach($keyword as $word) {
                                    ?>
                                    <li><a href="<?=G5_SHOP_URL; ?>/search.php?q=<?=urlencode($word); ?>"><?=get_text($word); ?></a></li>
                                    <?php
                                    $seq++;
                                }
                                ?>
                            </ol>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        </div>

        <?php if (false) { ?>
            <div class="hd_admin"><a href="<?=G5_ADMIN_URL; ?>" target="_blank">관리자</a> <a href="<?=G5_THEME_ADM_URL ?>" target="_blank">테마관리</a></div>
        <?php } ?>
    </header>
<? } ?>

<div id="container" class="container">