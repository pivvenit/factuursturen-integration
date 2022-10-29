<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;

/**
 * Class FSMailIntro.
 */
class FSMailIntro implements FieldInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $value)
    {
        echo '<textarea name="' . $field_name . '" id="' . $field_id . '" class="regular-text code">' . $value . '</textarea>';
        echo '<p class="help">' . __( 'Used as an intro text in emails. For example <code>Dear Mr(s),</code>.', 'factuursturen-integration' ) . '</p>';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }
}
