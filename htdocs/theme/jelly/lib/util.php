<?php
class util {
    /**
     * 퍼센트 값 구하기
     * @param int $total 전체숫자
     * @param int $used 부분숫자
     * @param int $decimal 소수점 자리
     *
     * @return float
     */
    public static function percent(int $total, int $used, int $decimal = 0): float {
        if($total <= 0 || $used <= 0) return 0;
        return 100 - round($used / $total * 100, $decimal);
    }
}