<?php
header('Content-Type: application/json');

class Log {
    public static function debug($str) {
        print "DEBUG: " . $str . "\n";
    }
    public static function info($str) {
        print "INFO: " . $str . "\n";
    }
    public static function error($str) {
        print "ERROR: " . $str . "\n";
    }
}

function Curl($url, $post_data, &$http_status, &$header = null) {
    //Log::debug("Curl $url JsonData=" . $post_data);

    $ch=curl_init();
    // user credencial
    curl_setopt($ch, CURLOPT_USERPWD, "username:passwd");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);

    // post_data
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    if (!is_null($header)) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    //Log::debug('Curl exec=' . $url);
      
    $body = null;
    // error
    if (!$response) {
        $body = curl_error($ch);
        // HostNotFound, No route to Host, etc  Network related error
        $http_status = -1;
        //Log::error("CURL Error: = " . $body);
    } else {
       //parsing http status code
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (!is_null($header)) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
        } else {
            $body = $response;
        }
    }

    curl_close($ch);

    return $body;
}

$url = "https://api.tason.com/tas-api/send";

$groupData = array();
$groupData["tas_id"] = "kimgolf3231@nate.com";//타스 id
$groupData["send_type"] = "EM";//이메일
$groupData["auth_key"] = "D9KQEU-O3ZNSQ-UP22LG-H2MQAC_146";//연동api key

$member1 = array("user_name" => "최정규", "user_email" => "web@mwd.kr", "map_content" => "안녕하세요, 테스트입니다.","sender" => "k.dealhelp@gmail.com","sender_name" => "운영자","subject" => "제목_테스트입니다.");
  
$data = array($member1);
$groupData["data"] = $data;
		
$output =  json_encode($groupData); 

$ret = Curl($url, $output, $http_status);

var_dump($ret);



?>