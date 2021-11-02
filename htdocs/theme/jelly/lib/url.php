<?php

class url {

    /**
     * 이미지 절대경로를 리턴한다.
     * @param $filename
     * @return string
     */
    public static function getThumbnailUrl(string $filename): string {
        return G5_DATA_URL . '/item/' . $filename;
    }

    public static function getDetailUrl(int $itId): string {
        return G5_SHOP_URL . '/item.php?it_id=' . $itId;
    }

    public static function getBoardUrl(string $tableId): string {
        return G5_URL . '/bbs/board.php?bo_table=' . $tableId;
    }

    public static function getBannerUrl(string $filename): string {
        return G5_DATA_URL . '/banner/' . $filename;
    }
}