<?php

namespace OnrampLab\Webhooks\Signer;

class DefaultSigner implements Signer
{
    public function signatureHeaderName(): string
    {
        return config('laravel-webhooks.signature_header_name');
    }

    public function generateSignature(array $payload, string $secret): string
    {
        $payloadJson = json_encode($payload);
        return hash_hmac('sha256', $payloadJson, $secret);
    }
}
