<?php

namespace CatApi;

use Core\Tools;

class CatApi
{

    public function getRandomImage()
    {
        if (!file_exists(__DIR__ . Tools::$image_dir) || time() - filemtime(__DIR__ . Tools::$image_dir) > 3) {

            $responseXml = $this->check_file_exists();

            $responseElement = new \SimpleXMLElement($responseXml);

            file_put_contents(
                __DIR__ . Tools::$image_dir,
                (string)$responseElement->data->images[0]->image->url
            );

            return (string)$responseElement->data->images[0]->image->url;
        } else {
            return file_get_contents(__DIR__ . Tools::$image_dir);
        }
    }

    function check_file_exists()
    {
        $responseXml = @file_get_contents('http://thecatapi.com/api/images/get?format=xml&type=jpg');

        if (!$responseXml) {
            // the cat API is down or something
            return 'http://cdn.my-cool-website.com/default.jpg';
        }

        return $responseXml;
    }
}
