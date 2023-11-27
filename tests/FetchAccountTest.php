<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\ProvidusBank\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\ProvidusBank\Responses\AccountResponse;
use BrokeYourBike\ProvidusBank\Interfaces\ConfigInterface;
use BrokeYourBike\ProvidusBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class FetchAccountTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn('john');
        $mockedConfig->method('getPassword')->willReturn('password');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "accountStatus": "ACTIVE",
                "emailAddress": "john@doe.com",
                "phoneNumber": "0123456789",
                "accountName": "JOHN DOE",
                "bvn": "34567",
                "accountNumber": "54321",
                "cbaCustomerID": "45678",
                "responseMessage": "SUCCESSFUL",
                "availableBalance": "12.34",
                "responseCode": "00"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/GetProvidusAccount',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'accountNumber' => 'account123',
                    'userName' => 'john',
                    'password' => 'password',
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);

        $requestResult = $api->fetchAccount('account123');
        $this->assertInstanceOf(AccountResponse::class, $requestResult);
    }
}
