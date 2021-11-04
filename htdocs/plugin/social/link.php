<?php
    include_once('./_common.php');

    util::loginCheck();

    $sns = $_GET['sns'] ?? '';
    $done = $_GET['hauth_done'] ?? '';

    $mb_no = data::getLoginMember()['mb_no'];
    
    if($sns) {
        $client_id = $config['cf_'.$sns.'_clientid'];
        $redirectURI = 'https://www.inburstshop.com/plugin/social/link.php?hauth_done='.$sns;
    }
    else {
        $client_id = $config['cf_'.$done.'_clientid'];
        $client_secret = $config['cf_'.$done.'_secret'];
        $redirectURI = 'https://www.inburstshop.com/plugin/social/link.php?hauth_done='.$done;
    }
    
    switch($sns) {
        case "naver":
            naverApi();
            break;
    }

    switch($done) {
        case 'naver':
            naverApiDone();
            break;
    }

    function naverApi() {
        global $client_id;
        global $redirectURI;
        $state = "RAMDOM_STATE";
        $apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".$client_id."&redirect_uri=".$redirectURI."&state=".$state;
        util::location($apiURL);
    }

    function naverApiDone() {
        global $client_id;
        global $client_secret;
        global $redirectURI;
        $code = $_GET["code"];;
        $state = $_GET["state"];;
        $url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
        $is_post = false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $response = curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

        if($status_code == 200) {
            $response_arr = json_decode($response, true);
            $token = $response_arr['access_token'];
            $header = "Bearer " . $token;
            $user_profile_url = "https://openapi.naver.com/v1/nid/me";

            $is_post = false;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $user_profile_url);
            curl_setopt($ch, CURLOPT_POST, $is_post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = array();
            $headers[] = "Authorization: " . $header;

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $user_profile = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if($status_code == 200) {
                var_dump($user_profile);
                $user_profile_arr = json_decode($user_profile, true);
                $naver_user_email = $user_profile_arr['response']['email'];
                $naver_sns_id = $user_profile_arr['response']['id'];
                $blog_id = explode('@',$naver_user_email)[0];
                getRssBlog($blog_id);
            }
            else {
                echo "Error 내용:".$response;
            }
        } else {
            echo "Error 내용:".$response;
        }
    }

    function getRssBlog($id) {
        $out = "GET /$id.xml HTTP/1.0\r\nHost: rss.blog.naver.com\r\n\r\n";
        $fp = fsockopen("rss.blog.naver.com", 443, $errno, $errstr, 30);
        var_dump($fp);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://rss.blog.naver.com/testlsb001.xml");
        curl_setopt($ch, CURLOPT_POST, "get");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);
        curl_close($ch);
        var_dump(simplexml_load_string($res));

    }
    
    
?>