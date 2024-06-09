<?php

namespace Plugin\ShopifyAuth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class RateLimitFilter implements FilterInterface
{
    // protected $rateLimit = 100; // リクエスト上限数
    protected $rateLimit = 500; // リクエスト上限数
    protected $timeWindow = 60; // 秒単位の時間

    public function before(RequestInterface $request, $arguments = null)
    {
        //https://ci-trans-jp.gitlab.io/user_guide_4_jp/libraries/throttler.html
        $throttler = Services::throttler(); //レートリミット導入

        if ($throttler->check(md5($request->getIPAddress()), $this->rateLimit, $this->timeWindow) === false) {
            $response = Services::response();
            $response->setStatusCode(429);
            $response->setContentType('application/json');
            $response->setBody(json_encode(['error' => "リクエストの制限を超えました"]));
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
