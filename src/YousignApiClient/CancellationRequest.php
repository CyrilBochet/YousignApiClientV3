<?php

namespace YousignApiClient;
;

class CancellationRequest
{
    private const CONTRACTUALIZATION_ABORTED = 'contractualization_aborted';
    private const ERRORS_IN_DOCUMENT = 'errors_in_document';
    private const OTHER = 'other';
    private ?string $id;
    private string $reason;
    private string $signatureRequestId;
    private ?string $customNote;

    public function __construct(string $reason, string $signatureRequestId, ?string $customNote = null)
    {
        if (!in_array($reason, [self::CONTRACTUALIZATION_ABORTED, self::ERRORS_IN_DOCUMENT, self::OTHER], true)) {
            throw new \InvalidArgumentException("Invalid reason");
        }

        $this->reason = $reason;
        $this->signatureRequestId = $signatureRequestId;
        $this->customNote = $customNote;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): CancellationRequest
    {
        $this->id = $id;
        return $this;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): CancellationRequest
    {
        $this->reason = $reason;
        return $this;
    }

    public function getCustomNote(): ?string
    {
        return $this->customNote;
    }

    public function setCustomNote(?string $customNote): CancellationRequest
    {
        $this->customNote = $customNote;
        return $this;
    }

    public function getSignatureRequestId(): string
    {
        return $this->signatureRequestId;
    }

    public function setSignatureRequestId(string $signatureRequestId): CancellationRequest
    {
        $this->signatureRequestId = $signatureRequestId;
        return $this;
    }

    public function toJson(): string
    {

        return <<< JSON
        {
          "reason": "$this->reason",
          "custom_note":"$this->customNote"
        }
        JSON;
    }
}
