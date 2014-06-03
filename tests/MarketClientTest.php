<?php

namespace tests;

use Appsco\Market\Api\MarketClient;
use Appsco\Market\Api\Model\Order;
use BWC\Component\Jwe\Encoder;

class MarketClientTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(function($class) {
            return class_exists($class);
        });
    }

    public function testMakeOrderJwtWithExistingApplication()
    {
        $order = Order::create($expectedPackageId = 123);
        $order
            ->setAppId($expectedApplicationId = 456)
            ->setOneTimePrice($expectedOneTimePrice = 100)
            ->setRecurringPrice($expectedRecurringPrice = 200)
            ->setTrialDuration($expectedTrialDuration = 3)
            ->setTrialDurationUnit($expectedTrialDurationUnit = Order::DURATION_UNIT_MONTH)
            ->setFirstBillingDate($expectedFirstBillingDate = new \DateTime('now +1 day'))
            ->setBillingDayOfMonth($expectedBillingDayOfMonth = 15)
            ->setDescription($expectedDescription = 'description')
        ;

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

        /** @var Order $jwt */
        $jwt = $encoder->decode($jwt, 'Appsco\Market\Api\Model\Order');

        $this->assertNotEmpty($jwt->getJwtId());
        $this->assertEquals($expectedIssuer, $jwt->getIssuer());
        $this->assertGreaterThanOrEqual(0, time() - $jwt->getIssuedAt());

        $this->assertInstanceOf('Appsco\Market\Api\Model\Order', $jwt);

        $this->assertEquals($expectedPackageId, $jwt->getPackageId());

        $this->assertEquals($expectedApplicationId, $order->getAppId());

        $this->assertEquals($expectedOneTimePrice, $order->getOneTimePrice());
        $this->assertEquals($expectedRecurringPrice, $order->getRecurringPrice());
        $this->assertEquals($expectedTrialDuration, $order->getTrialDuration());
        $this->assertEquals($expectedTrialDurationUnit, $order->getTrialDurationUnit());
        $this->assertEquals($expectedFirstBillingDate->setTime(0, 0, 0), $order->getFirstBillingDate());
        $this->assertEquals($expectedBillingDayOfMonth, $order->getBillingDayOfMonth());
        $this->assertEquals($expectedDescription, $order->getDescription());

        $encoder->verify($jwt, 'file://'.__DIR__.'/key.crt');
    }



    public function testNewApp()
    {
        $order = Order::create(0);
        $order
            ->setPackageId($expectedPackageId = 123)
            ->setAppUrl($expectedUrl = 'http://example.com')
            ->setAppTitle($expectedTitle = 'example application')
            ->setAppIcon($expectedIcon = 'http://example.com/icon.png')
        ;


        $this->assertEquals($expectedPackageId, $order->getPackageId());
        $this->assertEquals($expectedUrl, $order->getAppUrl());
        $this->assertEquals($expectedTitle, $order->getAppTitle());
        $this->assertEquals($expectedIcon, $order->getAppIcon());
    }

} 