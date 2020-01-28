<?php

namespace CatApi;

use Core\Tools;

class CatApi
{
    protected $created_X_seconds_ago = 3;
    protected $xml_random_cat_url = 'http://thecatapi.com/api/images/get?format=xml&type=jpg';
    protected $cat_url;

    function __construct()
    {
        $this->cat_url = "";
    }

    public function getRandomImage()
    {
        if (!file_exists(__DIR__ . Tools::$image_dir) || time() - filemtime(__DIR__ . Tools::$image_dir) > $this->created_X_seconds_ago) {

            $responseXml = $this->checkFileExists();

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

    function checkFileExists()
    {
        $responseXml = @file_get_contents($this->xml_random_cat_url);

        if (!$responseXml) {
            // the cat API is down or something
            return 'http://cdn.my-cool-website.com/default.jpg';
        }

        return $responseXml;
    }

    function getCatGifUrl($id)
    {

        return $this->cat_url."&image_id=".$id;
    }

    function getRandomCatGifUrl()
    {
        return $this->cat_url;
    }
}
