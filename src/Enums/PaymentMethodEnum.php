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
enum PaymentMethodEnum: string
{
    case INSTANT = '1';
    case NEFT = '2';
    case RTGS = '3';
}
