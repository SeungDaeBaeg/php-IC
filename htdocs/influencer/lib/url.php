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

    /**
     * 상세 상품 페이지 경로를 리턴
     * @param int $itId
     * @return string
     */
    public static function getDetailUrl(int $itId): string {
        return G5_SHOP_URL . '/item.php?it_id=' . $itId;
    }

    /**
     * 배너 주소 경로를 리턴
     * @param int $baId
     * @return string
     */
    public static function getBannerUrl(int $baId): string {
        return G5_DATA_URL . '/banner/' . $baId;
    }

    /**
     * 게시판 페이지 경로를 리턴
     * @param string $tableId
     * @return string
     */
    public static function getBoardUrl(string $tableId): string {
        return G5_URL . '/bbs/board.php?bo_table=' . $tableId;
    }

    /**
     * SNS 채널의 뱃지이미지경로를 리턴
     * @param string $mbSnsChannel
     * @return string
     */
    public static function getSnsChannelImage(string $mbSnsChannel): string {
        return G5_IMG_URL . '/badge_' . strtolower($mbSnsChannel) . '.png';
    }

    /**
     * 마이샵 커버 이미지
     * @param string $fileName
     * @return string|null
     */
    public static function getMyshopCoverImage(string $fileName): ?string {
        if(!file_exists(G5_DATA_PATH . '/myshop/coverImage/' . $fileName)) return null;
        return G5_DATA_URL . '/myshop/coverImage/' . $fileName;
    }

    /**
     * 마이샵 링크 주소
     * @param int $mbNo
     * @return string
     */
    public static function getMyshopLink(int $mbNo): string {
        return G5_URL . '/myshop?id=' . $mbNo;
    }

}