<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\Util\ValueExtractor;
use Pivvenit\FactuurSturen\RequestData\InvoiceLineInterface;

/**
 * Class InvoiceLine.
 */
class InvoiceLine implements InvoiceLineInterface
{

    /**
     * Number of products bought.
     *
     * @var int
     */
    protected $amount;

    /**
     * Unit name.
     *
     * @var string
     */
    protected $amount_desc = '';

    /**
     * The product name or description for displaying on the invoice.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Percentage tax rate.
     *
     * @var float|int
     */
    protected $tax_rate;

    /**
     * Line value.
     *
     * @var float
     */
    protected $price;

    /**
     * Discount percentage value.
     *
     * @var int
     */
    protected $discount_pct;

    /**
     * Set to true if the foreign tax should be used.
     *
     * @var bool
     */
    protected $tax_country;

    /**
     * {@inheritDoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritDoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAmountDesc()
    {
        return $this->amount_desc;
    }

    /**
     * {@inheritDoc}
     */
    public function setAmountDesc($amount_desc)
    {
        $this->amount_desc = $amount_desc;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxRate()
    {
        return $this->tax_rate;
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxRate($tax_rate)
    {
        $this->tax_rate = max(0, $tax_rate);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * {@inheritDoc}
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDiscountPct()
    {
        return $this->discount_pct;
    }

    /**
     * {@inheritDoc}
     */
    public function setDiscountPct($discount_pct)
    {
        $this->discount_pct = $discount_pct;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxCountry()
    {
        return $this->tax_country;
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxCountry($tax_country)
    {
        $this->tax_country = $tax_country;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data)
    {
        $instance = new self();
        $instance
            ->setAmount(ValueExtractor::getValueWithDefault($data, 'amount'))
            ->setAmountDesc(ValueExtractor::getValueWithDefault($data, 'amount_desc'))
            ->setDescription(ValueExtractor::getValueWithDefault($data, 'description'))
            ->setDiscountPct(ValueExtractor::getValueWithDefault($data, 'dicount_pct'))
            ->setPrice(ValueExtractor::getValueWithDefault($data, 'price'))
            ->setTaxRate(ValueExtractor::getValueWithDefault($data, 'tax_rate'))
        ;

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray($filter_nulls = true)
    {
        $array = array(
            'amount' => $this->getAmount(),
            'amount_desc' => $this->getAmountDesc(),
            'description' => $this->getDescription(),
            'tax_rate' => $this->getTaxRate(),
//            'tax_country' => $this->getTaxCountry() ? 'true' : 'false', // causes error when country is not foreign - whatever it means
            'price' => $this->getPrice(),
            'dicount_pct' => $this->getDiscountPct(),
        );

        return $filter_nulls ? array_filter($array, function ($item) {
			return !is_null($item);
		}) : null;
    }
}
