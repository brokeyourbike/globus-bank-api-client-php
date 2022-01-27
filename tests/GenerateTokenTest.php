<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\GlobusBank\Models\GenerateTokenResponse;
use BrokeYourBike\GlobusBank\Interfaces\ConfigInterface;
use BrokeYourBike\GlobusBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class GenerateTokenTest extends TestCase
{
    private string $username = 'john';
    private string $password = 'secure-password';

    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn($this->username);
        $mockedConfig->method('getPassword')->willReturn($this->password);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "corpCode": "john",
                "token": "super-secure-token",
                "responseCode": "00",
                "responseDescription": "Successful"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/Auth/GenerateToken',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'CorpCode' => $this->username,
                    'Password' => $this->password,
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);
        $requestResult = $api->generateToken();

        $this->assertInstanceOf(GenerateTokenResponse::class, $requestResult);
        $this->assertSame('super-secure-token', $requestResult->token);
    }
}
