<?php

class data {

    /**
     * 로그인 정보
     * @return array|null
     */
    public static function getLoginInfo(): ?array {
        if(empty($_SESSION['ss_mb_id'])) {
            return null;
        }

        return array(
            'id'        => $_SESSION['ss_tv_idx'],          // 로그인한 회원 PK
            'login_id'  => $_SESSION['ss_mb_id'],           // 로그인한 회원 아이디
            'is_mobile' => ($_SESSION['ss_is_mobile'] == 1) // 로그인한 사용자의 모바일 유무
        );
    }

    /**
     * 로그인한 회원 정보
     * @return array
     */
    public static function getLoginMember(): ?array {
        static $r = null;

        if(empty(data::getLoginInfo()['login_id'])) return null;

        if(empty($r)) {
            sql_fetch_arrays("
            SELECT  *
            FROM    g5_member
            WHERE   mb_id = ?", $r, array(data::getLoginInfo()['login_id']));
        }

        return $r[0] ?? null;
    }

    /**
     * 로그인 여부
     * @return bool
     */
    public static function isLogin(): bool {
        return !empty(data::getLoginInfo()['login_id']);
    }

    /**
     * 인플루언서 회원 여부
     * @return bool
     */
    public static function isInfluencer(): ?bool {
        if(empty(data::getLoginInfo()['login_id'])) return null;
        return data::getLoginMember()['mb_is_influencer'] === 'Y';
    }
}