<?php

namespace PayU\PaymentsApi\AluV3\Services;

use PayU\Alu\AirlineInfo;
use PayU\Alu\Billing;
use PayU\Alu\Card;
use PayU\Alu\Delivery;
use PayU\Alu\FX;
use PayU\Alu\MerchantConfig;
use PayU\Alu\Order;
use PayU\Alu\Product;
use PayU\Alu\Request;
use PayU\Alu\StoredCredentials;
use PayU\Alu\StrongCustomerAuthentication;
use PayU\Alu\User;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @var HashService \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockHashService;

    public function setUp()
    {
        $cfg = new MerchantConfig('MERCHANT_CODE', 'SECRET_KEY', 'RO');

        $user = new User('127.0.0.1');

        $this->order = new Order();

        $this->order->withBackRef('http://path/to/your/returnUrlScript')
            ->withOrderRef('MerchantOrderRef')
            ->withCurrency('RON')
            ->withOrderDate('2014-09-19 10:00:00')
            ->withOrderTimeout(1000)
            ->withPayMethod('CCVISAMC')
            ->withInstallmentsNumber(2)
            ->withCampaignType('EXTRA_INSTALLMENTS');

        $product = new Product();
        $product->withCode('PCODE01')
            ->withName('PNAME01')
            ->withPrice(100.0)
            ->withVAT(24.0)
            ->withQuantity(1);

        $this->order->addProduct($product);

        $product = new Product();
        $product->withCode('PCODE02')
            ->withName('PNAME02')
            ->withPrice(200.0)
            ->withVAT(24.0)
            ->withQuantity(1);

        $this->order->addProduct($product);

        $billing = new Billing();

        $billing->withAddressLine1('ADDRESS1')
            ->withAddressLine2('ADDRESS2')
            ->withCity('Bucuresti')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('John')
            ->withLastName('Doe')
            ->withPhoneNumber('0755167887')
            ->withIdentityCardNumber('324322');

        $delivery = new Delivery();
        $delivery->withAddressLine1('ADDRESS1')
            ->withAddressLine2('ADDRESS2')
            ->withCity('Istanbul')
            ->withCountryCode('RO')
            ->withEmail('john.doe@mail.com')
            ->withFirstName('John')
            ->withLastName('Doe')
            ->withPhoneNumber('0755167887');


        $card = new Card('5431210111111111', '11', 2016, 123, 'test');

        $this->request = new Request($cfg, $this->order, $billing, $delivery, $user, 'v3');

        $this->request->setCard($card);;

        $this->requestBuilder = new RequestBuilder();

        $this->mockHashService = $this->getMockBuilder('PayU\Alu\HashService')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetParams()
    {
        $result = $this->createExpectedRequest();

        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testGetParamsWithFx()
    {
        $result = $this->createExpectedRequest();

        $result['AUTHORIZATION_CURRENCY'] = 'EUR';
        $result['AUTHORIZATION_EXCHANGE_RATE'] = 0.2462;

        $fx = new FX('EUR', 0.2462);

        $this->request->setFx($fx);

        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testWhenAirlineInfoIsSent()
    {
        $airlineInfo = new AirlineInfo();

        $airlineInfo->setPassengerName('John Doe')
            ->setTicketNumber('TICKET_1234')
            ->setRestrictedRefund(0)
            ->setReservationSystem('DATS')
            ->setTravelAgencyCode('MYTRAVEL')
            ->setTravelAgencyName('My Travel Agency');

        $airlineInfo->addFlightSegment(
            '2017-01-10',
            'MOS',
            'SOF'
        );

        $airlineInfo->addFlightSegment(
            '2017-02-10',
            'ANK',
            'WDC',
            'XY',
            'B',
            1,
            'MAXY12',
            'F5512'
        );
        $this->order->withAirlineInfo($airlineInfo);

        $airlineInfoResult = array(
            'AIRLINE_INFO' => array(
                'PASSENGER_NAME' => 'John Doe',
                'TICKET_NUMBER' => 'TICKET_1234',
                'RESTRICTED_REFUND' => 0,
                'RESERVATION_SYSTEM' => 'DATS',
                'TRAVEL_AGENCY_CODE' => 'MYTRAVEL',
                'TRAVEL_AGENCY_NAME' => 'My Travel Agency',
                'FLIGHT_SEGMENTS' => array(
                    array(
                        'DEPARTURE_DATE' => '2017-01-10',
                        'DEPARTURE_AIRPORT' => 'MOS',
                        'DESTINATION_AIRPORT' => 'SOF',
                        'AIRLINE_CODE' => null,
                        'SERVICE_CLASS' => null,
                        'STOPOVER' => null,
                        'FARE_CODE' => null,
                        'FLIGHT_NUMBER' => null,
                    ),
                    array(
                        'DEPARTURE_DATE' => '2017-02-10',
                        'DEPARTURE_AIRPORT' => 'ANK',
                        'DESTINATION_AIRPORT' => 'WDC',
                        'AIRLINE_CODE' => 'XY',
                        'SERVICE_CLASS' => 'B',
                        'STOPOVER' => 1,
                        'FARE_CODE' => 'MAXY12',
                        'FLIGHT_NUMBER' => 'F5512',
                    ),
                ),
            ),
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($airlineInfoResult, $expectedRequest);

        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testWhenStoredCredentialsConsentTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsConsentType(StoredCredentials::CONSENT_TYPE_ON_DEMAND);

        $this->request->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE => $storedCredentials->getStoredCredentialsConsentType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->mockHashService->expects($this->exactly(2))
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testWhenStoredCredentialsRecurringConsentTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsConsentType(StoredCredentials::CONSENT_TYPE_RECURRING);

        $this->request->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE => $storedCredentials->getStoredCredentialsConsentType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->mockHashService->expects($this->exactly(2))
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testWhenStoredCredentialsRecurringSubsequentTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_RECURRING);

        $this->request->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE => $storedCredentials->getStoredCredentialsUseType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->mockHashService->expects($this->exactly(2))
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testWhenStoredCredentialsCardOnFileCardholderInitiatedTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_CARDHOLDER);

        $this->request->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE => $storedCredentials->getStoredCredentialsUseType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->mockHashService->expects($this->exactly(2))
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testWhenStoredCredentialsCardOnFileMerchantInitiatedTransaction()
    {
        $storedCredentials = new StoredCredentials();
        $storedCredentials->setStoredCredentialsUseType(StoredCredentials::USE_TYPE_MERCHANT);

        $this->request->setStoredCredentials($storedCredentials);

        $storedCredentialsResult = array(
            StoredCredentials::STORED_CREDENTIALS_USE_TYPE => $storedCredentials->getStoredCredentialsUseType()
        );

        $expectedRequest = $this->createExpectedRequest();

        $result = array_merge($storedCredentialsResult, $expectedRequest);

        $this->mockHashService->expects($this->exactly(2))
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $result,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );

        $this->assertArrayNotHasKey(
            StoredCredentials::STORED_CREDENTIALS_CONSENT_TYPE,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    public function testWhenStrongCustomerAuthenticationIsSent()
    {
        $strongCustomerAuthentication = new StrongCustomerAuthentication();
        $strongCustomerAuthentication->setStrongCustomerAuthentication('YES')
            ->setAddressMatch("YES")
            ->setBrowserAcceptHeaders('text/html')
            ->setBrowserIP('127.0.0.1')
            ->setBrowserJavaEnabled('YES')
            ->setBrowserLanguage('en-US')
            ->setBrowserColorDepth(24)
            ->setBrowserScreenHeight(864)
            ->setBrowserScreenWidth(1536)
            ->setBrowserTimezone(300)
            ->setBrowserUserAgent('Mozilla/5.0')
            ->setBillAddress3('445 Mount Eden Road, Mount Eden, Auckland')
            ->setBillStateCode('RO-B')
            ->setHomePhoneCountryPrefix('40')
            ->setHomePhoneSubscriber('5417543010')
            ->setMobilePhoneCountryPrefix('40')
            ->setMobilePhoneSubscriber('5231543010')
            ->setWorkPhoneCountryPrefix('40')
            ->setWorkPhoneSubscriber('4121543010')
            ->setDeliveryAddress3('445 Mount Eden Road, Mount Eden, Auckland')
            ->setDeliveryStateCode('RO-B')
            ->setCardHolderFraudActivity('YES')
            ->setDeviceChannel('01')
            ->setChallengeIndicator('01')
            ->setChallengeWindowSize('02')
            ->setAccountAdditionalInformation('any info here')
            ->setSdkReferenceNumber('487538453')
            ->setSdkMaximumTimeout('06')
            ->setSdkApplicationId('AA97B177-9383-4934-8543-0F91A7A02836')
            ->setSdkEncData('jwe object here')
            ->setSdkTransId('D952EB9F-7AD2-4B1B-B3CE-386735205990')
            ->setSdkEphemeralPubKey('public key component')
            ->setSdkUiType('01')
            ->setSdkInterface('03')
            ->setTransactionType('10')
            ->setShippingIndicator('03')
            ->setPreOrderIndicator('02')
            ->setPreOrderDate('2019-10-20')
            ->setDeliveryTimeFrame('03')
            ->setReOrderIndicator('01')
            ->setMerchantFundsAmount('123')
            ->setMerchantFundsCurrency('RON')
            ->setRecurringFrequencyDays('100')
            ->setRecurringExpiryDate('2019-12-01')
            ->setAccountCreateDate('2019-07-03')
            ->setAccountDeliveryAddressFirstUsedDate('2019-07-23')
            ->setAccountDeliveryAddressUsageIndicator('04')
            ->setAccountNumberOfTransactionsLastYear('23')
            ->setAccountNumberOfTransactionsLastDay('1')
            ->setAccountNumberOfPurchasesLastSixMonths('12')
            ->setAccountChangeDate('2019-09-14')
            ->setAccountChangeIndicator('02')
            ->setAccountAgeIndicator('03')
            ->setAccountPasswordChangedDate('2019-09-21')
            ->setAccountPasswordChangedIndicator('02')
            ->setAccountNameToRecipientMatch('YES')
            ->setAccountAddCardAttemptsDay('12')
            ->setAccountAuthMethod('03')
            ->setAccountAuthDateTime('2019-09-21 12:03:00')
            ->setRequestorAuthenticationData('Some additional data')
            ->setAccountCardAddedIndicator('05')
            ->setAccountCardAddedDate('2019-10-01');

        $this->request->setStrongCustomerAuthentication($strongCustomerAuthentication);

        $expectedResult = array_merge(
            $this->strongCustomerAuthenticationParams($strongCustomerAuthentication),
            $this->createExpectedRequest()
        );

        $this->mockHashService->expects($this->once())
            ->method('makeRequestHash')
            ->will($this->returnValue('hash'));

        $this->assertEquals(
            $expectedResult,
            $this->requestBuilder->buildAuthorizationRequest($this->request, $this->mockHashService)
        );
    }

    /**
     * @return array
     */
    public function createExpectedRequest()
    {
        $result = array(
            'ALIAS' => null,
            'BACK_REF' => 'http://path/to/your/returnUrlScript',
            'BILL_ADDRESS' => 'ADDRESS1',
            'BILL_ADDRESS2' => 'ADDRESS2',
            'BILL_BANK' => null,
            'BILL_BANKACCOUNT' => null,
            'BILL_CIISSUER' => null,
            'BILL_CINUMBER' => '324322',
            'BILL_CISERIAL' => null,
            'BILL_CITYPE' => null,
            'BILL_CITY' => 'Bucuresti',
            'BILL_CNP' => null,
            'BILL_COMPANY' => null,
            'BILL_COUNTRYCODE' => 'RO',
            'BILL_EMAIL' => 'john.doe@mail.com',
            'BILL_FAX' => null,
            'BILL_FISCALCODE' => null,
            'BILL_FNAME' => 'John',
            'BILL_LNAME' => 'Doe',
            'BILL_PHONE' => '0755167887',
            'BILL_REGNUMBER' => null,
            'BILL_STATE' => null,
            'BILL_ZIPCODE' => null,
            'CARD_PROGRAM_NAME' => null,
            'CC_CVV' => 123,
            'CC_NUMBER' => '5431210111111111',
            'CC_NUMBER_RECIPIENT' => null,
            'CC_OWNER' => 'test',
            'CLIENT_IP' => '127.0.0.1',
            'CLIENT_TIME' => '',
            'DELIVERY_ADDRESS' => 'ADDRESS1',
            'DELIVERY_ADDRESS2' => 'ADDRESS2',
            'DELIVERY_CITY' => 'Istanbul',
            'DELIVERY_COMPANY' => null,
            'DELIVERY_COUNTRYCODE' => 'RO',
            'DELIVERY_EMAIL' => 'john.doe@mail.com',
            'DELIVERY_FNAME' => 'John',
            'DELIVERY_LNAME' => 'Doe',
            'DELIVERY_PHONE' => '0755167887',
            'DELIVERY_STATE' => null,
            'DELIVERY_ZIPCODE' => null,
            'DISCOUNT' => null,
            'EXP_MONTH' => '11',
            'EXP_YEAR' => 2016,
            'MERCHANT' => 'MERCHANT_CODE',
            'ORDER_DATE' => '2014-09-19 10:00:00',
            'ORDER_MPLACE_MERCHANT' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'ORDER_PCODE' =>
                array(
                    0 => 'PCODE01',
                    1 => 'PCODE02',
                ),
            'ORDER_PGROUP' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'ORDER_PINFO' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'ORDER_PNAME' =>
                array(
                    0 => 'PNAME01',
                    1 => 'PNAME02',
                ),
            'ORDER_PRICE' =>
                array(
                    0 => 100,
                    1 => 200,
                ),
            'ORDER_QTY' =>
                array(
                    0 => 1,
                    1 => 1,
                ),
            'ORDER_REF' => 'MerchantOrderRef',
            'ORDER_SHIPPING' => null,
            'ORDER_VER' =>
                array(
                    0 => null,
                    1 => null,
                ),
            'PAY_METHOD' => 'CCVISAMC',
            'PRICES_CURRENCY' => 'RON',
            'SELECTED_INSTALLMENTS_NUMBER' => '2',
            'USE_LOYALTY_POINTS' => null,
            'LOYALTY_POINTS_AMOUNT' => null,
            'CAMPAIGN_TYPE' => 'EXTRA_INSTALLMENTS',
            'ORDER_PRICE_TYPE' =>
                array(
                    0 => 'NET',
                    1 => 'NET',
                ),
            'ORDER_VAT' =>
                array(
                    0 => 24,
                    1 => 24,
                ),
            'ORDER_HASH' => 'hash'
        );
        return $result;
    }

    private function strongCustomerAuthenticationParams(StrongCustomerAuthentication $strongCustomerAuthentication)
    {
        return array(
            'STRONG_CUSTOMER_AUTHENTICATION' => $strongCustomerAuthentication->getStrongCustomerAuthentication(),
            'ADDRESS_MATCH' => $strongCustomerAuthentication->getAddressMatch(),
            'BROWSER_ACCEPT_HEADER' => $strongCustomerAuthentication->getBrowserAcceptHeaders(),
            'BROWSER_IP' => $strongCustomerAuthentication->getBrowserIP(),
            'BROWSER_JAVA_ENABLED' => $strongCustomerAuthentication->getBrowserJavaEnabled(),
            'BROWSER_LANGUAGE' => $strongCustomerAuthentication->getBrowserLanguage(),
            'BROWSER_COLOR_DEPTH' => $strongCustomerAuthentication->getBrowserColorDepth(),
            'BROWSER_SCREEN_HEIGHT' => $strongCustomerAuthentication->getBrowserScreenHeight(),
            'BROWSER_SCREEN_WIDTH' => $strongCustomerAuthentication->getBrowserScreenWidth(),
            'BROWSER_TIMEZONE' => $strongCustomerAuthentication->getBrowserTimezone(),
            'BROWSER_USER_AGENT' => $strongCustomerAuthentication->getBrowserUserAgent(),
            'BILL_ADDRESS3' => $strongCustomerAuthentication->getBillAddress3(),
            'BILL_STATE_CODE' => $strongCustomerAuthentication->getBillStateCode(),
            'HOME_PHONE_COUNTRY_PREFIX' => $strongCustomerAuthentication->getHomePhoneCountryPrefix(),
            'HOME_PHONE_SUBSCRIBER' => $strongCustomerAuthentication->getHomePhoneSubscriber(),
            'MOBILE_PHONE_COUNTRY_PREFIX' => $strongCustomerAuthentication->getMobilePhoneCountryPrefix(),
            'MOBILE_PHONE_SUBSCRIBER' => $strongCustomerAuthentication->getMobilePhoneSubscriber(),
            'WORK_PHONE_COUNTRY_PREFIX' => $strongCustomerAuthentication->getWorkPhoneCountryPrefix(),
            'WORK_PHONE_SUBSCRIBER' => $strongCustomerAuthentication->getWorkPhoneSubscriber(),
            'DELIVERY_ADDRESS3' => $strongCustomerAuthentication->getDeliveryAddress3(),
            'DELIVERY_STATE_CODE' => $strongCustomerAuthentication->getDeliveryStateCode(),
            'CARDHOLDER_FRAUD_ACTIVITY' => $strongCustomerAuthentication->getCardHolderFraudActivity(),
            'DEVICE_CHANNEL' => $strongCustomerAuthentication->getDeviceChannel(),
            'CHALLENGE_INDICATOR' => $strongCustomerAuthentication->getChallengeIndicator(),
            'CHALLENGE_WINDOW_SIZE' => $strongCustomerAuthentication->getChallengeWindowSize(),
            'ACCOUNT_ADDITIONAL_INFORMATION' => $strongCustomerAuthentication->getAccountAdditionalInformation(),
            'SDK_REFERENCE_NUMBER' => $strongCustomerAuthentication->getSdkReferenceNumber(),
            'SDK_MAXIMUM_TIMEOUT' => $strongCustomerAuthentication->getSdkMaximumTimeout(),
            'SDK_APPLICATION_ID' => $strongCustomerAuthentication->getSdkApplicationId(),
            'SDK_ENC_DATA' => $strongCustomerAuthentication->getSdkEncData(),
            'SDK_TRANS_ID' => $strongCustomerAuthentication->getSdkTransId(),
            'SDK_EPHEMERAL_PUB_KEY' => $strongCustomerAuthentication->getSdkEphemeralPubKey(),
            'SDK_UI_TYPE' => $strongCustomerAuthentication->getSdkUiType(),
            'SDK_INTERFACE' => $strongCustomerAuthentication->getSdkInterface(),
            'TRANSACTION_TYPE' => $strongCustomerAuthentication->getTransactionType(),
            'SHIPPING_INDICATOR' => $strongCustomerAuthentication->getShippingIndicator(),
            'PREORDER_INDICATOR' => $strongCustomerAuthentication->getPreOrderIndicator(),
            'PREORDER_DATE' => $strongCustomerAuthentication->getPreOrderDate(),
            'DELIVERY_TIME_FRAME' => $strongCustomerAuthentication->getDeliveryTimeFrame(),
            'REORDER_INDICATOR' => $strongCustomerAuthentication->getReOrderIndicator(),
            'MERCHANT_FUNDS_AMOUNT' => $strongCustomerAuthentication->getMerchantFundsAmount(),
            'MERCHANT_FUNDS_CURRENCY' => $strongCustomerAuthentication->getMerchantFundsCurrency(),
            'RECURRING_FREQUENCY_DAYS' => $strongCustomerAuthentication->getRecurringFrequencyDays(),
            'RECURRING_EXPIRY_DATE' => $strongCustomerAuthentication->getRecurringExpiryDate(),
            'ACCOUNT_CREATE_DATE' => $strongCustomerAuthentication->getAccountCreateDate(),
            'ACCOUNT_DELIVERY_ADDRESS_FIRST_USED_DATE' =>
                $strongCustomerAuthentication->getAccountCreateDate(),
            'ACCOUNT_DELIVERY_ADDRESS_USAGE_INDICATOR' =>
                $strongCustomerAuthentication->getAccountDeliveryAddressUsageIndicator(),
            'ACCOUNT_NUMBER_OF_TRANSACTIONS_LAST_YEAR' =>
                $strongCustomerAuthentication->getAccountNumberOfTransactionsLastYear(),
            'ACCOUNT_NUMBER_OF_TRANSACTIONS_LAST_DAY' =>
                $strongCustomerAuthentication->getAccountNumberOfTransactionsLastDay(),
            'ACCOUNT_NUMBER_OF_PURCHASES_LAST_SIX_MONTHS' =>
                $strongCustomerAuthentication->getAccountNumberOfPurchasesLastSixMonths(),
            'ACCOUNT_CHANGE_DATE' => $strongCustomerAuthentication->getAccountChangeDate(),
            'ACCOUNT_CHANGE_INDICATOR' => $strongCustomerAuthentication->getAccountChangeIndicator(),
            'ACCOUNT_AGE_INDICATOR' => $strongCustomerAuthentication->getAccountAgeIndicator(),
            'ACCOUNT_PASSWORD_CHANGED_DATE' => $strongCustomerAuthentication->getAccountPasswordChangedDate(),
            'ACCOUNT_PASSWORD_CHANGED_INDICATOR' => $strongCustomerAuthentication->getAccountPasswordChangedIndicator(),
            'ACCOUNT_NAME_TO_RECIPIENT_MATCH' => $strongCustomerAuthentication->getAccountNameToRecipientMatch(),
            'ACCOUNT_ADD_CARD_ATTEMPTS_DAY' => $strongCustomerAuthentication->getAccountAddCardAttemptsDay(),
            'ACCOUNT_AUTH_METHOD' => $strongCustomerAuthentication->getAccountAuthMethod(),
            'ACCOUNT_AUTH_DATETIME' => $strongCustomerAuthentication->getAccountAuthDateTime(),
            'REQUESTOR_AUTHENTICATION_DATA' => $strongCustomerAuthentication->getRequestorAuthenticationData(),
            'ACCOUNT_CARD_ADDED_INDICATOR' => $strongCustomerAuthentication->getAccountCardAddedIndicator(),
            'ACCOUNT_CARD_ADDED_DATE' => $strongCustomerAuthentication->getAccountCardAddedDate()
        );
    }
}
