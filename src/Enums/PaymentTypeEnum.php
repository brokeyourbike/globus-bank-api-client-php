<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum PaymentTypeEnum: string
{
    case SALARY_PAYMENT = '1';
    case VENDOR_PAYMENT = '2';
    case OTHER_PAYMENT = '3';
}
