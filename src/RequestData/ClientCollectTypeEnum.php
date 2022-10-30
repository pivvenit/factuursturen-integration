<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\Vendor\Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class ClientCollectTypeEnum.
 */
final class ClientCollectTypeEnum extends AbstractEnumeration
{

    /**
     * Single direct debit.
     */
    const TYPE_DD_SINGLE = 'OOFF';

    /**
     * Direct debit: first collection.
     */
    const TYPE_DD_FIRST = 'FRST';

    /**
     * Direct debit: recurring collection.
     */
    const TYPE_DD_RECURRING = 'FRST';
}
