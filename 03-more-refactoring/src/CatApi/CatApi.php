<?php

namespace CatApi;

use Core\Tools;
use MongoDB\Driver\Exception\Exception;

class CatApi
{
    protected $created_X_seconds_ago = 3;
    protected $xml_random_cat_url = 'http://thecatapi.com/api/images/get?format=xml&type=jpg';

    public function getRandomImage()
    {
        if (!file_exists(__DIR__ . Tools::$image_dir) || time() - filemtime(__DIR__ . Tools::$image_dir) > $this->created_X_seconds_ago) {

            $responseElement = $this->getCatXML();

            file_put_contents(
                __DIR__ . Tools::$image_dir,
                (string)$this->extractImageUrlFromXml($responseElement)
            );

            return (string)$this->extractImageUrlFromXml($responseElement);
        } else {
            return file_get_contents(__DIR__ . Tools::$image_dir);
        }
    }

    public function getCatXML($id='')
    {

        if($id != '') {
            $responseXml = @file_get_contents($this->xml_random_cat_url."&image_id=".$id);
        } else {
            $responseXml = @file_get_contents($this->xml_random_cat_url);
        }

        try {
            $responseElement = new \SimpleXMLElement($responseXml);
        } catch (Exception $exception) {
            return 'http://cdn.my-cool-website.com/default.jpg';
        }

        return $responseElement;
    }

    public function getCatGifUrl($id)
    {
        return $this->extractImageUrlFromXml($this->getCatXML($id));
    }

    public function getRandomCatGifUrl()
    {
        return $this->extractImageUrlFromXml($this->getCatXML());
    }

    public function extractImageUrlFromXml($xml)
    {
        return $xml->data->images[0]->image->url;
    }
}
