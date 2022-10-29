<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;

/**
 * Class FSDefaultTaxRate.
 */
class FSDefaultTaxRate implements FieldInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $value)
    {
        echo '<input type="number" min="0" max="100" step="1" name="' . $field_name . '" id="' . $field_id . '" value="' . $value . '" class="code">';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }
}
