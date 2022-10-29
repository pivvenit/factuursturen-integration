<?php

namespace Pivvenit\FactuurSturen\RequestData;

use Pivvenit\FactuurSturen\Util\ValueExtractor;
use Pivvenit\FactuurSturen\RequestData\ClientInterface;
use Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum;

/**
 * Class Client.
 */
class Client implements ClientInterface, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $contact = '';

    /**
     * Show the contact name on the invoice.
     *
     * @var bool
     */
    protected $showcontact = true;

    /**
     * @var string
     */
    protected $company = '';

    /**
     * @var string
     */
    protected $address = '';

    /**
     * @var string
     */
    protected $zipcode = '';

    /**
     * @var string
     */
    protected $city = '';

    /**
     * Country id. You can get a list of country id's with the function api/v1/countrylist.
     * When creating or updating a client, you can supply a country id or a country name.
     * We'll then try to find the id of the country you supplied.
     *
     * @var int|string
     */
    protected $country = '';

    /**
     * @var string
     */
    protected $phone = '';

    /**
     * @var string
     */
    protected $mobile = '';

    /**
     * Invoice is sent to this e-mail address, if the sendmethod is e-mail.
     *
     * @var string
     */
    protected $email = '';

    /**
     * The IBAN number of the client.
     *
     * @var string
     */
    protected $bankcode = '';

    /**
     * @var string
     */
    protected $biccode = '';

    /**
     * @var string
     */
    protected $taxnumber = '';

    /**
     * If the taxes on the invoice is shifted to the receiver.
     *
     * @var bool
     */
    protected $tax_shifted = false;

    /**
     * How to send the invoice to the receiver.
     *
     * @see \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum
     * 'mail': print the invoices yourself.
     * 'email': send invoices through e-mail.
     * 'printcenter': send invoice through the printcenter.
     *
     * @var string
     */
    protected $sendmethod = '';

    /**
     * How the invoice is going to be paid.
     *
     * @see Pivvenit\FactuurSturen\RequestData\ClientPaymentMethodEnum
     * 'bank': normal bank account transfer
     * 'autocollect': the invoice will be collected (incasso)
     *
     * @var string
     */
    protected $paymentmethod = '';

    /**
     * The term of payment in days.
     * Defines when the invoice has to be paid by the recipient.
     *
     * @var int
     */
    protected $top = 30;

    /**
     * Standard discount percentage for this client.
     * Every invoice defined for this client will automatically get this discount percentage.
     *
     * @var int
     */
    protected $stddiscount = 0;

    /**
     * The first line used in the e-mail to address the recipient.
     * Example: "Dear sir/madam,".
     *
     * @var string
     */
    protected $mailintro = '';

    /**
     * Three lines that will be printed on the invoice.
     * Can be used for references to other documents or something else.
     *
     * In the array are three fields: 'line1', 'line2' and 'line3'.
     * All fieldstypes are strings.
     *
     * @var \Pivvenit\FactuurSturen\RequestData\ClientReferenceInterface
     */
    protected $reference;

    /**
     * Notes saved for this client
     *
     * @var string
     */
    protected $notes = '';

    /**
     * Print the field 'notes' on every invoice for the client.
     *
     * @var bool
     */
    protected $notes_on_invoice = '';

    /**
     * Non-active clients are hidden in the web application.
     *
     * @var bool
     */
    protected $active = true;

    /**
     * In what language the invoice will be generated for this client.
     *
     * Possible options:
     * empty: default language
     * 'nl': dutch
     * 'en': english
     * 'de': german
     * 'fr': france
     * 'es': spanish
     *
     * @var string
     */
    protected $default_doclang = '';

    /**
     * Used currency in invoice. Like 'EUR', 'USD', etc.
     *
     * @var string
     */
    protected $currency;

    /**
     * Will show if the products on the invoice for this client will be handled as excluding or including tax:
     * 'intax': products will be handled as including tax
     * 'extax': products will be handled as excluding tax.
     *
     * @var string
     */
    protected $tax_type;

    /**
     * The collection type.
     *
     * @see \Pivvenit\FactuurSturen\RequestData\ClientCollectTypeEnum
     * 'OOFF': single direct debit
     * 'FRST': direct debit: first collection
     * 'RCUR': direct debit: recurring collection
     *
     * @var string
     */
    protected $collecttype;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->setReference(new ClientReference());
    }

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
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * {@inheritDoc}
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getShowcontact()
    {
        return $this->showcontact;
    }

    /**
     * {@inheritDoc}
     */
    public function setShowcontact($showcontact)
    {
        $this->showcontact = $showcontact;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * {@inheritDoc}
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * {@inheritDoc}
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * {@inheritDoc}
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * {@inheritDoc}
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * {@inheritDoc}
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBankcode()
    {
        return $this->bankcode;
    }

    /**
     * {@inheritDoc}
     */
    public function setBankcode($bankcode)
    {
        $this->bankcode = $bankcode;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBiccode()
    {
        return $this->biccode;
    }

    /**
     * {@inheritDoc}
     */
    public function setBiccode($biccode)
    {
        $this->biccode = $biccode;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxnumber()
    {
        return $this->taxnumber;
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxnumber($taxnumber)
    {
        $this->taxnumber = $taxnumber;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxShifted()
    {
        return $this->tax_shifted;
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxShifted($tax_shifted)
    {
        $this->tax_shifted = $tax_shifted;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSendmethod()
    {
        return $this->sendmethod;
    }

    /**
     * {@inheritDoc}
     */
    public function setSendmethod(InvoiceSendMethodEnum $sendmethod)
    {
        $this->sendmethod = $sendmethod;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentmethod()
    {
        return $this->paymentmethod;
    }

    /**
     * {@inheritDoc}
     */
    public function setPaymentmethod(ClientPaymentMethodEnum $paymentmethod)
    {
        $this->paymentmethod = $paymentmethod;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * {@inheritDoc}
     */
    public function setTop($top)
    {
        $this->top = $top;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getStddiscount()
    {
        return $this->stddiscount;
    }

    /**
     * {@inheritDoc}
     */
    public function setStddiscount($stddiscount)
    {
        $this->stddiscount = $stddiscount;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMailintro()
    {
        return $this->mailintro;
    }

    /**
     * {@inheritDoc}
     */
    public function setMailintro($mailintro)
    {
        $this->mailintro = $mailintro;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * {@inheritDoc}
     */
    public function setReference(\Pivvenit\FactuurSturen\RequestData\ClientReferenceInterface $reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * {@inheritDoc}
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getNotesOnInvoice()
    {
        return $this->notes_on_invoice;
    }

    /**
     * {@inheritDoc}
     */
    public function setNotesOnInvoice($notes_on_invoice)
    {
        $this->notes_on_invoice = (bool) $notes_on_invoice;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * {@inheritDoc}
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultDoclang()
    {
        return $this->default_doclang;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultDoclang($default_doclang)
    {
        $this->default_doclang = $default_doclang;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxType()
    {
        return $this->tax_type;
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxType(ClientTaxTypeEnum $tax_type)
    {
        $this->tax_type = $tax_type;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCollecttype()
    {
        return $this->collecttype;
    }

    /**
     * {@inheritDoc}
     */
    public function setCollecttype(ClientCollectTypeEnum $collecttype)
    {
        $this->collecttype = $collecttype;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data)
    {
        $instance = new self();

        $instance
            ->setId(ValueExtractor::getValueWithDefault($data, 'clientnr'))
            ->setActive('true' == ValueExtractor::getValueWithDefault($data, 'active', 'false'))
            ->setAddress(ValueExtractor::getValueWithDefault($data, 'address'))
            ->setBankcode(ValueExtractor::getValueWithDefault($data, 'bankcode'))
            ->setBiccode(ValueExtractor::getValueWithDefault($data, 'biccode'))
            ->setCity(ValueExtractor::getValueWithDefault($data, 'city'))
            ->setCompany(ValueExtractor::getValueWithDefault($data, 'company'))
            ->setContact(ValueExtractor::getValueWithDefault($data, 'contact'))
            ->setCountry(ValueExtractor::getValueWithDefault($data, 'country'))
            ->setCurrency(ValueExtractor::getValueWithDefault($data, 'currency'))
            ->setDefaultDoclang(ValueExtractor::getValueWithDefault($data, 'default_doclang'))
            ->setEmail(ValueExtractor::getValueWithDefault($data, 'email'))
            ->setMailintro(ValueExtractor::getValueWithDefault($data, 'mailintro'))
            ->setMobile(ValueExtractor::getValueWithDefault($data, 'mobile'))
            ->setNotes(ValueExtractor::getValueWithDefault($data, 'notes'))
            ->setNotesOnInvoice('true' == ValueExtractor::getValueWithDefault($data, 'notes_on_invoice', 'false'))
            ->setPhone(ValueExtractor::getValueWithDefault($data, 'phone'))
            ->setShowcontact('true' == ValueExtractor::getValueWithDefault($data, 'showcontact', 'false'))
            ->setStddiscount(ValueExtractor::getValueWithDefault($data, 'stddiscount'))
            ->setTaxShifted('true' == ValueExtractor::getValueWithDefault($data, 'tax_shifted', 'false'))
            ->setTaxnumber(ValueExtractor::getValueWithDefault($data, 'taxnumber'))
            ->setTop(ValueExtractor::getValueWithDefault($data, 'top'))
            ->setZipcode(ValueExtractor::getValueWithDefault($data, 'zipcode'))
        ;

        if (isset($data['reference'])) {
            $reference = new ClientReference();
            $reference
                ->setLine1(ValueExtractor::getValueWithDefault($data['reference'], 'line1'))
                ->setLine2(ValueExtractor::getValueWithDefault($data['reference'], 'line2'))
                ->setLine3(ValueExtractor::getValueWithDefault($data['reference'], 'line3'));
            $instance->setReference($reference);
        }

        $collecttype = ClientCollectTypeEnum::memberByValueWithDefault(ValueExtractor::getValueWithDefault($data, 'collecttype'), null, true);
        !($collecttype instanceof ClientCollectTypeEnum) ?: $instance->setCollecttype($collecttype);

        $paymentmethod = ClientPaymentMethodEnum::memberByValueWithDefault(ValueExtractor::getValueWithDefault($data, 'paymentmethod'), null, true);
        !($paymentmethod instanceof ClientPaymentMethodEnum) ?: $instance->setPaymentmethod($paymentmethod);

        $sendmethod = InvoiceSendMethodEnum::memberByValueWithDefault(ValueExtractor::getValueWithDefault($data, 'sendmethod'), null, true);
        !($sendmethod instanceof InvoiceSendMethodEnum) ?: $instance->setSendmethod($sendmethod);

        $tax_type = ClientTaxTypeEnum::memberByValueWithDefault(ValueExtractor::getValueWithDefault($data, 'tax_type'), null, true);
        !($tax_type instanceof ClientTaxTypeEnum) ?: $instance->setTaxType($tax_type);

        return $instance;
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
    public function toArray($filter_nulls = true)
    {
        $data = array(
            'clientnr' => $this->getId(),
            'contact' => $this->getContact(),
            'showcontact' => $this->getShowcontact() ? 'true' : 'false',
            'company' => $this->getCompany(),
            'address' => $this->getAddress(),
            'zipcode' => $this->getZipcode(),
            'city' => $this->getCity(),
            'country' => $this->getCountry(),
            'phone' => $this->getPhone(),
            'mobile' => $this->getMobile(),
            'email' => $this->getEmail(),
            'bankcode' => $this->getBankcode(),
            'biccode' => $this->getBiccode(),
            'taxnumber' => $this->getTaxnumber(),
            'tax_shifted' => $this->getTaxShifted() ? 'true' : 'false',
            'top' => $this->getTop(),
            'stddiscount' => $this->getStddiscount(),
            'mailintro' => $this->getMailintro(),
            'reference' => $this->getReference()->toArray(),
            'notes' => $this->getNotes(),
            'notes_on_invoice' => $this->getNotesOnInvoice() ? 'true' : 'false',
            'active' => $this->getActive() ? 'true' : 'false',
            'default_doclang' => $this->getDefaultDoclang(),
            'currency' => $this->getCurrency(),
        );

        if($this->getTaxType() instanceof ClientTaxTypeEnum) {
            $data['tax_type'] = $this->getTaxType()->value();
        }

        if($this->getSendMethod() instanceof InvoiceSendMethodEnum) {
            $data['sendmethod'] = $this->getSendMethod()->value();
        }

        if($this->getPaymentmethod() instanceof ClientPaymentMethodEnum) {
            $data['paymentmethod'] = $this->getPaymentmethod()->value();
        }

        if($this->getCollecttype() instanceof ClientCollectTypeEnum) {
            $data['collecttype'] = $this->getCollecttype()->value();
        }

        return $filter_nulls ? array_filter($data) : $data;
    }

}
