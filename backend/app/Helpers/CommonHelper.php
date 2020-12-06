<?php

namespace App\Helpers;

class CommonHelper{

    // public static function getRandomNumber($min, $max)
    // {
    //     return rand($min, $max);
    // }

    /**
     * curl関数
     * @param string $url
     * @param array $header
     * @return string
     */
    public static function curl($url, $post = null, $header = null){
        $conn = curl_init();
        if($post != null){
            curl_setopt($conn, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($conn, CURLOPT_POSTFIELDS, $post);
        }
        // サーバ証明書の検証は行わない。
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOS, false);
        // curl_execの実行結果を文字列として取得できるように設定
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        // 問い合わせ先のurlを指定
        curl_setopt($conn, CURLOPT_URL,  $url);
        if($header != null){
            //ヘッダー追加オプション
            //curl_setopt($conn, CURLOPT_HEADER, $header);
            curl_setopt($conn, CURLOPT_HTTPHEADER, $header);
        }
        // 問い合わせを行い、その結果を取得
        $result = curl_exec($conn);
        // コネクションを切断
        curl_close($conn);
        return $result;
    }

}