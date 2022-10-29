<?php

namespace Pivvenit\FactuurSturen\RequestData;

/**
 * Interface InvoiceLineInterface.
 */
interface InvoiceLineInterface
{

    /**
     * Gets the line amount.
     *
     * @return int
     */
    public function getAmount();

    /**
     * Sets the line amount.
     *
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount);

    /**
     * Gets the amount descripton.
     *
     * @return string
     */
    public function getAmountDesc();

    /**
     * Sets the line amount description.
     *
     * @param string $amount_desc
     * @return $this
     */
    public function setAmountDesc($amount_desc);

    /**
     * Gets the line description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Sets the line description.
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Gets the line tax rate.
     *
     * @return int
     */
    public function getTaxRate();

    /**
     * Sets the line tax rate.
     *
     * @param int $tax_rate
     * @return $this
     */
    public function setTaxRate($tax_rate);

    /**
     * Gets the line price.
     *
     * @return float
     */
    public function getPrice();

    /**
     * Sets the line price.
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Gets the percentage discount value.
     *
     * @return int
     */
    public function getDiscountPct();

    /**
     * Sets the percentage discount value.
     *
     * @param int $discount_pct
     * @return $this
     */
    public function setDiscountPct($discount_pct);

    /**
     * Checks whether the country tax should be used or not.
     *
     * @return int
     */
    public function getTaxCountry();

    /**
     * Sets whether the country tax should be used or not.
     *
     * @param bool $tax_country
     * @return $this
     */
    public function setTaxCountry($tax_country);

    /**
     * Converts simple array to the class instance.
     *
     * @param array $data
     * @return array
     */
    public static function fromArray(array $data);

    /**
     * Converts instance to data array compatible with factuursturen.nl service.
     *
     * @param bool $filter_nulls
     * @return array
     */
    public function toArray($filter_nulls = true);
}
