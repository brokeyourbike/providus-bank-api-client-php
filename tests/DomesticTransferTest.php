<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\ProvidusBank\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\ProvidusBank\Responses\TransactionResponse;
use BrokeYourBike\ProvidusBank\Interfaces\TransactionInterface;
use BrokeYourBike\ProvidusBank\Interfaces\ConfigInterface;
use BrokeYourBike\ProvidusBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class DomesticTransferTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getReference')->willReturn('ref-123');
        $transaction->method('getBankAccount')->willReturn('12345');
        $transaction->method('getBankCode')->willReturn('bank12345');
        $transaction->method('getRecipientName')->willReturn('John Doe');
        $transaction->method('getCurrencyCode')->willReturn('USD');
        $transaction->method('getAmount')->willReturn(50.00);
        $transaction->method('getDescription')->willReturn('test');

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);
        
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn('john');
        $mockedConfig->method('getPassword')->willReturn('password');
        $mockedConfig->method('getSourceAccountNumber')->willReturn('source1234');
        $mockedConfig->method('getSourceAccountName')->willReturn('sourceJane');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "amount": "100.0",
                "transactionReference": "12451245124",
                "currency": "NGN",
                "responseMessage": "OPERATION SUCCESSFUL",
                "responseCode": "00"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);

        $requestResult = $api->domesticTransfer($transaction);
        $this->assertInstanceOf(TransactionResponse::class, $requestResult);
    }

    /** @test */
    public function it_can_fetch_status(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn('john');
        $mockedConfig->method('getPassword')->willReturn('password');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "amount": "60.0",
                "recipientBankCode": "000012",
                "recipientAccountNumber": "0012345023",
                "transactionReference": "143r1fafr",
                "transactionDateTime": "2017-12-15 15:12:12.0",
                "currency": "NGN",
                "responseMessage": "Transfer_Not_Successful",
                "responseCode": "32"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);

        $requestResult = $api->fetchDomesticTransactionStatus('ref-1234');
        $this->assertInstanceOf(TransactionResponse::class, $requestResult);
    }
}