<?php
    include_once('./_common.php');

    util::loginCheck();

    $sns = $_GET['sns'] ?? '';
    $done = $_GET['hauth_done'] ?? '';

    $mb_no = data::getLoginMember()['mb_no'];
    $info_url = G5_INFLUENCER_URL.'/tail/info_option.php';
    
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
        case "youtube":
            youtubeApi();
            break;
    }

    switch($done) {
        case 'naver':
            naverApiDone();
            break;
        case 'youtube':
            youtubeApiDone();
            break;
    }

    function youtubeApi() {
        global $client_id;
        global $redirectURI;
        $scope = "https://www.googleapis.com/auth/youtube.readonly";
        $apiURL = "https://accounts.google.com/o/oauth2/auth?client_id=$client_id";
        $apiURL .= "&scope=$scope";
        $apiURL .= "&access_type=offline";
        $apiURL .= "&response_type=code";
        $apiURL .= "&redirect_uri=".rawurlencode($redirectURI);
        $apiURL .= "&prompt=consent";
        util::location($apiURL);
    }

    function youtubeApiDone() {
        global $client_id;
        global $client_secret;
        global $redirectURI;
        global $info_url;
        $code = $_GET["code"];
        $is_post = true;
        $url = "https://oauth2.googleapis.com/token";
        $params = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectURI,
            'access_type' => 'offline'
        );

        $options = array();
        $options['params'] = $params;
        $options['method'] = 'post';

        list($error, $response) = util::curlCall($url,$options);

        if(!$error) {
            $response_arr = json_decode($response,true);

            $params = array(
                'access_token' => $response_arr['access_token'],
                'part' => 'snippet,statistics',
                'mine' => 'true',
            );

            $options = array();
            $options['params'] = $params;

            list($error, $response) = util::curlCall("https://www.googleapis.com/youtube/v3/channels", $options);

            if(!$error) {
                $response_arr = json_decode($response,true);

                $channerInfo = fetchYoutubeChannel($response_arr['items']);

                if ($channerInfo === false) {
                    //유튜브 채널이 없을때
                    util::location($info_url."?code=3");
                    exit;
                }
                else {

                }
            }
        }
        echo "Error 내용:".$response;
    }

    function fetchYoutubeChannel($items) {
        if (!isset($items)) {
            return false;
        }

        foreach ($items as $item) {
            if ($item['kind'] == "youtube#channel") {
                return $item;
            }
        }
        
        return false;
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
        $code = $_GET["code"];
        $state = $_GET["state"];
        $url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;

        list($error, $response) = util::curlCall($url);
        if(!$error) {
            $response_arr = json_decode($response, true);
            $token = $response_arr['access_token'];
            $header = "Bearer " . $token;
            $user_profile_url = "https://openapi.naver.com/v1/nid/me";

            $headers = array();
            $headers[] = "Authorization: " . $header;

            $options = array();
            $options['headers'] = $headers;

            list($error, $response) = util::curlCall($user_profile_url,$options);
            if(!$error) {                
                global $info_url;
                global $mb_no;
                $user_profile_arr = json_decode($response, true);
                $naver_user_email = $user_profile_arr['response']['email'];
                $naver_sns_id = $user_profile_arr['response']['id'];
                $blog_id = explode('@',$naver_user_email)[0];
                $xml = getRssBlog($blog_id);
                $count_item = count($xml->channel->item);
                if($count_item > 0) {
                    
                    $sql = "
                    SELECT count(*) cnt, mb_no 
                    FROM g5_member_sns_link 
                    WHERE del_yn = 'N'
                    AND msl_type = 'naver'
                    AND msl_sns_id = ?";
                    
                    sql_fetch_data($sql,$dup_res,array($naver_sns_id));
                    
                    if($dup_res['cnt'] > 0) {
                        //다른 계정에서 같은 sns를 사용하려고 할 때
                        if($dup_res['mb_no'] != $mb_no) util::location($info_url."?code=2");
                        else {
                            sql_update('g5_member_sns_link',array(
                                "del_yn"    =>  'Y',
                                "updated_at" => date('Y-m-d H:i:s')
                            ),"msl_sns_id = '{$naver_sns_id}'");
                            util::location($info_url."?code=0&sns=naver&type=delete");
                            exit;
                        }
                    }
                
                    $id = sql_insert('g5_member_sns_link',array(
                        "msl_sns_id"        =>  $naver_sns_id,
                        "mb_no"             =>  $mb_no,
                        "msl_email"         =>  $naver_user_email,
                        "msl_type"          =>  "naver",
                        "msl_token"         =>  $token,
                        "msl_url"           =>  $xml->channel->link,
                        "msl_title"         =>  $xml->channel->title
                    ));
                    //insert가 되지 않았을때
                    if($id === 0) util::location($info_url."?code=1");
                }
                util::location($info_url."?code=0&rss_count=$count_item&sns=naver&type=insert");
            }
        }

        echo "Error 내용:".$response;
    }

    function getRssBlog($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://rss.blog.naver.com/$id.xml");
        curl_setopt($ch, CURLOPT_POST, "get");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);
        curl_close($ch);
        
        return simplexml_load_string($res);
    }

    function deleteSns($sns) {

    }
    
    
?>