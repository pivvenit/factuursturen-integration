<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\Vendor\Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class ClientPaymentMethodEnum.
 */
final class ClientPaymentMethodEnum extends AbstractEnumeration
{

    /**
     * Normal bank account transfer.
     */
    const METHOD_BANK = 'bank';

    /**
     * The invoice will be collected (incasso).
     */
    const METHOD_AUTOCOLLECT = 'autocollect';
}
