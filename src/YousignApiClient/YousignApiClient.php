<?php
/*
 * *************************************************************************
 *  * Copyright (C) Cyril BOCHET - All Rights Reserved
 *  * @project     YousignApiClient
 *  * @file        YousignApiClient.php
 *  * @author      Cyril BOCHET
 *  * @site        https://www.linkedin.com/in/cyril-bochet
 *  * @date        19/04/2024 14:48
 *
 */

namespace YousignApiClient;


use CURLFile;
use YousignApiClient\Fields\CheckboxField;
use YousignApiClient\Fields\Field;
use YousignApiClient\Fields\MentionField;
use YousignApiClient\Fields\RadioGroup;
use YousignApiClient\Fields\TextField;
use YousignApiClient\Webhook\Webhook;

class YousignApiClient
{
    private string $apikey = '';
    private string $apiBaseUrl;
    private array $signers = [];
    private array $approvers = [];
    private array $documents = [];
    private SignatureRequest $signatureRequest;

    public function __construct($apikey, $env)
    {
        $this->setApikey($apikey);
        if ($env === 'prod') {
            $this->apiBaseUrl = 'https://api.yousign.com/v3/';
        } else {
            $this->apiBaseUrl = 'https://api-sandbox.yousign.app/v3/';
        }
    }

    public function setApikey($apikey): void
    {
        $this->apikey = $apikey;
    }

    public function getApikey(): string
    {
        return $this->apikey;
    }

    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

    public function setApiBaseUrl(string $apiBaseUrl): YousignApiClient
    {
        $this->apiBaseUrl = $apiBaseUrl;
        return $this;
    }

    public function getSigners(): array
    {
        return $this->signers;
    }

    public function addSignerToArray(Signer $signer): self
    {
        if (!in_array($signer, $this->signers, true)) {
            $this->signers[] = $signer;
        }
        return $this;
    }

    public function getApprovers(): array
    {
        return $this->approvers;
    }

    public function addApproverToArray(Approver $approver): self
    {
        if (!in_array($approver, $this->approvers, true)) {
            $this->approvers[] = $approver;
        }
        return $this;
    }

    public function getDocuments(): array
    {
        return $this->documents;
    }

    public function addDocumentToArray(Document $document): self
    {
        if (!in_array($document, $this->documents, true)) {
            $this->documents[] = $document;
        }
        return $this;
    }

    public function getSignatureRequest(): SignatureRequest
    {
        return $this->signatureRequest;
    }

    public function setSignatureRequest(SignatureRequest $signatureRequest): YousignApiClient
    {
        $this->signatureRequest = $signatureRequest;
        return $this;
    }


    public function initiateSignatureRequest(SignatureRequest $signatureRequest): SignatureRequest
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => sprintf('%s/signature_requests', $this->getApiBaseUrl()),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $signatureRequest->toJson(),
                CURLOPT_HTTPHEADER => [
                    sprintf('Authorization: Bearer %s', $this->getApikey()),
                    'Content-Type: application/json'
                ],
            ]
        );
        $initiatedSignatureRequestResponse = curl_exec($ch);
        $signatureRequestResponseArray = json_decode($initiatedSignatureRequestResponse, true, 512);
        curl_close($ch);

        if (isset($signatureRequestResponseArray['id'])) {
            $signatureRequest->setId($signatureRequestResponseArray['id']);
            $this->setSignatureRequest($signatureRequest);
            return $signatureRequest;
        } else {
            throw new \RuntimeException('Failed to initiate signature request: ' . $initiatedSignatureRequestResponse);
        }
    }


    public function addDocument(Document $document): Document
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => sprintf('%s/signature_requests/%s/documents', $this->getApiBaseUrl(), $this->getSignatureRequest()->getId()),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => [
                    'file' => new CURLFile($document->getPath(), 'application/pdf'),
                    'nature' => $document->getNature(),
                    "parse_anchors" => $document->getParseAnchors()
                ],
                CURLOPT_HTTPHEADER => [
                    sprintf('Authorization: Bearer %s', $this->getApikey()),
                    'Content-Type: multipart/form-data'
                ],
            ]);

        $documentUploadedResponse = curl_exec($ch);
        $documentResponseArray = json_decode($documentUploadedResponse, true, 512);
        curl_close($ch);

        if (isset($documentResponseArray['id'])) {
            $document->setId($documentResponseArray['id']);
            $this->addDocumentToArray($document);
            return $document;
        } else {
            throw new \RuntimeException('Failed to add document: ' . $documentUploadedResponse);
        }
    }


    public function addSigner(Signer $signer): Signer
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => sprintf('%s/signature_requests/%s/signers', $this->getApiBaseUrl(), $this->getSignatureRequest()->getId()),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $signer->toJson(),
                CURLOPT_HTTPHEADER => [
                    sprintf('Authorization: Bearer %s', $this->getApikey()),
                    'Content-Type: application/json'
                ],
            ]
        );
        $response = curl_exec($ch);
        $responseArray = json_decode($response, true, 512);
        curl_close($ch);

        if (isset($responseArray['id'])) {
            $signer->setId($responseArray['id']);
            $this->addSignerToArray($signer);
            return $signer;
        } else {
            throw new \RuntimeException('Failed to add signer: ' . $response);
        }
    }

    public function addApprover(Approver $approver): Approver
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => sprintf('%s/signature_requests/%s/approvers', $this->getApiBaseUrl(), $this->getSignatureRequest()->getId()),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => '{"signer_id":"c7dc68e1-e171-421d-815e-86a04af3fa47"}',
                CURLOPT_HTTPHEADER => [
                    sprintf('Authorization: Bearer %s', $this->getApikey()),
                    'Content-Type: application/json'
                ],
            ]
        );
        $response = curl_exec($ch);
        $responseArray = json_decode($response, true, 512);
        curl_close($ch);

        if (isset($responseArray['id'])) {
            $approver->setId($responseArray['id']);
            $this->addApproverToArray($approver);
            return $approver;
        } else {
            throw new \RuntimeException('Failed to add approver: ' . $response);
        }
    }

    public function sendSignatureRequest()
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => sprintf('%s/signature_requests/%s/activate', $this->getApiBaseUrl(), $this->getSignatureRequest()->getId()),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    sprintf('Authorization: Bearer %s', $this->getApikey()),
                    'Content-Type: application/json'
                ],
            ]);

        $activatedSignatureRequestResponse = curl_exec($ch);
        $responseArray = json_decode($activatedSignatureRequestResponse, true);
        curl_close($ch);
        //TODO: faire mieux
        if (isset($responseArray['signers'][0]['signature_link'])) {
            // Si un lien de signature est retourné dans la réponse, cela signifie que la demande de signature a été envoyée avec succès
            $signer1MagicLink = $responseArray['signers'][0]['signature_link'];
            return $signer1MagicLink;
        } else {
            // Sinon, il y a eu une erreur lors de l'envoi de la demande de signature
            throw new \RuntimeException('Failed to send signature request: ' . $activatedSignatureRequestResponse);
        }
    }

    public function addMentionField(MentionField $mentionField): void
    {
        $response = $this->addField($mentionField);
        $responseArray = json_decode($response, true, 512);

        if (!isset($responseArray['id'])) {
            throw new \RuntimeException('Failed to add mention field: ' . $response);
        }
    }

    public function addTextField(TextField $textField): void
    {
        $response = $this->addField($textField);
        $responseArray = json_decode($response, true, 512);
        if (!isset($responseArray['id'])) {
            throw new \RuntimeException('Failed to add text field: ' . $response);
        }
    }

    public function addCheckboxField(CheckboxField $checkboxField): void
    {
        $response = $this->addField($checkboxField);
        $responseArray = json_decode($response, true, 512);
        if (!isset($responseArray['id'])) {
            throw new \RuntimeException('Failed to add checkbox field: ' . $response);
        }
    }

    public function addRadioGroup(RadioGroup $radioGroup): void
    {
        $response = $this->addField($radioGroup);
        $responseArray = json_decode($response, true, 512);
        if (!isset($responseArray['id'])) {

            throw new \RuntimeException('Failed to add radio group: ' . $response);
        }
    }

    private function addField(RadioGroup|Field $field): bool|string
    {
        $requestBodyPayload = $field->toJson();
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => sprintf('%s/signature_requests/%s/documents/%s/fields', $this->getApiBaseUrl(), $this->getSignatureRequest()->getId(), $field->getDocumentId()),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $requestBodyPayload,
                CURLOPT_HTTPHEADER => [
                    sprintf('Authorization: Bearer %s', $this->getApikey()),
                    'Content-Type: application/json'
                ],
            ]);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function downloadAuditTrail(Signer $signer)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => sprintf('%s/signature_requests/%s/signers/%s/audit_trails/download', $this->getApiBaseUrl(), $this->signatureRequest->getId(), $signer->getId()),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    sprintf('Authorization: Bearer %s', $this->getApikey()),
                    'accept: application/pdf'
                ),
            ]
        );
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function listWebhooks(): bool|string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf('%s/webhooks', $this->getApiBaseUrl()),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                sprintf('Authorization: Bearer %s', $this->getApikey()),
                'Content-Type: application/json'
            ],
        ));

        return curl_exec($curl);
        curl_close($curl);

    }

    public function createWebhook(Webhook $webhook): Webhook
    {
        $requestBodyPayload = $webhook->toJson();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf('%s/webhooks', $this->getApiBaseUrl()),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $requestBodyPayload,
            CURLOPT_HTTPHEADER => [
                sprintf('Authorization: Bearer %s', $this->getApikey()),
                'Content-Type: application/json'
            ],
        ));

        $response = curl_exec($curl);
        $responseArray = json_decode($response, true);
        if (!isset($responseArray['id'])) {
            throw new \RuntimeException('Failed to create the webhook: ' . $response);
        }

        $webhook->setId($responseArray['id']);
        $webhook->setSecretKey($responseArray['secret_key']);

        curl_close($curl);

        return $webhook;
    }

    public function sendManualReminder(string $signatureRequestId, string $signerId) : string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf('%s/signature_requests/%s/signers/%s/send_reminder', $this->getApiBaseUrl(), $signatureRequestId, $signerId),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                sprintf('Authorization: Bearer %s', $this->getApikey()),
                'Content-Type: application/json'
            ],
        ));

        curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($status_code === 201) {
            $result = array(
                'success' => true,
                'message' => 'Reminder sent successfully.'
            );
        } else {
            $result = array(
                'success' => false,
                'error_code' => $status_code,
                'error_message' => $this->getErrorMessage($status_code)
            );
        }

        return json_encode($result);
    }

    public function cancelRequest(CancellationRequest $cancelingRequest) : string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf('%s/signature_requests/%s/cancel', $this->getApiBaseUrl(), $cancelingRequest->getSignatureRequestId()),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $cancelingRequest->toJson(),
            CURLOPT_HTTPHEADER => [
                sprintf('Authorization: Bearer %s', $this->getApikey()),
                'Content-Type: application/json'
            ],
        ));

        curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($status_code === 201) {
            $result = array(
                'success' => true,
                'message' => 'Request canceled successfully.'
            );
        } else {
            $result = array(
                'success' => false,
                'error_code' => $status_code,
                'error_message' => $this->getErrorMessage($status_code)
            );
        }

        return json_encode($result);
    }

    private function getErrorMessage(int $status_code): string
    {
        return match ($status_code) {
            400 => "Bad request - The server cannot process the request due to a client error.",
            401 => "Access unauthorized - Authentication is required and has failed or has not yet been provided.",
            403 => "Access forbidden - The server understood the request but refuses to authorize it.",
            404 => "Resource not found - The requested resource could not be found.",
            default => "Unexpected response from the server with status code: $status_code",
        };
    }
}
