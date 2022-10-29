<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum;
use Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum;
use Pivvenit\FactuurSturen\RequestData\InvoiceLineInterface;
use Pivvenit\FactuurSturen\RequestData\ClientInterface;

interface InvoiceInterface
{

    /**
     * Gets the client data object.
     *
     * @return \Pivvenit\FactuurSturen\RequestData\ClientInterface
     */
    public function getClient();

    /**
     * Sets the client data object.
     *
     * @param \Pivvenit\FactuurSturen\RequestData\ClientInterface $action
     * @return $this
     */
    public function setClient(ClientInterface $action);

    /**
     * Sets the action. Accepts only values from \Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum.
     *
     * @return \Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum
     */
    public function getAction();

    /**
     * Sets the action name. Accepts only values from \Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum.
     *
     * @see \Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum
     *
     * @param string $action
     * @return $this
     */
    public function setAction(InvoiceActionEnum $action);

    /**
     * Gets the send method.
     *
     * @see \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum
     *
     * @return \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum
     */
    public function getSendMethod();

    /**
     * Sets the action name. Accepts only values from \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum.
     *
     * @see \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum
     *
     * @param \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum $action
     * @return $this
     */
    public function setSendMethod(InvoiceSendMethodEnum $sendMethod);

    /**
     * Sets the invoice name under which the invoice will be saved.
     *
     * @param string $saveName
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setSaveName($saveName);

    /**
     * Gets the save method.
     *
     * @return string
     */
    public function getSaveName();

    /**
     * Whether invoice should be overwritten if exists or not.
     *
     * @param bool $overwriteIfExist
     * @return $this
     */
    public function setOverwriteIfExist($overwriteIfExist);

    /**
     * Checks if overwriting existing invoices is enabled.
     *
     * @return bool
     */
    public function getOverwriteIfExist();

    /**
     * Whether to convert invoice prices to EURO based on the currency
     * set in the selected client and the invoice date (to retrieve the current exchange rate).
     *
     * @param bool $convertPricesToEuro
     *
     * @return $this
     */
    public function setConvertPricesToEuro($convertPricesToEuro);

    /**
     * Checks if prices are being converted to EURO.
     *
     * @return bool
     */
    public function getConvertPricesToEuro();

    /**
     * Gets either the paid amount value
     * or 'full' if the invoice is paid in full.
     *
     * @return float|string
     */
    public function getAlreadyPaid();

    /**
     * Sets the alreadypad param value.
     * Should be either paid value or 'full'.
     *
     * @param float|string $alreadyPaid
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setAlreadyPaid($alreadyPaid);

    /**
     * Gets the payment method.
     *
     * @return string
     */
    public function getAlreadyPaidMethod();

    /**
     * Sets the payment method.
     *
     * @param string $alreadyPaidMethod
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setAlreadyPaidMethod($alreadyPaidMethod);

    /**
     * Gets all the line items.
     *
     * @return \Pivvenit\FactuurSturen\RequestData\InvoiceLineInterface[]
     */
    public function getLineItems();

    /**
     * Add a line item to the invoice lines.
     *
     * @param \Pivvenit\FactuurSturen\RequestData\InvoiceLineInterface $item
     * @return $this
     */
    public function addLineItem(InvoiceLineInterface $item);

    /**
     * Sets the line items - overrides all already added items!
     *
     * @param \Pivvenit\FactuurSturen\RequestData\InvoiceLineInterface[] $lines
     * @return $this
     */
    public function setLineItems(array $lines);

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
