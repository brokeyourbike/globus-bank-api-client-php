<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Interfaces;

use BrokeYourBike\GlobusBank\Enums\PaymentTypeEnum;
use BrokeYourBike\GlobusBank\Enums\PaymentMethodEnum;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
interface TransactionInterface
{
    public function getReference(): string;
    public function getPaymentType(): PaymentTypeEnum;
    public function getPaymentMethod(): PaymentMethodEnum;
    public function getSourceAccount(): string;
    public function getBeneficiaryAccount(): string;
    public function getBeneficiaryBankCode(): string;
    public function getBeneficiaryName(): string;
    public function getAmount(): float;
    public function getValueDate(): \DateTimeInterface;
}
