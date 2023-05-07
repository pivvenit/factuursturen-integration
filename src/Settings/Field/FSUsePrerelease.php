<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;

/**
 * Class FSUsePrerelease.
 */
class FSUsePrerelease implements FieldInterface
{

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $value)
    {
        echo '<input type="checkbox" name="' . $field_name . '" id="' . $field_id . '" value="1" ' . checked(1, $value, false) . '>';
        echo '<p class="help">' . __('Use prerelease version of this plugin', 'factuursturen-integration') . '</p>';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }

}
