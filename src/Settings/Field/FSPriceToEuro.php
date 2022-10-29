<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;

/**
 * Class FSPriceToEuro.
 */
class FSPriceToEuro implements FieldInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $value)
    {
        echo '<input type="checkbox" name="' . $field_name . '" id="' . $field_id . '" value="1" ' . checked(1, $value, false) . '>';
        echo '<p class="help">' . __('Enable to automatically convert all invoice prices to EURO.<br>Prices are converted by factuursturen.nl service.', 'factuursturen-integration') . '</p>';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }

}
