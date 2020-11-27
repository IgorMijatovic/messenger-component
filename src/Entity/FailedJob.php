<?php

namespace App\Entity;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Mime\Message;

class FailedJob
{

    private Envelope $envelope;

    public function __construct(Envelope $envelope)
    {
        $this->envelope = $envelope;
    }

    public function getMessage(): object
    {
        return $this->envelope->getMessage();
    }

    public function getId(): string
    {
        /** @var TransportMessageIdStamp[] $stamps */
        $stamps = $this->envelope->all(TransportMessageIdStamp::class);
        return end($stamps)->getId();
    }

    public function getTitle(): string {
        return get_class($this->envelope->getMessage());
    }

    public function getTrace(): string {
        /** @var RedeliveryStamp[] $stamps */
        $stamps = $this->envelope->all(RedeliveryStamp::class);
        return $stamps[0]->getFlattenException()->getTraceAsString();
    }

}
