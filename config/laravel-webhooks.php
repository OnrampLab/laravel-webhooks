<?php

return [
    /*
     * The default queue to be used for sending webhook requests.
     */
    'queue' => 'default',

    /*
     * Headers to be added to all webhook requests.
     */
    'headers' => [
        'Content-Type' => 'application/json',
    ],

    /*
     * If a webhook call takes longer than this specified number of seconds,
     * the attempt will be considered failed.
     */
    'timeout_in_seconds' => 10,

    /*
     * When set to true, an exception will be thrown when the last attempt fails
     */
    'throw_exception_on_failure' => false,

    /*
     * This is the name of the header where the signature will be added.
     */
    'signature_header_name' => 'signature',

    /*
     * This class is responsible for generating the signature that will be added to
     * the headers of the webhook request. A webhook client can use the signature
     * to verify the request hasn't been tampered with.
     */
    'signer' => \OnrampLab\Webhooks\Signer\DefaultSigner::class,

    /*
    * When set to true, a webhook log will be created in the webhook logs table after the webhook request is made
    */
    'should_create_webhook_log' => true,

    /*
    * The maximum number of attempts to dispatch the webhook before considering it failed.
    */
    'max_attempts' => 3,

    /*
     * The interval (in seconds) between retry attempts for failed webhooks.
     */
    'retry_interval' => 60,
];
