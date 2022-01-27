<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;
use BrokeYourBike\GlobusBank\Models\MakeLocalPaymentResponse;
use BrokeYourBike\GlobusBank\Models\GetAccountBalanceResponse;
use BrokeYourBike\GlobusBank\Models\GenerateTokenResponse;
use BrokeYourBike\GlobusBank\Interfaces\TransactionInterface;
use BrokeYourBike\GlobusBank\Interfaces\ConfigInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;

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

    public function generateToken(): GenerateTokenResponse
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
            \GuzzleHttp\RequestOptions::JSON => [
                'CorpCode' => $this->config->getUsername(),
                'Password' => $this->config->getPassword(),
            ],
        ];

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), 'Auth/GenerateToken');

        $response = $this->httpClient->request(
            HttpMethodEnum::POST->value,
            $uri,
            $options
        );

        return new GenerateTokenResponse($response);
    }

    public function getAccountBalance(): GetAccountBalanceResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'Payment/GetAccountBalance', []);
        return new GetAccountBalanceResponse($response);
    }

    public function makeLocalPayment(TransactionInterface $transaction): MakeLocalPaymentResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'Payment/MakeLocalPayment', [
            'SourceAccount' => $this->config->getSourceAccount(),
            'PaymentTypeId' => $transaction->getPaymentType()->value,
            'PaymentMethodId' => $transaction->getPaymentMethod()->value,
            'CorporateCode' => $this->config->getUsername(),
            'EnableSingleDebit' => true,
            'BatchReference' => $transaction->getReference(),
            'SingleDebitNarration' => $transaction->getReference(),
            'PaymentList' => [
                [
                    'BankCode' => $transaction->getBeneficiaryBankCode(),
                    'Amount' => $transaction->getAmount(),
                    'BeneficiaryAccount' => $transaction->getBeneficiaryAccount(),
                    'BeneficiaryName' => $transaction->getBeneficiaryName(),
                    'Narration' => $transaction->getReference(),
                    'ValueDate' => $transaction->getValueDate()->format('Y-m-d'),
                ]
            ],
        ]);
        return new MakeLocalPaymentResponse($response);
    }

    /**
     * @param HttpMethodEnum $method
     * @param string $uri
     * @param array<mixed> $data
     * @return ResponseInterface
     */
    private function performRequest(HttpMethodEnum $method, string $uri, array $data): ResponseInterface
    {
        $tokenResponse = $this->generateToken();

        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$tokenResponse->token}"
            ],
        ];

        $option = match ($method) {
            HttpMethodEnum::GET => \GuzzleHttp\RequestOptions::QUERY,
            default => \GuzzleHttp\RequestOptions::JSON,
        };

        $options[$option] = $data;

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), $uri);
        return $this->httpClient->request($method->value, $uri, $options);
    }
}
