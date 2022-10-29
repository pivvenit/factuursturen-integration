<?php

namespace Pivvenit\FactuurSturen\Settings\Section;

use Pivvenit\FactuurSturen\Settings\SectionInterface;
use Pivvenit\FactuurSturen\View\TemplateRenderTrait;

/**
 * Class FSInvoice.
 */
class FSInvoice implements SectionInterface
{
    use TemplateRenderTrait;

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->renderTemplate('admin/section-fsinvoice');
    }
}
