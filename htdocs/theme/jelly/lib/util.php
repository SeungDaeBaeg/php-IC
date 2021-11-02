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

    /**
     * 리퀘스트 파라미터 체크
     *
     * @param mixed $param
     * @param mixed $callbackOrMsg
     *
     * @return string|null
     */
    public static function paramCheck($param, $callbackOrMsg = '') {

        if(is_array($param)) {
            $r = array();
            foreach($param as $k => $p) {
                $r[$p] = $_REQUEST[$p] ?? '';
            }
            return $r;
        } else if(is_string($param)) {
            if(empty($_REQUEST[$param]) && gettype($callbackOrMsg) === 'string' && trim($callbackOrMsg) !== '') {
                util::alert($callbackOrMsg, true);
            } else if(empty($_REQUEST[$param]) && gettype($callbackOrMsg) === 'object') {
                $callbackOrMsg();
                exit();
            }

            return $_REQUEST[$param] ?? null;
        }
    }

    /**
     * 로그인 체크 함수
     *
     * @param bool $return
     * @return bool
     */
    public static function loginCheck(bool $return = false) {
        $isLogin = data::isLogin();

        if($return) {
            return $isLogin;
        } else {
            if(!$isLogin) {
                echo "<script>alert('로그인을 진행해주세요.');</script>";
                exit();
            }
        }
    }

    /**
     * 알럿 창
     *
     * @param string $msg
     * @param bool   $exit
     */
    public static function alert(string $msg, bool $exit = false): void {
        if($msg !== '') {
            echo "<script>";
            echo "alert('".$msg."');";
            echo "</script>";
        }

        if($exit) {
            exit();
        }
    }

    /**
     * 로케이션
     * @param string $link
     */
    public static function location(string $link = '/'): void {
        echo "<script>";
        echo "window.parent.location.href = '".$link."';";
        echo "</script>";
        exit();
    }


    /**
     * 컴포넌트 로드
     *
     * @param string $path
     * @param array  $param
     *
     * @return false|string
     */
    public static function component(string $path, $param = array()) {

        if(count($param) > 0) {
            foreach($param as $k => $v) $$k = $v;
        }

        ob_start();

        static $isJsImport, $isCssImport;

        if(empty($isJsImport) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/js/component/' . $path . '.js')) {
            add_javascript('<script src="'.G5_JS_URL.'/component/'.$path.'.js"></script>', 10);
            $isJsImport = true;
        }

        if(empty($isCssImport) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/css/component/' . $path . '.css')) {
            add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/component/'.$path.'.css">', 0);
            $isCssImport = true;
        }

        include $_SERVER['DOCUMENT_ROOT'] . '/component/' . $path . '.php';
        return ob_get_clean();
    }

    /**
     * AJAX 결과 리턴 데이터
     *
     * @param string $msg
     * @param int    $code
     * @param array  $extra
     *
     * @return string
     */
    public static function ajaxResult(string $msg = 'success', int $code = 0, array $extra = array()): string {
        $returnData = array(
            'msg'   => $msg,
            'code'  => $code
        );

        if(count($extra) > 0) {
            $returnData = array_merge($returnData, $extra);
        }

        die(json_encode($returnData));
    }
}