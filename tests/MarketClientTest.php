<?php

namespace tests;

use Appsco\Market\Api\MarketClient;
use Appsco\Market\Api\Model\ExistingApplication;
use Appsco\Market\Api\Model\NewApplication;
use Appsco\Market\Api\Model\Order;
use BWC\Component\Jwe\Encoder;
use BWC\Component\Jwe\Jwt;

class MarketClientTest extends \PHPUnit_Framework_TestCase
{
    public function testMakeOrderJwtWithExistingApplication()
    {
        $order = new Order($expectedPackageId = 123, new ExistingApplication($expectedApplicationId = 456));
        $order->setOneTimePrice($expectedOneTimePrice = 100);
        $order->setRecurringPrice($expectedRecurringPrice = 200);
        $order->setTrialMonths($expectedTrial = 3);
        $order->setDescription($expectedDescription = 'description');

        $client = new MarketClient(
            $encoder = new Encoder(),
            'file://'.__DIR__.'/key.pem',
            $expectedIssuer = 'zYua50VjHMoK443jTsKaentBe15U6z4B',
            $expectedTarget = 'https://market.appsco.com/checkout'
        );


        $response = $client->makeOrder($order);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);

        $url = $response->getTargetUrl();

        $this->assertStringStartsWith($expectedTarget, $url);

        $jwt = null;
        $arr = parse_url($url);
        $query = $arr['query'];
        parse_str($query);

        $this->assertNotEmpty($jwt);

        /** @var Jwt $jwt */
        $jwt = $encoder->decode($jwt);

        $this->assertNotEmpty($jwt->getJwtId());
        $this->assertEquals($expectedIssuer, $jwt->getIssuer());
        $this->assertGreaterThanOrEqual(0, time() - $jwt->getIssuedAt());
        $this->assertEmpty($jwt->get('app_url'));
        $this->assertEmpty($jwt->get('app_title'));
        $this->assertEmpty($jwt->get('app_icon'));
        $this->assertEquals($expectedApplicationId, $jwt->get('app_id'));
        $this->assertEquals($expectedOneTimePrice, $jwt->get('ot_price'));
        $this->assertEquals($expectedRecurringPrice, $jwt->get('r_price'));
        $this->assertEquals($expectedTrial, $jwt->get('trial'));
        $this->assertEquals($expectedDescription, $jwt->get('desc'));

        $encoder->verify($jwt, 'file://'.__DIR__.'/key.crt');
    }



    public function testNewApp()
    {
        $order = new Order($expectedPackageId = 123, new NewApplication(
            $expectedUrl = 'http://example.com',
            $expectedTitle = 'example application',
            $expectedIcon = 'http://example.com/icon.png'
        ));

        $payload = $order->getJwtPayload();

        $this->assertArrayNotHasKey('app_id', $payload);
        $this->assertArrayHasKey('app_url', $payload);
        $this->assertArrayHasKey('app_title', $payload);
        $this->assertArrayHasKey('app_icon', $payload);

        $this->assertEquals($expectedPackageId, $payload['package_id']);
        $this->assertEquals($expectedUrl, $payload['app_url']);
        $this->assertEquals($expectedTitle, $payload['app_title']);
        $this->assertEquals($expectedIcon, $payload['app_icon']);
    }

} 