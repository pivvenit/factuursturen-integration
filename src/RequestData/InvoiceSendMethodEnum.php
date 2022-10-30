<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\Vendor\Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class InvoiceActionEnum.
 */
final class InvoiceSendMethodEnum extends AbstractEnumeration
{

    const METHOD_EMAIL       = 'email';
    const METHOD_MAIL        = 'mail';
    const METHOD_PRINTCENTER = 'printcenter';
}
