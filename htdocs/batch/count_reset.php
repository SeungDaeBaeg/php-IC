<?php
/**
 * 마이샵 방문자 카운트 초기화 처리
 *
 * 00시에 실행
 */

include_once('./_common.php');

sql_fetch_data("
UPDATE  g5_influencer_myshop
SET     in_total_count = in_total_count + in_today_count, in_today_count = 0");