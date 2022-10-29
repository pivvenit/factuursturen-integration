<?php

namespace Pivvenit\FactuurSturen\RequestData;

interface ClientInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getContact();

    /**
     * @param string $contact
     * @return $this
     */
    public function setContact($contact);

    /**
     * @return bool
     */
    public function getShowcontact();

    /**
     * @param bool $showcontact
     * @return $this
     */
    public function setShowcontact($showcontact);

    /**
     * @return string
     */
    public function getCompany();

    /**
     * @param string $company
     * @return $this
     */
    public function setCompany($company);

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address);

    /**
     * @return string
     */
    public function getZipcode();

    /**
     * @param string $zipcode
     * @return $this
     */
    public function setZipcode($zipcode);

    /**
     * @return string
     */
    public function getCity();

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * @return int|string
     */
    public function getCountry();

    /**
     * @param int|string $country
     * @return $this
     */
    public function setCountry($country);

    /**
     * @return string
     */
    public function getPhone();

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone);

    /**
     * @return string
     */
    public function getMobile();

    /**
     * @param string $mobile
     * @return $this
     */
    public function setMobile($mobile);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getBankcode();

    /**
     * @param string $bankcode
     * @return $this
     */
    public function setBankcode($bankcode);

    /**
     * @return string
     */
    public function getBiccode();

    /**
     * @param string $biccode
     * @return $this
     */
    public function setBiccode($biccode);

    /**
     * @return string
     */
    public function getTaxnumber();

    /**
     * @param string $taxnumber
     * @return $this
     */
    public function setTaxnumber($taxnumber);

    /**
     * @return bool
     */
    public function getTaxShifted();

    /**
     * @param bool $tax_shifted
     * @return $this
     */
    public function setTaxShifted($tax_shifted);

    /**
     * @return \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum
     */
    public function getSendmethod();

    /**
     * @param \Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum $sendmethod
     * @return $this
     */
    public function setSendmethod(InvoiceSendMethodEnum $sendmethod);

    /**
     * @return \Pivvenit\FactuurSturen\RequestData\ClientPaymentMethodEnum
     */
    public function getPaymentmethod();

    /**
     * @param \Pivvenit\FactuurSturen\RequestData\ClientPaymentMethodEnum $paymentmethod
     * @return $this
     */
    public function setPaymentmethod(ClientPaymentMethodEnum $paymentmethod);

    /**
     * @return int
     */
    public function getTop();

    /**
     * @param int $top
     * @return $this
     */
    public function setTop($top);

    /**
     * @return int
     */
    public function getStddiscount();

    /**
     * @param int $stddiscount
     * @return $this
     */
    public function setStddiscount($stddiscount);

    /**
     * @return string
     */
    public function getMailintro();

    /**
     * @param string $mailintro
     * @return $this
     */
    public function setMailintro($mailintro);

    /**
     * @return \Pivvenit\FactuurSturen\RequestData\ClientReference
     */
    public function getReference();

    /**
     * @param \Pivvenit\FactuurSturen\RequestData\ClientReferenceInterface $reference
     * @return $this
     */
    public function setReference(\Pivvenit\FactuurSturen\RequestData\ClientReferenceInterface $reference);

    /**
     * @return string
     */
    public function getNotes();

    /**
     * @param string $notes
     * @return $this
     */
    public function setNotes($notes);

    /**
     * @return bool
     */
    public function getNotesOnInvoice();

    /**
     * @param bool $notes_on_invoice
     * @return $this
     */
    public function setNotesOnInvoice($notes_on_invoice);

    /**
     * @return bool
     */
    public function getActive();

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive($active);

    /**
     * @return string
     */
    public function getDefaultDoclang();

    /**
     * @param string $default_doclang
     * @return $this
     */
    public function setDefaultDoclang($default_doclang);

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * @return \Pivvenit\FactuurSturen\RequestData\ClientTaxTypeEnum
     */
    public function getTaxType();

    /**
     * @param \Pivvenit\FactuurSturen\RequestData\ClientTaxTypeEnum $tax_type
     * @return $this
     */
    public function setTaxType(ClientTaxTypeEnum $tax_type);

    /**
     * @return \Pivvenit\FactuurSturen\RequestData\ClientCollectTypeEnum
     */
    public function getCollecttype();

    /**
     * @param \Pivvenit\FactuurSturen\RequestData\ClientCollectTypeEnum $collecttype
     * @return $this
     */
    public function setCollecttype(ClientCollectTypeEnum $collecttype);

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
