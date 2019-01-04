<?php
namespace PayU\Alu\Transformer;

use PayU\Alu\Component\Component;
use PayU\Alu\Exception\InvalidArgumentException;
use PayU\Alu\Component\Response;

class ResponseTransformer extends Transformer
{
    /**
     * @param Component $component
     * @return array
     */
    public function transform(Component $component)
    {
        if (!$component instanceof Response) {
            throw new InvalidArgumentException("Unexpected type: " . get_class($component));
        }

        /** @var \PayU\Alu\Component\Response $response */
        $response = $component;

        $data = array(
            'REFNO' => $response->getRefno(),
            'ALIAS' => $response->getAlias(),
            'STATUS' => $response->getStatus(),
            'RETURN_CODE' => $response->getReturnCode(),
            'RETURN_MESSAGE' => $response->getReturnMessage(),
            'DATE' => $response->getDate()
        );

        if (!is_null($response->getAmount())) {
            $data['AMOUNT'] = $response->getAmount();
        }
        if (!is_null($response->getCurrency())) {
            $data['CURRENCY'] = $response->getCurrency();
        }
        if (!is_null($response->getInstallmentsNo())) {
            $data['INSTALLMENTS_NO'] = $response->getInstallmentsNo();
        }
        if (!is_null($response->getCardProgramName())) {
            $data['CARD_PROGRAM_NAME'] = $response->getCardProgramName();
        }
        if (!is_null($response->getOrderRef())) {
            $data['ORDER_REF'] = $response->getOrderRef();
        }
        if (!is_null($response->getAuthCode())) {
            $data['AUTH_CODE'] = $response->getAuthCode();
        }
        if (!is_null($response->getRrn())) {
            $data['RRN'] = $response->getRrn();
        }

        $data = array_merge($data, $response->getAdditionalResponseParameters());

        if (!is_null($response->getUrlRedirect())) {
            $data['URL_REDIRECT'] = $response->getUrlRedirect();
        }
        if (!is_null($response->getTokenHash())) {
            $data['TOKEN_HASH'] = $response->getTokenHash();
        }

        if (is_array($response->getWireAccounts())) {
            foreach ($response->getWireAccounts() as $account) {
                $data['WIRE_ACCOUNTS'][] = array(
                    'BANK_IDENTIFIER' => $account->getBankIdentifier(),
                    'BANK_ACCOUNT' => $account->getBankAccount(),
                    'ROUTING_NUMBER' => $account->getRoutingNumber(),
                    'IBAN_ACCOUNT' => $account->getIbanAccount(),
                    'BANK_SWIFT' => $account->getBankSwift(),
                    'COUNTRY' => $account->getCountry(),
                    'WIRE_RECIPIENT_NAME' => $account->getWireRecipientName(),
                    'WIRE_RECIPIENT_VAT_ID' => $account->getWireRecipientVatId(),
                );
            }
        }

        return $data;
    }
}