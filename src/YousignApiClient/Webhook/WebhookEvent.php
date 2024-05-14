<?php

namespace YousignApiClient\Webhook;

class WebhookEvent {
    private string $eventName;

    const ALL = '*';
    const SIGNATURE_REQUEST_DECLINED = 'signature_request.declined';
    const SIGNATURE_REQUEST_DONE = 'signature_request.done';
    const SIGNATURE_REQUEST_ACTIVATED = 'signature_request.activated';
    const SIGNATURE_REQUEST_REMINDER_EXECUTED = 'signature_request.reminder_executed';
    const SIGNATURE_REQUEST_EXPIRED = 'signature_request.expired';
    const SIGNATURE_REQUEST_APPROVED = 'signature_request.approved';
    const SIGNATURE_REQUEST_PERMANENTLY_DELETED = 'signature_request.permanently_deleted';
    const SIGNER_DECLINED = 'signer.declined';
    const SIGNER_DONE = 'signer.done';
    const SIGNER_NOTIFIED = 'signer.notified';
    const SIGNER_LINK_OPENED = 'signer.link_opened';
    const SIGNER_IDENTIFICATION_BLOCKED = 'signer.identification_blocked';
    const SIGNER_SENDER_CONTACTED = 'signer.sender_contacted';
    const SIGNER_ERROR = 'signer.error';
    const CONTACT_CREATED = 'contact.created';
    const APPROVER_NOTIFIED = 'approver.notified';
    const APPROVER_APPROVED = 'approver.approved';
    const APPROVER_REJECTED = 'approver.rejected';
    const ELECTRONIC_SEAL_ERROR = 'electronic_seal.error';
    const ELECTRONIC_SEAL_DONE = 'electronic_seal.done';

    public function __construct(string $eventName) {
        if ( $eventName === "*" || defined('self::' . $eventName)) {
            $this->eventName = $eventName;
        } else {
            throw new \InvalidArgumentException('Invalid event name');
        }
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): WebhookEvent
    {
        $this->eventName = $eventName;
        return $this;
    }


}