<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\GlobusBank\Models\Payment;
use BrokeYourBike\GlobusBank\Models\GetBatchStatusResponse;
use BrokeYourBike\GlobusBank\Interfaces\ConfigInterface;
use BrokeYourBike\GlobusBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class GetBatchStatusTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "result": [
                    {
                        "id": 12341234,
                        "batchId": "13945|LLO2341",
                        "sourceAccount": "ACME INC",
                        "paymentStatus": "FAILED",
                        "paymentType": "Other Payment",
                        "paymentMethod": "INSTANT PAYMENT",
                        "paymentDate": "2022-01-28T16:20:45",
                        "valueDate": "2022-01-28T00:00:00",
                        "amount": 100,
                        "transactionRef": "23562356",
                        "beneficiaryAccount": "43653456235",
                        "beneficiaryName": "JOHN DOE",
                        "narration": "00124",
                        "singeNarration": "00124",
                        "subsidiaryName": "ACME INC"
                    }
                ],
                "responseCode": "00",
                "responseDescription": "Successful"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->expects()
            ->request(new \Mockery\Matcher\AnyArgs())
            ->twice()
            ->andReturns($this->getGenerateTokenResponse(), $mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);
        $requestResult = $api->getBatchStatus('batch-1');

        $this->assertInstanceOf(GetBatchStatusResponse::class, $requestResult);
        $this->assertSame('00', $requestResult->responseCode);
        $this->assertSame('Successful', $requestResult->responseDescription);
        $this->assertCount(1, $requestResult->result);

        $payment = $requestResult->result[0];
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertSame('FAILED', $payment->paymentStatus);
    }

    /** @test */
    public function it_can_handle_nullable_result()
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "responseCode": "00",
                "responseDescription": "Successful"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->expects()
            ->request(new \Mockery\Matcher\AnyArgs())
            ->twice()
            ->andReturns($this->getGenerateTokenResponse(), $mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);
        $requestResult = $api->getBatchStatus('batch-1');

        $this->assertInstanceOf(GetBatchStatusResponse::class, $requestResult);
        $this->assertSame('00', $requestResult->responseCode);
        $this->assertSame('Successful', $requestResult->responseDescription);
    }
}
