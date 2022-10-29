<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;

/**
 * Class FSApiKey.
 */
class FSApiKey implements FieldInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $value)
    {
        echo '<input type="text" name="' . $field_name . '" id="' . $field_id . '" value="' . $value . '" class="regular-text code">';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }
}
