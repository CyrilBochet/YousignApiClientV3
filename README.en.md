## CyrilBochet/YousignApiClient

> API Client for <a target="_blank" href="https://yousign.com/fr-fr"> Yousign</a> · French electronic signature solution.

> Any contribution to improvement is highly appreciated!

### Table of Contents

- [Request Initialization](#signature-request)
- [Adding a Document](#add-doc)
- [Adding a Signer](#add-signer)
- [Adding Fields](#add-fields)
- [Sending the Request](#send-request)
- [Retrieve the signed document](#download-signed-document)
- [Cancel a signature request](#cancel-request)
- [Useful Links](#useful-links)

<div id='signature-request'/></div>

### Request Initialization

 ```PHP
use YousignApiClient\YousignApiClient;
use YousignApiClient\SignatureRequest;

$apikey = 'API_KEY';
$env = 'test';

$client = new YousignApiClient($apikey, $env);

// Nouvelle requête
$signatureRequest = new SignatureRequest(string $name, string $deliveryMode, string $timezone, bool $orderedSigners);

$signatureRequest = new SignatureRequest('Contract', 'email', 'Europe/Paris', true);

// Optionel
$signatureRequest->setCustomExperienceId("xxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxxx");

$client->initiateSignatureRequest($signatureRequest);
```

<div id='add-doc'/></div>

### Creating and Adding a Document

```PHP
use YousignApiClient\Document;

$document = new Document(string $nature, string $path, bool $parseAnchors);

$document = new Document('signable_document', 'contract.pdf', true);
$client->addDocument($document);
$documentId = $document->getId();
```

<div id='add-signer'/></div>

### Creating a Signer Object

```PHP
use YousignApiClient\Signer;
$signer = new Signer(string $firstName, string $lastName, string $email, ?string $phoneNumber, string $locale, ?string $signatureAuthenticationMode, ?string $signatureLevel);
$signer = new Signer("Jean", "DUPOND", 'j.dupond@mail.fr', '+33123456789', 'fr', 'otp_sms', 'electronic_signature');
```

### Font Configuration

```PHP
use YousignApiClient\Font;

$font = new Font(string $family, string $color, int $size = 12);
$font = new Font('Raleway', '#000000', 9);
```

<div id='add-fields'/></div>

### Creating and Adding Fields

```PHP
use YousignApiClient\Fields\CheckboxField;
use YousignApiClient\Fields\MentionField;
use YousignApiClient\Fields\RadioField;
use YousignApiClient\Fields\RadioGroup;
use YousignApiClient\Fields\SignatureField;
use YousignApiClient\Fields\TextField;

// Signature 
$signatureField = new SignatureField(string $documentId, int $page, int $x, int $y, int $height = 37, int $width = 85);

$signatureField = new SignatureField('xxxxxxxxxxxxxxxxxx', 1, 57, 695, 85, 198);
$signer->addField($signatureField);

// Radio 
$radio1 = new RadioField(string $name, int $x, int $y, int $size);

$radio1 = new RadioField('radio_1', 154, 341, 12);
$radio2 = new RadioField('radio_2', 184, 341, 12);

$radioGroup = new RadioGroup(string $documentId, int $page, string $name, bool $optional, array $radios);

$radioGroup = new RadioGroup('xxxxxxxxxxxxxx', 1, 'radio_group_name', false, [$radio1, $radio2]);
$signer->addField($radioGroup);

// Text
$textField = new TextField(int $maxLength, string $question, bool $optional, Font $font, string $documentId, int $page, int $x, int $y, int $height = 24, int $width = 24);

$textField = new TextField(10, 'Your first name:', false, $font, 'xxxxxxxxxxxxxx', 1, 468, 428, 24, 54);
$signer->addField($textField);

// Checkbox
$cb = new CheckboxField($size, $name, $checked, $optional, $documentId, $page, $x, $y);
$signer->addField($cb);

// For documents with many fields, you can create an array and loop through it
$mentionsArray = array(
    "signer1" => array(
        array(
            "page" => 2,
            "x" => 57,
            "y" => 675,
            "width" => 197,
            "height" => 24,
            "mention" => "Certified true and accurate",
        ),
        array(
            "page" => 14,
            "x" => 35,
            "y" => 318,
            "width" => 156,
            "height" => 24,
            "mention" => "Agreed and signed",
        ),
    ),
    "signer2" => array(
        array(
            "page" => 14,
            "x" => 206,
            "y" => 318,
            "width" => 156,
            "height" => 24,
            "mention" => "Certified true and accurate",
        ),
        array(
            "page" => 2,
            "x" => 297,
            "y" => 675,
            "width" => 197,
            "height" => 24,
            "mention" => "Agreed and signed",
        ),
    ),
);

  // Example: Looping through the mentions fields of signer 2
  foreach ($mentionsArray["signer2"] as $mention) {
    $mf = new MentionField($mention["mention"], $font, $documentId, $mention["page"], $mention["x"], $mention["y"], $mention["height"], $mention["width"]);
    $signer2->addField($mf);
  }

```

<div id='webhooks'/></div>

### Webhooks

 ```PHP
use YousignApiClient\Webhook\Scope;
use YousignApiClient\Webhook\Webhook;
use YousignApiClient\Webhook\WebhookEvent;

// New Webhook

$allEvents = new WebhookEvent('*');
$event = new WebhookEvent('signature_request.done');

$allScopes = new Scope('*');
$scope = new Scope('public_api');

$webhook = new Webhook(bool $sandbox, bool $autoRetry, bool $enabled, string $endpoint, string $description, array $events, array $scopes);
$webhook = new Webhook(true, true, true, 'endpoint.fr', 'test', [$allEvents], [$allScopes]);

$client->createWebhook($webhook);

// Webhooks list
$webhooks = $client->listWebhooks();
```

<div id='send-request'/></div>

### Adding Signers and Sending the Signature Request

```PHP
 $client->addSigner($signer);
 $client->sendSignatureRequest();
```
<div id='download-signed-document'/></div>

### Retrieve the signed document

 ```PHP

$response = $client->downloadSignedDocument(string $signatureRequestId);
$response = $client->downloadSignedDocument('xxxxxxxxxxxxxxxx');
$file = fopen('path/to/file.pdf', 'w');
fwrite($file, $response);
fclose($file);

```
<div id='cancel-request'/></div>

### Cancel a signature request

 ```PHP
use YousignApiClient\CancellationRequest;

// New cancellation request

$cancellationRequest = new CancellationRequest(string $reason, string $signatureRequestId, ?string $customNote = null);
$cancellationRequest = new CancellationRequest('other', 'xxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxxx', 'Custom note');
$client->cancelRequest($cancellationRequest);

```

<div id='useful-links'/></div>

### Useful Links

> To retrieve the coordinates of a location on the document: https://placeit.yousign.fr

> Complete Yousign API Documentation: https://developers.yousign.com/reference/oas-specification

