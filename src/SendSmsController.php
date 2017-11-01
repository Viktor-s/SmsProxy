<?php

namespace SmsProxy;

use SmsGate\Message;
use SmsGate\Phone;
use SmsGate\Sender\SenderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SendSmsController
{
    /**
     * @var SmsSenderFactory
     */
    private $senderFactory;

    /**
     * Constructor.
     *
     * @param SmsSenderFactory $senderFactory
     */
    public function __construct(SmsSenderFactory $senderFactory)
    {
        $this->senderFactory = $senderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function handleAction(Request $request): Response
    {
        try {
            $phone = $this->getPhoneFromRequest($request);
            $message = $this->getMessageFromRequest($request);
        } catch (\RuntimeException $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->senderFactory->createByPhone($phone)
                ->send(new Message($message, SMS_SENDER), new Phone($phone));
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response();
    }

    /**
     * Get the phone from request
     *
     * @param Request $request
     *
     * @return string
     */
    private function getPhoneFromRequest(Request $request): string
    {
        if (!$request->query->has('phone')) {
            throw new \RuntimeException('Missing phone.');
        }

        return $request->query->get('phone');
    }

    /**
     * Get the message from request
     *
     * @param Request $request
     *
     * @return string
     */
    private function getMessageFromRequest(Request $request): string
    {
        if (!$request->query->get('message')) {
            throw new \RuntimeException('Missing message');
        }

        return $request->query->get('message');
    }
}
