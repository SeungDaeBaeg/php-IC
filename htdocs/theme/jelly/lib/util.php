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
     * @return string|array|null
     */
    public static function param($param, $callbackOrMsg = '') {

        if(is_array($param)) {
            $r = array();
            foreach($param as $k => $p) {
                $r[$p] = $_REQUEST[$p] ?? '';
            }
            return $r;
        } else if(is_string($param)) {
            if(empty($_REQUEST[$param]) && gettype($callbackOrMsg) === 'string' && trim($callbackOrMsg) !== '') {
                util::alert($callbackOrMsg,array ("type"=>"instant"),true);
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
                self::alert('로그인을 진행해주세요.');
                exit();
            }
        }
    }

    /**
     * 알럿 창
     * @param string $msg
     * @param callable $js
     * @param bool   $exit
     */
    public static function alert(string $msg, callable $js = null): void {
        if(is_callable($js)) {
            die("
            <script>
                window.parent.util.alert('".$msg."', {
                  cb: function() { " . $js() . " }
                });
            </script>");
        } else {
            echo "
            <script>
                window.parent.util.alert('".$msg."');
            </script>";
        }
    }

    /**
     * 페이지 이동
     * @param string $link
     * @return string|null
     */
    public static function location(string $link = '/'): ?string {
        $tag = "window.parent.location.href = '".$link."';";
        if(debug_backtrace()[1]['function'] !== '{closure}') {
            echo "<script>".$tag."</script>";
        }
        return $tag;
    }

    /**
     * 현재 페이지 새로고침
     * @return string|null
     */
    public static function reload(): ?string {
        $tag = "window.parent.location.reload();";
        if(debug_backtrace()[1]['function'] !== '{closure}') {
            echo "<script>".$tag."</script>";
        }
        return $tag;
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

    /**
     * 파일 업로드
     *
     * @param string $path
     * @param string $fromFile
     * @param string $uploadFileName
     * @param array  $allowedExt
     *
     * @return array
     */
    public static function upload(string $path, string $fromFile, string $uploadFileName, array $allowedExt = array('jpg','jpeg','png','gif')): array {

        try {
            @mkdir($path, G5_DIR_PERMISSION);
            @chmod($path, G5_DIR_PERMISSION);

            // 변수 정리
            $error = $_FILES[$fromFile]['error'];
            $name = $_FILES[$fromFile]['name'];
            $ext = array_pop(explode('.', $name));

            // 오류 확인
            if( $error != UPLOAD_ERR_OK ) {
                switch( $error ) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new Exception("파일이 너무 큽니다.", $error);
                    case UPLOAD_ERR_NO_FILE:
                        throw new Exception("파일이 첨부되지 않았습니다.", $error);
                    default:
                        throw new Exception("파일이 제대로 업로드되지 않았습니다.", $error);
                }
            }

            // 확장자 확인
            if( !in_array($ext, $allowedExt) ) {
                throw new Exception("허용되지 않는 확장자입니다.", -1);
            }

            // 파일 이동
            if(!move_uploaded_file( $_FILES[$fromFile]['tmp_name'], $path . "/" . $uploadFileName . "." . $ext)) {
                throw new Exception("파일 업로드에 실패하였습니다.", -2);
            }

            throw new Exception("파일 업로드 성공하였습니다.", 0);
        } catch(Exception $e) {
            $r = array(
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage(),
            );

            if($e->getCode() === 0) {
                $r['file_name'] = $uploadFileName . "." . $ext;
            }

            return $r;
        }
    }
}