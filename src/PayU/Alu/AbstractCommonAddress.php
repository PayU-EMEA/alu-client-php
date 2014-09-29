<?php

namespace PayU\Alu;

/**
 * Class AbstractCommonAddress
 * @package PayU\Alu
 */
abstract class AbstractCommonAddress
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var string
     */
    protected $faxNumber;

    /**
     * @var string
     */
    protected $addressLine1;

    /**
     * @var string
     */
    protected $addressLine2;

    /**
     * @var string
     */
    protected $zipCode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @param string $addressLine1
     * @return $this
     */
    public function withAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    /**
     * @param string $addressLine2
     * @return $this
     */
    public function withAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function withCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $company
     * @return $this
     */
    public function withCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param string $countryCode
     * @return $this
     */
    public function withCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function withEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $faxNumber
     * @return $this
     */
    public function withFaxNumber($faxNumber)
    {
        $this->faxNumber = $faxNumber;
        return $this;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function withFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function withLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param string $phoneNumber
     * @return $this
     */
    public function withPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function withState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return $this
     */
    public function withZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }


    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
}
