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
enum ErrorCodeEnum: string
{
    /**
     * Completed Successfully
     */
    case SUCCESS = '00';

    /**
     * Failed
     */
    case FAILED = '01';

    /**
     * Exception occurred
     */
    case EXCEPTION = '09';

    /**
     * Unauthorized
     */
    case UNAUTHORIZED = '401';

    /**
     * Bad Request
     */
    case BAD_REQUEST = '400';

    /**
     * Internal Server Error
     */
    case SERVER_ERROR = '500';
}
