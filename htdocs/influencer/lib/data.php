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
     * @param null $mb_id 회원 아이디, 생략하면 현재 로그인한 회원 정보를 가지고 온다.
     * @return array|null 회원 정보 연관 배열
     */
    public static function getLoginMember($mb_id = null): ?array {
        static $r = null;

        if(is_null($mb_id)) {
            $mb_id = data::getLoginInfo()['login_id'];
        }

        if(empty($mb_id)) return null;

        if(empty($r)) {
            sql_fetch_arrays("
            SELECT  *
            FROM    g5_member
            WHERE   mb_id = ?", $r, array($mb_id));
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
    public static function isInfluencer($mb_id = null): ?bool {
        if(is_null($mb_id)) $mb_id = data::getLoginInfo()['login_id'];
        if(empty($mb_id)) return null;
        return data::getLoginMember($mb_id)['mb_is_influencer'] === 'Y';
    }

    /**
     * 판매가능한 유효한 상품 정보를 리턴
     * @param string $where
     * @return array
     */
    public static function getAvailbleItems(string $where = ''): array {
        $items = array();
        sql_fetch_arrays("
        SELECT  *
        FROM    g5_shop_item
        WHERE   it_soldout = 0
        AND     it_stock_qty > 0
        ".$where, $items);

        return $items;
    }

    /**
     * 이벤트 참여 테이블로 인서트
     */
    public static function setJoinEvent($ev_id = '',$su_id = '') {
        $mb_no = data::getLoginMember()['mb_no'];
        
        $id = sql_insert("g5_shop_party_join",array(
            'ev_id'         =>  $ev_id,
            'mb_no'         =>  $mb_no,
            'su_id'         =>  $su_id
        ));

        return $id;

        /* if($id > 0) {
            util::ajaxResult('success',0,array("ev_id"=>$ev_id));
        }
        else {
            util::ajaxResult('관리자한테 문의해주세요.',-2);
        } */
    }
}