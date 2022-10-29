<?php

namespace Pivvenit\FactuurSturen\Settings;

interface FieldInterface
{

    /**
     * Renders the setting field.
     *
     * @param string $field_name
     * @param string $field_id
     * @param mixed  $value
     */
    public function render($field_name, $field_id, $value);

    /**
     * Triggered on save.
     * Keep in mind that saving option value is handled by WordPress.
     */
    public function save();
}
