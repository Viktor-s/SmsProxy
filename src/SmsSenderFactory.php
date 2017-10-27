<?php

namespace SmsProxy;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SmsGate\Adapter\TurboSms\TurboSmsAdapterFactory;
use SmsGate\Sender\LoggingSenderDecorator;
use SmsGate\Sender\Sender;
use SmsGate\Sender\SenderInterface;

class SmsSenderFactory
{
    /**
     * @var string
     */
    private $logDirectory;

    /**
     * Constructor.
     *
     * @param string $logDirectory
     */
    public function __construct(string $logDirectory)
    {
        $this->logDirectory = $logDirectory;
    }

    /**
     * Create the SMS sender
     *
     * @return SenderInterface
     */
    public function create(): SenderInterface
    {
        $adapter = TurboSmsAdapterFactory::soap(TURBO_SMS_SOAP_LOGIN, TURBO_SMS_SOAP_PASSWORD);

        $sender = new Sender($adapter);
        $logger = $this->createLogger();

        return new LoggingSenderDecorator($sender, $logger);
    }

    /**
     * Create the logger
     *
     * @return LoggerInterface
     */
    private function createLogger(): LoggerInterface
    {
        $logFile = sprintf(
            '%s/sms.log',
            rtrim($this->logDirectory, '/')
        );

        $handler = new RotatingFileHandler($logFile, 0, LogLevel::DEBUG);

        return new Logger('sms', [$handler]);
    }
}
