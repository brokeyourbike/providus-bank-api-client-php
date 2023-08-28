<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\ProvidusBank;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\ProvidusBank\Responses\TransactionResponse;
use BrokeYourBike\ProvidusBank\Responses\AccountResponse;
use BrokeYourBike\ProvidusBank\Interfaces\TransactionInterface;
use BrokeYourBike\ProvidusBank\Interfaces\ConfigInterface;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;
use BrokeYourBike\HasSourceModel\SourceModelInterface;
use BrokeYourBike\HasSourceModel\HasSourceModelTrait;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;
    use HasSourceModelTrait;

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, ClientInterface $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function canFetchAccount(): bool
    {
        $response = $this->performRequest(HttpMethodEnum::OPTIONS, 'GetProvidusAccount', []);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }

    public function fetchAccount(string $accountNumber): AccountResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'GetProvidusAccount', ['accountNumber' => $accountNumber]);
        return new AccountResponse($response);
    }

    public function domesticTransfer(TransactionInterface $transaction): TransactionResponse
    {
        if ($transaction instanceof SourceModelInterface){
            $this->setSourceModel($transaction);
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'ProvidusFundTransfer', [
            'transactionReference' => $transaction->getReference(),
            'creditAccount' => $this->config->getSourceAccountNumber(),
            'debitAccount' => $transaction->getBankAccount(),
            'currencyCode' => $transaction->getCurrencyCode(),
            'transactionAmount' => $transaction->getAmount(),
            'narration' => $transaction->getDescription(),
        ]);

        return new TransactionResponse($response);
    }

    public function transfer(TransactionInterface $transaction): TransactionResponse
    {
        if ($transaction instanceof SourceModelInterface){
            $this->setSourceModel($transaction);
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'NIPFundTransfer', [
            'transactionReference' => $transaction->getReference(),
            'narration' => $transaction->getDescription(),
            'currencyCode' => $transaction->getCurrencyCode(),
            'transactionAmount' => $transaction->getAmount(),
            'sourceAccountName' => $this->config->getSourceAccountName(),
            'beneficiaryAccountName' => $transaction->getRecipientName(),
            'beneficiaryAccountNumber' => $transaction->getBankAccount(),
            'beneficiaryBank' => $transaction->getBankCode(),
        ]);

        return new TransactionResponse($response);
    }

    public function fetchDomesticTransactionStatus(string $reference): TransactionResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'ProvidusFundTransfer', [
            'transactionReference' => $reference,
        ]);

        return new TransactionResponse($response);
    }

    public function fetchTransactionStatus(string $reference): TransactionResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'GetNIPTransactionStatus', [
            'transactionReference' => $reference,
        ]);

        return new TransactionResponse($response);
    }

    /**
     * @param HttpMethodEnum $method
     * @param string $uri
     * @param array<mixed> $data
     * @return ResponseInterface
     */
    private function performRequest(HttpMethodEnum $method, string $uri, array $data): ResponseInterface
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
        ];

        if ($method === HttpMethodEnum::POST) {
            $options[\GuzzleHttp\RequestOptions::JSON] = array_merge($data, [
                'userName' => $this->config->getUsername(),
                'password' => $this->config->getPassword(),
            ]);
        }

        if ($this->getSourceModel()) {
            $options[\BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL] = $this->getSourceModel();
        }

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), $uri);
        return $this->httpClient->request($method->value, $uri, $options);
    }
}
