<?php

namespace SmsProxy;

use SmsGate\Adapter\TurboSms\TurboSmsAdapterFactory;
use SmsGate\Sender\Sender;
use SmsGate\Sender\SenderInterface;

class SmsSenderFactory
{
    /**
     * Create the SMS sender
     *
     * @return SenderInterface
     */
    public function create(): SenderInterface
    {
        $adapter = TurboSmsAdapterFactory::soap(TURBO_SMS_SOAP_LOGIN, TURBO_SMS_SOAP_PASSWORD);

        return new Sender($adapter);
    }
}
