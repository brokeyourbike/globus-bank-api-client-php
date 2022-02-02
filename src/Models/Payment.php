<?php

// Copyright (C) 2022 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\GlobusBank\Models;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Payment extends DataTransferObject
{
    public string $id;
    public string $batchId;
    public string $paymentStatus;
    public string $paymentType;
    public string $paymentMethod;
    public string $paymentDate;
}
