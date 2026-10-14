<?php

namespace App\Config;

class ResponseCodes
{
    const   OK                      = '0000';
    const   WRONG_USER_CREDENTIALS  = '0010';
    const   PK_VIOLETED  = '00001';
    const   WRONG_PASSWORD          = '0011';
    const   PASSWORDS_DONT_MATCH    = '0012';
    const   USER_NOT_ACTIVE         = '0013';

    const   ERROR_SAVE_MODEL        = '0020';

    const   FILE_NOT_FOUND          = '0030';

    const   DB_ERROR                = '0100';
    const   DB_NO_DATA_FOUND        = '0110';

    const   BAD_REQUEST             = '400';

    const   CONFLICT                = '409';

    const   SYSTEM_ERROR            = '500';

    const   OPERATION_ABORTED       = '0200';

    const   WARN                    = '0300';
    const   FORBIDDEN               = '403';
}
