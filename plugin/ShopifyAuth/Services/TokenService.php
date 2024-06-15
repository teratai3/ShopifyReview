<?php

namespace Plugin\ShopifyAuth\Services;

use CodeIgniter\I18n\Time;

class TokenService
{
    protected $clientId;
    protected $clientSecret;

    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function verifySessionToken($token)
    {
        /*
        iss: adminのURL
        dest: ショップのドメイン
        aud: アプリのAPIキー（Shopifyから発行され、アプリの環境変数に保持しておく）
        sub: ユーザーID（利用ショップ）
        exp: 有効期限
        nbf: 有効化の日時
        iat: 発行日時
        jti: ランダムな値
        sid: アプリ・ユーザーでユニークなセッションID
        */

        // トークンをヘッダー、ペイロード、署名に分割
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return ['status' => false, 'message' => '無効なトークン構造'];
        }

        list($header, $payload, $signature) = $parts;

        // ベース64デコード
        $payloadJson = base64_decode($payload);
        $payloadData = json_decode($payloadJson, true);

        if (!$payloadData) {
            return ['status' => false, 'message' => '無効なペイロード データ'];
        }

        // expの検証
        if (!isset($payloadData['exp']) || Time::now()->isAfter(Time::createFromTimestamp($payloadData['exp']))) {
            return ['status' => false, 'message' => 'トークンの有効期限が切れています'];
        }



        // nbfの検証
        if (!isset($payloadData['nbf']) || Time::now()->isBefore(Time::createFromTimestamp($payloadData['nbf'] - 30))) {
            // 誤差が発生したので、◯秒の猶予を設ける
            return ['status' => false, 'message' => 'トークンはまだ有効ではありません'];
        }

        // issとdestの検証
        if (!isset($payloadData['iss']) || !isset($payloadData['dest']) || parse_url($payloadData['iss'], PHP_URL_HOST) !== parse_url($payloadData['dest'], PHP_URL_HOST)) {
            return ['status' => false, 'message' => '無効な発行者または宛先です'];
        }

        // audの検証
        if (!isset($payloadData['aud']) || $payloadData['aud'] !== $this->clientId) {
            return ['status' => false, 'message' => '無効な対象者'];
        }

        // subの検証
        if (!isset($payloadData['sub'])) {
            return ['status' => false, 'message' => '無効な値'];
        }


        // "Bearer " プレフィックスが存在する場合は削除
        if (strpos($header, 'Bearer ') === 0) {
            $header_token = substr($header, 7);
            // log_message('error', "header Bearer token:" . $token);
            // log_message('error', "signature:" . $signature);
        } else {
            return ['status' => false, 'message' => 'headerが無効です。'];
        }


        //認証参考
        //https://qiita.com/raharu0425/items/bd1aab00eea516b376b6
        $headerPayload = $header_token . '.' . $payload;

        $expectedSignature = hash_hmac('sha256', $headerPayload, $this->clientSecret, true);
        $expectedSignatureBase64 = str_replace(['+', '/', '='], ['-', '_', ''], rtrim(base64_encode($expectedSignature), '='));

        if (!hash_equals($expectedSignatureBase64, $signature)) {
            return ['status' => false, 'message' => '無効な署名'];
        }


        return ['status' => true, 'payload' => $payloadData];
    }
}
