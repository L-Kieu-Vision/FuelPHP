<?php

/**
 * Lists for exeception codes.
 *
 */

namespace Api\Exception;

class ExceptionCode
{

    const E_OK = 200;

    # 4xx Client Error
    const E_BAD_REQUEST = 400;
    const E_UNAUTHORIZED = 401;
    const E_PAYMENT_REQUIRED = 402;
    const E_FORBIDDEN = 403;
    const E_NOT_FOUND = 404;
    const E_METHOD_NOT_ALLOWED = 405;

    # 5xx Server Error
    const E_INTERNAL_SERVER_ERROR = 500;
    const E_NOT_IMPLEMENTED = 501;
    const E_BAD_GATEWAY = 502;
    const E_SERVICE_UNAVAILABLE = 503;
    const E_GATEWAY_TIMEOUT = 504;
    const E_HTTP_VERSION_NOT_SUPPORTED = 505;

    # 3xxx: Validation error
    const E_VALIDATION_ERROR_FIELD = 3000;

    # 4xxx: Elastic error
    const E_ELASTIC_ERROR_CONNECT = 4000;
    const E_ELASTIC_ERROR_PARAM = 4001;

    # 5xxx: App error
    const E_APP_ERROR_PERMISSION = 5000;
    const E_APP_ERROR_LOGIN = 5001;
    const E_APP_ERROR_COMMON = 5002;
    const E_NOT_HAVE_PERMISSION = 5003;

    # 6xxx: Upload error
    const E_UPLOAD_ERROR_NOT_FILE = 6000;
    // duplicate the PHP standard error codes for consistency
    const UPLOAD_ERR_OK = UPLOAD_ERR_OK;
    const UPLOAD_ERR_INI_SIZE = UPLOAD_ERR_INI_SIZE;
    const UPLOAD_ERR_FORM_SIZE = UPLOAD_ERR_FORM_SIZE;
    const UPLOAD_ERR_PARTIAL = UPLOAD_ERR_PARTIAL;
    const UPLOAD_ERR_NO_FILE = UPLOAD_ERR_NO_FILE;
    const UPLOAD_ERR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;
    const UPLOAD_ERR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE;
    const UPLOAD_ERR_EXTENSION = UPLOAD_ERR_EXTENSION;
    // and add our own error codes
    const UPLOAD_ERR_MAX_SIZE = 101;
    const UPLOAD_ERR_EXT_BLACKLISTED = 102;
    const UPLOAD_ERR_EXT_NOT_WHITELISTED = 103;
    const UPLOAD_ERR_TYPE_BLACKLISTED = 104;
    const UPLOAD_ERR_TYPE_NOT_WHITELISTED = 105;
    const UPLOAD_ERR_MIME_BLACKLISTED = 106;
    const UPLOAD_ERR_MIME_NOT_WHITELISTED = 107;
    const UPLOAD_ERR_MAX_FILENAME_LENGTH = 108;
    const UPLOAD_ERR_MOVE_FAILED = 109;
    const UPLOAD_ERR_DUPLICATE_FILE = 110;
    const UPLOAD_ERR_MKDIR_FAILED = 111;
    const UPLOAD_ERR_FTP_FAILED = 112;

    # 9xxx: System error
    const E_SYSTEM_ERROR = 9000;

}
