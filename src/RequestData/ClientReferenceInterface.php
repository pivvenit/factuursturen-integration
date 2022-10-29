<?php

namespace Pivvenit\FactuurSturen\RequestData;

/**
 * Interface ClientReferenceInterface.
 *
 * Represents three lines that will be printed on the invoice.
 * Can be used for references to other documents or something else.
 */
interface ClientReferenceInterface
{

    /**
     * Gets the first line value.
     *
     * @return string
     */
    public function getLine1();

    /**
     * Sets the first line value.
     *
     * @param string $line1
     * @return $this
     */
    public function setLine1($line1);

    /**
     * Gets the second line value.
     *
     * @return string
     */
    public function getLine2();

    /**
     * Sets the second line value.
     *
     * @param string $line2
     * @return $this
     */
    public function setLine2($line2);

    /**
     * Gets the third line value.
     *
     * @return string
     */
    public function getLine3();

    /**
     * Sets the third line value.
     *
     * @param string $line3
     * @return $this
     */
    public function setLine3($line3);

    /**
     * Converts class instance to data array compatible with factuursturen.nl service.
     *
     * @return array
     */
    public function toArray();
}
