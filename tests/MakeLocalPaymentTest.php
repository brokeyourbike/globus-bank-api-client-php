<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Tests;

use Psr\Http\Message\ResponseInterface;
use Carbon\Carbon;
use BrokeYourBike\GlobusBank\Models\MakeLocalPaymentResponse;
use BrokeYourBike\GlobusBank\Models\GetAccountBalanceResponse;
use BrokeYourBike\GlobusBank\Interfaces\TransactionInterface;
use BrokeYourBike\GlobusBank\Interfaces\ConfigInterface;
use BrokeYourBike\GlobusBank\Enums\PaymentTypeEnum;
use BrokeYourBike\GlobusBank\Enums\PaymentMethodEnum;
use BrokeYourBike\GlobusBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class MakeLocalPaymentTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();

        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getReference')->willReturn('ref1234');
        $transaction->method('getPaymentType')->willReturn(PaymentTypeEnum::OTHER_PAYMENT);
        $transaction->method('getPaymentMethod')->willReturn(PaymentMethodEnum::INSTANT);
        $transaction->method('getBeneficiaryAccount')->willReturn('45678');
        $transaction->method('getBeneficiaryBankCode')->willReturn('777');
        $transaction->method('getBeneficiaryName')->willReturn('jane doe');
        $transaction->method('getAmount')->willReturn(50.02);
        $transaction->method('getValueDate')->willReturn(Carbon::create(2020, 2, 3));

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "corpCode": "john",
                "accountNumber": "123465798",
                "batchReference": "3674|TEST000001",
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
        $requestResult = $api->makeLocalPayment($transaction);

        $this->assertInstanceOf(MakeLocalPaymentResponse::class, $requestResult);
        $this->assertSame('john', $requestResult->corpCode);
        $this->assertSame('123465798', $requestResult->accountNumber);
        $this->assertSame('3674|TEST000001', $requestResult->batchReference);
        $this->assertSame('00', $requestResult->responseCode);
        $this->assertSame('Successful', $requestResult->responseDescription);
    }
}
