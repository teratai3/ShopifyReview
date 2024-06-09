<?php

namespace Plugin\ShopifyAuth\Services;

use CodeIgniter\I18n\Time;

class TokenService
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
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
            // ◯秒の猶予を設ける
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

        return ['status' => true, 'payload' => $payloadData];
    }
}
