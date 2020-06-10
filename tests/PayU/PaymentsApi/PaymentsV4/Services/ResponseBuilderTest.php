<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\Response;
use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationResponse;
use PHPUnit\Framework\TestCase;

class ResponseBuilderTest extends TestCase
{
    /**
     * @var ResponseBuilder
     */
    private $responseBuilder;

    public function setUp()
    {
        $this->responseBuilder = new ResponseBuilder();
    }

    private function createSuccessResponseArray()
    {
        return [
            'meta' => [
                'status' => [
                    "code" => 0,
                    "message" => "success"
                ],
                'response' => [
                    "httpCode" => 200,
                    "httpMessage" => "200 OK"
                ],
                'version' => 'v2'
            ],
            'response' => [
                "token" => "b7e5d8649c9e2e75726b59c56c29e91d",
                "cardUniqueIdentifier" => "e9fc5107db302fa8373efbedf55a1614b5a3125ee59fe274e7dc802930d68f6d"
            ]
        ];
    }

    private function createErrorResponseArray()
    {
        return [
            'meta' => [
                'status' => [
                    "code" => 400,
                    "message" => "No order with reference number: 120393911"
                ],
                'response' => [
                    "httpCode" => 400,
                    "httpMessage" => "400 Bad Request"
                ],
                'version' => 'v2'
            ],
            'error' => [
                "code" => 400,
                "message" => "No order with reference number: 120393911"
            ]
        ];
    }

    public function testBuildTokenResponseWithSuccessResponse()
    {
        // Given
        $authorizationResponse = new AuthorizationResponse($this->createSuccessResponseArray());
        $expectedResponse = new Response();
        $expectedResponse->setTokenCode(0);
        $expectedResponse->setTokenMessage('success');
        $expectedResponse->setTokenHash('b7e5d8649c9e2e75726b59c56c29e91d');

        // Then
        $this->assertEquals(
            $expectedResponse,
            $this->responseBuilder->buildTokenResponse($authorizationResponse, new Response())
        );
    }

    public function testBuildTokenResponseWithErrorResponse()
    {
        // Given
        $authorizationResponse = new AuthorizationResponse($this->createErrorResponseArray());
        $expectedResponse = new Response();
        $expectedResponse->setTokenCode(400);
        $expectedResponse->setTokenMessage('No order with reference number: 120393911');

        // Then
        $this->assertEquals(
            $expectedResponse,
            $this->responseBuilder->buildTokenResponse($authorizationResponse, new Response())
        );
    }
}
