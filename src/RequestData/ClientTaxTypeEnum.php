<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class ClientTaxTypeEnum.
 */
final class ClientTaxTypeEnum extends AbstractEnumeration
{

    /**
     * Products will be handled as including tax.
     */
    const TYPE_INTAX = 'intax';

    /**
     * Products will be handled as excluding tax.
     */
    const TYPE_EXTAX = 'extax';
}
