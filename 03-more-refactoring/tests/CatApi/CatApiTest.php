<?php

namespace CatApi\Tests;

use CatApi\CatApi;

class CatApiTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        $catApi = new CatApi();
        @unlink(__DIR__ . $catApi->image_dir);
    }

    /** @test */
    public function it_fetches_a_random_url_of_a_cat_gif()
    {
        $catApi = new CatApi();

        $url = $catApi->getRandomImage();

        $this->assertTrue(filter_var($url, FILTER_VALIDATE_URL) !== false);
    }

    /** @test */
    public function it_caches_a_random_cat_gif_url_for_3_seconds()
    {
        $catApi = new CatApi();

        $firstUrl = $catApi->getRandomImage();
        sleep(2);
        $secondUrl = $catApi->getRandomImage();
        sleep(2);
        $thirdUrl = $catApi->getRandomImage();

        $this->assertSame($firstUrl, $secondUrl);
        $this->assertNotSame($secondUrl, $thirdUrl);
    }

    /** @test */
    public function get_a_valid_url_with_an_id()
    {
        $catApi = new CatApi();

        $url_one = $catApi->getCatGifUrl('vd');

        $this->assertTrue(filter_var($url_one, FILTER_VALIDATE_URL));
    }

    /** @test */
    public function get_a_valid_url_with_an_id_and_compare_if_is_the_correct_one()
    {
        $catApi = new CatApi();

        $url_one = $catApi->getCatGifUrl('vd');
        $url__one_must_be_target = "https://30.media.tumblr.com/tumblr_m1pgmg9Fe61qjahcpo1_500.jpg";

        $this->assertEquals($url__one_must_be_target, $url_one);
    }

    /** @test */
    public function compare_two_urls_with_same_method_with_differents_url_result()
    {
        $catApi = new CatApi();
        $url_one = $catApi->getRandomCatGifUrl();
        $url_two = $catApi->getRandomCatGifUrl();

        $this->assertNotSame($url_one, $url_two);
    }

    /** @test */
    public function check_xml_conversion_of_a_url_result()
    {
        $catApi = new CatApi();
        $response_xml = "
                        <response>
                            <data>
                                <images>
                                    <image>
                                        <id>vd</id>
                                        <url>https://30.media.tumblr.com/tumblr_m1pgmg9Fe61qjahcpo1_500.jpg</url>
                                        <source_url>https://thecatapi.com/?image_id=vd</source_url>
                                    </image>
                                </images>
                            </data>
                        </response>";
        $xml = $catApi->convertToValidXml($response_xml);

        $this->assertEquals('https://30.media.tumblr.com/tumblr_m1pgmg9Fe61qjahcpo1_500.jpg', $xml->data->images[0]->image->url);
    }

    /** @test */
    public function check_exception_at_xml_conversion_of_a_url_result_and_is_a_xml()
    {
        $catApi = new CatApi();
        $xml = $catApi->convertToValidXml("cualquier cosa");

        $this->assertEquals('http://localhost/default.jpg', $xml->data->images[0]->image->url);
    }

}

//TODO crear clase Wrapper que exponga los métodos protected para poder pasar los  tests