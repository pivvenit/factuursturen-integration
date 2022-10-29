<?php

namespace Pivvenit\FactuurSturen\RequestData;

/**
 * Class ClientReference.
 *
 * Represents three lines that will be printed on the invoice.
 * Can be used for references to other documents or something else.
 */
class ClientReference implements ClientReferenceInterface
{

    /**
     * @var string
     */
    protected $line1 = '';

    /**
     * @var string
     */
    protected $line2 = '';

    /**
     * @var string
     */
    protected $line3 = '';

    /**
     * {@inheritDoc}
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * {@inheritDoc}
     */
    public function setLine1($line1)
    {
        $this->line1 = $line1;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * {@inheritDoc}
     */
    public function setLine2($line2)
    {
        $this->line2 = $line2;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLine3()
    {
        return $this->line3;
    }

    /**
     * {@inheritDoc}
     */
    public function setLine3($line3)
    {
        $this->line3 = $line3;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return array(
            'line1' => $this->getLine1(),
            'line2' => $this->getLine2(),
            'line3' => $this->getLine3(),
        );
    }
}
