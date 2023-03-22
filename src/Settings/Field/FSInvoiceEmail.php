<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;

/**
 * Class FSInvoiceEmail.
 */
class FSInvoiceEmail implements FieldInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $value)
    {
        echo '<input type="checkbox" name="' . $field_name . '" id="' . $field_id . '" value="1" ' . checked(1, $value, false) . '>';
        echo '<p class="help">' . __('Display an input for the (optional) invoice email-address which receives the Factuursturen invoice', 'factuursturen-integration') . '</p>';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }

}
