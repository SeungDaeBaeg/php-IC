<?php

class url {

    /**
     * 이미지 절대경로를 리턴한다.
     * @param $filename
     * @return string
     */
    public static function getItemUrl($filename) {
        return G5_DATA_URL . '/item/' . $filename;
    }
}