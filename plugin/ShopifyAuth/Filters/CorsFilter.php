<?php

namespace Plugin\ShopifyAuth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CorsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
       // Allow from any origin
       header("Access-Control-Allow-Origin: *");
       header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
       header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

       if ($request->getMethod() === 'options') {
           header("HTTP/1.1 200 OK");
           exit;
       }
       return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
