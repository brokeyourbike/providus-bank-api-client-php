<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\ProvidusBank\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum ErrorCodeEnum: string
{
    // success
    case SUCCESSFUL = '00';

    // pending
    case DOPMANT_ACCOUNT = '06';
    case PROCESSING_IN_PROGRESS = '09';
    case REVERSAL_NOT_SUCCESSFUL = '11';
    case INVALID_TRANSACTION = '12';
    case INVALID_BATCH_NUMBER = '14';
    case INVALID_SESSION_OR_RECORD_ID = '15';
    case INVALID_CHANNEL = '17';
    case WRONG_METHOD_CALL = '18';
    case UNABLE_TO_LOCATE_RECORD = '25';
    case REVERSAL_SUCCESSFUL = '31';
    case SUSPECTED_FRAUD = '34';
    case CONTACT_SENDING_BANK = '35';
    case TRANSFER_SUCCESSFUL = '36';
    case NO_SUFFICEINT_FUNDS = '51';
    case RESPONSE_RECEIVED_TOO_LATE = '68';
    case CUSTOMER_DETAILS_NOT_SUCCESSFULLY_VALIDATED = '69';
    case NOTIFICATION_NOT_SUCCESSFULLY_RECEIVED = '70';
    case INVALID_TRANSACTION_AMOUNT = '7706';
    case AUTHENTICATION_FAILED = '8004';
    case NO_CONNECTION = '8803';
    case UNKNOWN_NFPCALL_RESPONSE = '909';
    case NULL_NFPCALL_RESPONSE = '919';
    case LOCAL_DEFINED_TIMEOUT = '999';
    case NIP_AUTHENTICATION_FAILED = '9999';

    // failure
    case TRANSACTION_DOES_NOT_EXIST = '01';
    case INVALID_SENDER = '03';
    case DO_NOT_HONOR = '05';
    case INVALID_ACCOUNT = '07';
    case ACCOUNT_NAME_MISMATCH = '08';
    case INVALID_AMOUNT = '13';
    case UNKNOWN_BANK_CODE = '16';
    case NO_ACTION_TAKEN = '21';
    case DUPLICATE_RECORD = '26';
    case FORMAT_ERROR = '30';
    case TRANSFER_NOT_SUCCESSFUL = '32';
    case SERVICE_UNAVAILABLE = '505';
    case TRANSACTION_NOT_PERMITTED_TO_SENDER = '57';
    case TRANSACTION_NOT_PERMITTED_TO_CHANNEL = '58';
    case TRANSFER_LIMIT_EXCEEDED = '61';
    case SECURITY_VIOLATION = '63';
    case EXCEEDS_WITHDRAWAL_FREQUENCY = '65';
    case DEBIT_ACCOUNT_IS_INVALID = '7701';
    case CREDIT_ACCOUNT_IS_INVALID = '7702';
    case CREDIT_ACCOUNT_IS_DORMANT = '7703';
    case INSUFFICIENT_BALANCE_IN_DEBIT_ACCOUNT = '7704';
    case CURRENCY_MISMATCH = '7708';
    case TRANSACTION_REFERENCE_EXISTS = '7709';
    case CUSTOMER_ALREADY_ENROLLED_BUT_NOT_VALIDATED = '7710';
    case CUSTOMER_ALREADY_ENROLLED_BUT_NOT_ACTIVE = '7711';
    case CUSTOMER_ALREADY_ENROLLED = '7712';
    case OTP_SERVICE_FAILED_INVALID_ACCOUNT_NUMBER = '7713';
    case CUSTOMER_HAS_NOT_ENROLLED = '7714';
    case OTP_MISMATCH = '7715';
    case CUSTOMER_HAS_ALREADY_BEEN_VALIDATED = '7716';
    case CBA_SYSTEM_ERROR = '7799';
    case METHOD_NOT_ALLOWED = '8805';
    case CREDIT_ACCOUNT_NOT_PERMITTED = '8888';
    case BENEFICIARY_BANK_NOT_AVAILABLE = '91';
    case ROUTING_ERROR = '92';
    case DUPLICATE_TRANSACTION = '94';
    case SYSTEM_MALFUNCTION = '96';
    case TIME_OUT_WAITING_FOR_RESPONSE_FROM_DESTINATION = '97';
}
