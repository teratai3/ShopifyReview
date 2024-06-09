<?php

namespace Plugin\ShopifyFrontend\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class AssetController extends BaseController
{
    public function fetchAsset(string $assetType, ...$relativePath)
    {
        
        $directoryPath = implode("/", array_map(function ($filename) {
            return preg_replace('/[^a-zA-Z0-9\.\-_]/', '', basename($filename));
        }, $relativePath));

        $filepath = ROOTPATH . "plugin/ShopifyFrontend/public/" .  basename($assetType) . "/" . $directoryPath;

       
        if (!file_exists($filepath)) throw PageNotFoundException::forPageNotFound();


        switch ($assetType) {
            case 'css':
                $this->response->setContentType('text/css');
                break;
            case 'js':
            case 'dist':
                $this->response->setContentType('application/javascript');
                break;
            case 'images':
                $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $fileInfo->file($filepath);
                $this->response->setContentType($mimeType);
                break;
            case 'fonts':
                $this->response->setContentType('font/ttf');
                break;
            default:
                throw PageNotFoundException::forPageNotFound();
        }
        return readfile($filepath);
    }
}
