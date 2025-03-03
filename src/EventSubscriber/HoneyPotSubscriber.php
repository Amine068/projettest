<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HoneyPotSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $honeyPotLogger;
    private RequestStack $requestStack;

    public function __construct(LoggerInterface $honeyPotLogger, RequestStack $requestStack)
    {
        $this->honeyPotLogger = $honeyPotLogger;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'checkHoneyJar'
        ];
    }

    public function checkHoneyJar(FormEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return;
        }

        $data = $event->getData();

        if (!array_key_exists('phone', $data) || !array_key_exists('faxNumber', $data)) {
            throw new HttpException(400, "Form has been modified");
        }

        [
            'phone' => $phone,
            'faxNumber' => $faxNumber
        ] = $data;

        if ($phone !== "" || $faxNumber !== "")
        {
            $this->honeyPotLogger->info("robot, IP: ".$request->getClientIp().". le champ phone contenait:".$phone."et le champ faxNumber contenait: ".$faxNumber.".");
            throw new HttpException(403, "robot a tenter de dire: ".$phone.".");
        }
    }
}