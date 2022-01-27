<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Models;

use Spatie\DataTransferObject\Attributes\MapFrom;
use BrokeYourBike\DataTransferObject\JsonResponse;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class GetAccountBalanceResponse extends JsonResponse
{
    public string $responseCode;
    public string $responseDescription;

    #[MapFrom('result.accountName')]
    public ?string $accountName;

    #[MapFrom('result.accountNumber')]
    public ?string $accountNumber;

    #[MapFrom('result.currency')]
    public ?string $currency;

    #[MapFrom('result.availableBalance')]
    public ?string $availableBalance;

    #[MapFrom('result.ledgerBalance')]
    public ?string $ledgerBalance;
}
