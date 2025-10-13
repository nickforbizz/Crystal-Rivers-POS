<?php

namespace App\Services;

use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    /**
     * Handle generic response parsing and logging
     */
    protected function handleResponse(Response $response)
    {
        $data = $response->json();
        Log::info('M-Pesa Response:', $data ?? []);
        return $data;
    }

    // 🔹 1. STK Push (Lipa Na Mpesa Online)
    public function stkPush(string $phoneNumber, float $amount, string $accountReference, ?string $callbackUrl = null, string $transactionType = 'PAYBILL')
    {
        $response = Mpesa::stkpush(
            $phoneNumber,
            $amount,
            $accountReference,
            $callbackUrl,
            $transactionType === 'PAYBILL' ? Mpesa::PAYBILL : Mpesa::TILL
        );

        return $this->handleResponse($response);
    }

    // 🔹 2. STK Query
    public function stkQuery(string $checkoutRequestId, string $shortCodeType = 'C2B')
    {
        $response = Mpesa::stkquery($checkoutRequestId, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 3. Register C2B URLs
    public function registerC2BUrls(string $shortcode, ?string $confirmUrl = null, ?string $validateUrl = null, string $shortCodeType = 'C2B')
    {
        $response = Mpesa::c2bregisterURLS($shortcode, $confirmUrl, $validateUrl, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 4. C2B Simulate
    public function simulateC2B(string $phoneNumber, float $amount, string $shortcode, string $commandId, ?string $accountNumber = null, string $shortCodeType = 'C2B')
    {
        $response = Mpesa::c2bsimulate($phoneNumber, $amount, $shortcode, $commandId, $accountNumber, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 5. B2C (Payouts)
    public function b2c(string $phoneNumber, string $commandId, float $amount, string $remarks, ?string $resultUrl = null, ?string $timeoutUrl = null, string $shortCodeType = 'B2C')
    {
        $response = Mpesa::b2c($phoneNumber, $commandId, $amount, $remarks, $resultUrl, $timeoutUrl, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 6. Validated B2C
    public function validatedB2C(string $phoneNumber, string $commandId, float $amount, string $remarks, string $idNumber, ?string $resultUrl = null, ?string $timeoutUrl = null, string $shortCodeType = 'B2C')
    {
        $response = Mpesa::validated_b2c($phoneNumber, $commandId, $amount, $remarks, $idNumber, $resultUrl, $timeoutUrl, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 7. Transaction Status
    public function transactionStatus(string $shortcode, string $transactionId, int $identifierType, string $remarks, ?string $resultUrl = null, ?string $timeoutUrl = null, string $shortCodeType = 'C2B')
    {
        $response = Mpesa::transactionStatus($shortcode, $transactionId, $identifierType, $remarks, $resultUrl, $timeoutUrl, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 8. Account Balance
    public function accountBalance(string $shortcode, int $identifierType, string $remarks, ?string $resultUrl = null, ?string $timeoutUrl = null, string $shortCodeType = 'C2B')
    {
        $response = Mpesa::accountBalance($shortcode, $identifierType, $remarks, $resultUrl, $timeoutUrl, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 9. Reversal
    public function reversal(string $shortcode, string $transactionId, float $amount, string $remarks, ?string $resultUrl = null, ?string $timeoutUrl = null, string $shortCodeType = 'C2B')
    {
        $response = Mpesa::reversal($shortcode, $transactionId, $amount, $remarks, $resultUrl, $timeoutUrl, $shortCodeType);
        return $this->handleResponse($response);
    }

    // 🔹 10. B2B
    public function b2b(string $receiverShortcode, string $commandId, float $amount, string $remarks, ?string $accountNumber = null, ?string $resultUrl = null, ?string $timeoutUrl = null, string $shortCodeType = 'B2B')
    {
        $response = Mpesa::b2b($receiverShortcode, $commandId, $amount, $remarks, $accountNumber, $resultUrl, $timeoutUrl, $shortCodeType);
        return $this->handleResponse($response);
    }
}
