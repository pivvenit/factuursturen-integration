<?php

namespace Pivvenit\FactuurSturen\Settings\Field;

use Pivvenit\FactuurSturen\Settings\FieldInterface;
use Pivvenit\FactuurSturen\Util\Factuursturen;

/**
 * Class FSDefaultDoclang.
 */
class FSDefaultDoclang implements FieldInterface
{

    /**
     * Get fields options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $langs = Factuursturen::getDoclangs();
        return array_combine($langs, $langs);
    }

    /**
     * {@inheritDoc}
     */
    public function render($field_name, $field_id, $selected)
    {
        echo '<select name="' . $field_name . '" id="' . $field_id . '" class="regular-text">';
        foreach ($this->getOptions() as $value => $label) {
            echo '<option value="' . $value . '"' . ($value == $selected ? ' selected' : '') . '>' . $label . '</option>';
        }
        echo '</select>';
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // todo
    }

}
