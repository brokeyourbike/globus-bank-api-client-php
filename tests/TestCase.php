<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Tests;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    protected function getGenerateTokenResponse(): ResponseInterface
    {
        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "corpCode": "john",
                "token": "super-secure-token",
                "responseCode": "00",
                "responseDescription": "Successful"
            }');

        /** @var ResponseInterface $mockedResponse */
        return $mockedResponse;
    }
}
