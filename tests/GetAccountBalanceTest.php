<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\GlobusBank\Models\GetAccountBalanceResponse;
use BrokeYourBike\GlobusBank\Interfaces\ConfigInterface;
use BrokeYourBike\GlobusBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class GetAccountBalanceTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "result": {
                    "accountName": "JOHN LIMITED",
                    "accountNumber": "123456789",
                    "currency": "USD",
                    "availableBalance": 100.01,
                    "ledgerBalance": 300.00
                },
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
        $requestResult = $api->getAccountBalance();

        $this->assertInstanceOf(GetAccountBalanceResponse::class, $requestResult);
        $this->assertSame(100.01, $requestResult->availableBalance);
        $this->assertSame(300.00, $requestResult->ledgerBalance);
        $this->assertSame('USD', $requestResult->currency);
        $this->assertSame('123456789', $requestResult->accountNumber);
        $this->assertSame('JOHN LIMITED', $requestResult->accountName);
        $this->assertSame('00', $requestResult->responseCode);
        $this->assertSame('Successful', $requestResult->responseDescription);
    }
}
