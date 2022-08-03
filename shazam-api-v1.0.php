<?php

class Dublix
{
    const URL = "https://www.aha-music.com/identify-songs-music-recognition-online/";

public function get_csrf_cookie(){
    try{
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => self::URL,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
        ]);
        $content = curl_exec($curl);
        curl_close($curl);
        preg_match('/_token" value="([^"\']+)/',$content,$csrf);
        preg_match_all('/set-cookie: ([^;]+)/',$content,$cookie);
        return isset($csrf[1]) ? ['cookie' => $csrf[1] , 'cookie_s' => implode('; ',$cookie[1])] : null;
    }
    catch (\Exception $ex){
        return null;
    }
}

public function upload_file(string $path_to_file){
    $csrf_cookie = $this->get_csrf_cookie();
    if (!$csrf_cookie) return 'Something is went wrong !';
    $post = [
        'files[]' => new CURLFile($path_to_file),
        '_token' => $csrf_cookie['cookie']
    ];
    $curl = curl_init();
    curl_setopt_array($curl,[
        CURLOPT_URL => self::URL.'/upload',
        CURLOPT_POST => 1,
        CURLOPT_COOKIE => $csrf_cookie['cookie_s'],
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_RETURNTRANSFER => 1
    ]);
    $res = curl_exec($curl);
    return json_decode($res,1)['files'][0]['acrid'];
}

public function init(string $path_to_file) : array{
    $id = $this->upload_file($path_to_file);
    $content = file_get_contents(self::URL."/upload/$id");
    preg_match('/artist: "([^"]+)/',$content,$artist);
    preg_match('/song: "([^"]+)/',$content,$song);
    return isset($artist[1]) ? ['success' => 1 ,'file_name' => $path_to_file, 'artist' => $artist[1] , 'song' => $song[1],"by"=>"@dublix"] : ['success' => 0 ,'file_name' => $path_to_file, 'message' => 'no result'];
}
}
