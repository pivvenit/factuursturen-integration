<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;

/**
 * Class FSTaxNumber.
 */
class FSTaxNumber implements FieldInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $value)
    {
        echo '<input type="text" name="' . $field_name . '" id="' . $field_id . '" value="' . $value . '" class="regular-text code">';
		echo '<p class="help">' . __( 'The field name of the tax number. For example <code>billing_tax_number</code>.', 'factuursturen-integration' ) . '</p>';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }
}
