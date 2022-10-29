<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\Util\ValueExtractor;
use Pivvenit\FactuurSturen\Util\FactuursturenClient;
use Pivvenit\FactuurSturen\RequestData\InvoiceLine;
use Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum;
use Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum;
use Pivvenit\FactuurSturen\RequestData\InvoiceInterface;
use Pivvenit\FactuurSturen\RequestData\InvoiceLineInterface;
use Pivvenit\FactuurSturen\RequestData\ClientInterface;

class Invoice implements InvoiceInterface, \JsonSerializable
{

    /**
     * The invoice ID.
     *
     * @var int
     */
    protected $id;

    /**
     * Define the action what we should do with your request.
     *
     * @var \Pivvenit\FactuurSturen\RequestData\ClientInterface
     */
    protected $client;

    /**
     * Define the action what we should do with your request.
     *
     * @var string
     *
     * @see \Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum
     */
    protected $action;

    /**
     * How to send the invoice to the receiver.
     * Required when you use the action InvoiceActionEnum::ACTION_SEND.
     *
     * @var string
     *
     * @see \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum
     */
    protected $sendMethod;

    /**
     * When the action is InvoiceActionEnum::ACTION_SEND or InvoiceActionEnum::ACTION_REPEAT you must supply a savename.
     * We'll save the invoice under that name.
     *
     * @var string
     */
    protected $saveName;

    /**
     * If a savename already exists, it will not be overwritten unless
     * this attribute is set to 'true'. Default is 'false'.
     *
     * @var bool
     */
    protected $overwriteIfExist;

    /**
     * When this option is set to 'true' we will convert all the given prices on the invoices to euro,
     * based on the currency set in the selected client and the invoice date (to retrieve the current exchange rate).
     *
     * @var bool
     */
    protected $convertPricesToEuro;

    /**
     * Mark a part of the invoice as already paid.
     * We'll place an extra line in the PDF that says that a part of the invoice is already paid.
     * We'll also register that a part is paid in your account.
     *
     * Supply the amount in float. You can also supply 'full' as a value. We will mark the invoice total as amount.
     *
     * @var float|string
     */
    protected $alreadyPaid;

    /**
     * Enter a string of the payment method.
     * It can be anything you want, for example: 'Pin', 'Cash', 'MasterCard', 'Gift from Santa', etc.
     *
     * @var string
     */
    protected $alreadyPaidMethod;

    /**
     * Invoice line items.
     *
     * @var \Pivvenit\FactuurSturen\RequestData\InvoiceLineInterface[]
     */
    protected $lines = array();

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritDoc}
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * {@inheritDoc}
     */
    public function setAction(InvoiceActionEnum $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSendMethod()
    {
        return $this->sendMethod;
    }

    /**
     * {@inheritDoc}
     */
    public function setSendMethod(InvoiceSendMethodEnum $sendMethod)
    {
        $this->sendMethod = $sendMethod;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setSaveName($saveName)
    {
        if (20 < strlen($saveName)) {
            throw new \InvalidArgumentException(printf('%1$s::%2$s method accepts numeric values or "full" only.', get_called_class(), __FUNCTION__));
        }

        $this->saveName = $saveName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveName()
    {
        return $this->saveName;
    }

    /**
     * {@inheritDoc}
     */
    public function setOverwriteIfExist($overwriteIfExist)
    {
        $this->overwriteIfExist = $overwriteIfExist;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOverwriteIfExist()
    {
        return $this->overwriteIfExist;
    }

    /**
     * {@inheritDoc}
     */
    public function setConvertPricesToEuro($convertPricesToEuro)
    {
        $this->convertPricesToEuro = $convertPricesToEuro;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getConvertPricesToEuro()
    {
        return $this->convertPricesToEuro;
    }

    /**
     * {@inheritDoc}
     */
    public function getAlreadyPaid()
    {
        return $this->alreadyPaid;
    }

    /**
     * {@inheritDoc}
     */
    public function setAlreadyPaid($alreadyPaid)
    {
        if ($alreadyPaid && !is_numeric($alreadyPaid) && 'full' !== $alreadyPaid) {
            throw new \InvalidArgumentException(printf('%1$s::%2$s method accepts numeric values or "full" only, "%3$s" given', get_called_class(), __FUNCTION__, $alreadyPaid));
        }

        $this->alreadyPaid = $alreadyPaid;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAlreadyPaidMethod()
    {
        return $this->alreadyPaidMethod;
    }

    /**
     * {@inheritDoc}
     */
    public function setAlreadyPaidMethod($alreadyPaidMethod)
    {
        if (32 < strlen($alreadyPaidMethod)) {
            throw new \InvalidArgumentException(printf('%1$s::%2$s method accepts maximum of 32-character long values.', get_called_class(), __FUNCTION__));
        }

        $this->alreadyPaidMethod = $alreadyPaidMethod;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLineItems()
    {
        return $this->lines;
    }

    /**
     * {@inheritDoc}
     */
    public function addLineItem(InvoiceLineInterface $item)
    {
        $this->lines[] = $item;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setLineItems(array $lines)
    {
        $this->lines = $lines;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data)
    {
        $instance = new self();

        $instance
            ->setId(ValueExtractor::getValueWithDefault($data, 'invoicenr'))
            ->setAlreadyPaid(ValueExtractor::getValueWithDefault($data, 'alreadypaid'))
            ->setAlreadyPaidMethod(ValueExtractor::getValueWithDefault($data, 'alreadypaidmethod'))
            ->setConvertPricesToEuro('true' == ValueExtractor::getValueWithDefault($data, 'convert_prices_to_euro', 'false'))
            ->setLineItems(array_map(function ($line) {
                    return InvoiceLine::fromArray($line);
            }, ValueExtractor::getValueWithDefault($data, 'lines', array())))
            ->setOverwriteIfExist('true' == ValueExtractor::getValueWithDefault($data, 'overwrite_if_exist', 'false'))
            ->setSaveName(ValueExtractor::getValueWithDefault($data, 'savename'))
        ;

        if (($client = ValueExtractor::getValueWithDefault($data, 'client')) instanceof ClientInterface) {
            $instance->setClient($client);
        }

        $action = InvoiceActionEnum::memberByValueWithDefault(ValueExtractor::getValueWithDefault($data, 'action'));
        !($action instanceof InvoiceActionEnum) ?: $instance->setAction($action);

        $sendmethod = InvoiceSendMethodEnum::memberByValueWithDefault(ValueExtractor::getValueWithDefault($data, 'sendmethod'));
        !($sendmethod instanceof InvoiceSendMethodEnum) ?: $instance->setSendMethod($sendmethod);

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray($filter_nulls = true)
    {
        $data = array(
            'invoicenr' => $this->getId(),
            'clientnr' => $this->getClient()->getId(),
            'overwrite_if_exist' => $this->getOverwriteIfExist(),
            'convert_prices_to_euro' => $this->getConvertPricesToEuro(),
            'alreadypaid' => $this->getAlreadyPaid(),
            'alreadypaidmethod' => $this->getAlreadyPaidMethod(),
            'lines' => array_map(function ($item) {
                    return $item->toArray();
            }, $this->getLineItems()),
        );

        if($this->getAction() instanceof InvoiceActionEnum) {
            $data['action'] = $this->getAction()->value();
        }

        if($this->getSendMethod() instanceof InvoiceSendMethodEnum) {
            $data['sendmethod'] = $this->getSendMethod()->value();
        }

        if ($this->getAction()->value() == InvoiceActionEnum::ACTION_SAVE) {
            $data['savename'] = $this->getSaveName();
        }

        return $filter_nulls ? array_filter($data) : $data;
    }
}
