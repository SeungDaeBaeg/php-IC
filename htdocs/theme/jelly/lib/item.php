<?php

class item {
    /**
     * 특정 아이템의 옵션 리스트 가져오기
     * @param int $it_id
     * @return array|null
     */
    public static function getItemOptions(int $it_id): ?array {
        $r = array();

        //상품 정보
        sql_fetch_data("
        SELECT  it_option_subject
        FROM    g5_shop_item
        WHERE   it_id = ?
        ", $item, array($it_id));

        //상품 옵션 정보
        $it_option_subjects = explode(",", $item['it_option_subject']) ?? array();

        if(!empty($item['it_option_subject'])) {
            $it_option_subjects = explode(",", $item['it_option_subject']) ?? array();

            if(count($it_option_subjects) > 0) {

                $options = array();
                sql_fetch_arrays("
                SELECT  io_id
                FROM    g5_shop_item_option
                WHERE   it_id = ?
                AND     io_use = 1
                ORDER BY io_no ASC
                ", $item_options, array($it_id));
                foreach($item_options as $item_option) {
                    $ioIds = explode('', $item_option['io_id']);

                    foreach($ioIds as $k => $ioId) {
                        $options[$k][] = $ioId;
                    }
                }

                foreach($it_option_subjects as $k => $it_option_subject) {
                    $options[$k] = array_values(array_unique($options[$k]));
                    $r[$it_option_subject] = $options[$k];
                }
            }
        }

        return $r;
    }
}