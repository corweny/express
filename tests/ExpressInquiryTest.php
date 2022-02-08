<?php
namespace Walkerdistace\Tests;

use PHPUnit\Framework\TestCase;
use Walkerdistance\LaravelExpress\ExpressInquiry;

class ExpressInquiryTest extends TestCase
{
    protected $customer = '**********************************';
    protected $api_key = '*********';

    public function testExpressInquiry()
    {
        $express = new ExpressInquiry([
            'customer' => $this->customer,
            'api_key' => $this->api_key,
        ]);
        $response = $express->getPollQuery('************',0,'desc');
        var_dump($response);
        $this->assertArrayHasKey('status',$response);
        $this->assertIsArray($response);
        $this->assertStringContainsString(200,$response['status']);
    }

    public function testExpressInquiryData()
    {
        $express = new ExpressInquiry([
            'customer' => $this->customer,
            'api_key' => $this->api_key,
        ]);
        $response = $express->getPollQuery('************');
        var_dump($response);
    }

    public function testExpressMapTrack()
    {
        $express = new ExpressInquiry([
            'customer' => $this->customer,
            'api_key' => $this->api_key,
        ]);
        $response = $express->getPollMapTrack('************');
        var_dump($response);
    }
}