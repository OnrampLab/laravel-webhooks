<?php

namespace OnrampLab\Webhooks\Signer;

interface Signer
{
    public function signatureHeaderName(): string;

    public function generateSignature(array $payload, string $secret): string;
}
