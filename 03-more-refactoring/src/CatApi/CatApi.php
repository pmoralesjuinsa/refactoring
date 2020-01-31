<?php

namespace CatApi;

class CatApi
{
    CONST IMAGE_DIR = "/../../cache/random";
    CONST CREATED_THREE_SECONDS_AGO = 3;
    CONST XML_RANDOM_CAT_URL = 'http://thecatapi.com/api/images/get?format=xml&type=jpg';

    protected function getRandomImage()
    {
        if (!file_exists(__DIR__ . IMAGE_DIR) ||
            time() - filemtime(__DIR__ . IMAGE_DIR) > CREATED_THREE_SECONDS_AGO) {

            $responseElement = $this->getCatXML();

            file_put_contents(
                __DIR__ . IMAGE_DIR,
                (string)$this->extractImageUrlFromXml($responseElement)
            );

            return (string)$this->extractImageUrlFromXml($responseElement);
        }

        return file_get_contents(__DIR__ . IMAGE_DIR);

    }

    protected function getCatXML($id='')
    {

        if($id != '') {
            $responseXml = @file_get_contents(XML_RANDOM_CAT_URL."&image_id=".$id);
        } else {
            $responseXml = @file_get_contents(XML_RANDOM_CAT_URL);
        }

        return $this->convertToValidXml($responseXml);
    }

    protected function convertToValidXml($responseXml)
    {
        try {
            $responseElement = new \SimpleXMLElement($responseXml);
        } catch (\Exception $exception) {
            $responseElement = new \SimpleXMLElement("<body></body>");
            $responseElement->data->images[0]->image->url = 'http://localhost/default.jpg';
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

    protected function extractImageUrlFromXml($xml)
    {
        return $xml->data->images[0]->image->url;
    }
}
