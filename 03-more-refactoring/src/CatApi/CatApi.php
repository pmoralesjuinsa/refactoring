<?php

namespace CatApi;

class CatApi
{
    protected $image_dir = "/../../cache/random";

    public function getRandomImage()
    {
        if (!file_exists(__DIR__ . $this->image_dir) || time() - filemtime(__DIR__ . $this->image_dir) > 3) {

            $responseXml = $this->checkFileExists();

            $responseElement = new \SimpleXMLElement($responseXml);

            file_put_contents(
                __DIR__ . $this->image_dir,
                (string)$responseElement->data->images[0]->image->url
            );

            return (string)$responseElement->data->images[0]->image->url;
        } else {
            return file_get_contents(__DIR__ . $this->image_dir);
        }
    }

    function checkFileExists()
    {
        $responseXml = @file_get_contents('http://thecatapi.com/api/images/get?format=xml&type=jpg');

        if (!$responseXml) {
            // the cat API is down or something
            return 'http://cdn.my-cool-website.com/default.jpg';
        }

        return $responseXml;
    }
}
