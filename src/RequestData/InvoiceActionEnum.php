<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\Vendor\Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class InvoiceActionEnum.
 */
final class InvoiceActionEnum extends AbstractEnumeration
{

    const ACTION_SEND = 'send';
    const ACTION_SAVE = 'save';

    // Repeat action is not implemented in the invoice class!
    // const ACTION_REPEAT = 'repeat';
}
