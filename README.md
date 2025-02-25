## CyrilBochet/YousignApiClient

### README translation

- [English](README.en.md)

> Client API pour <a target="_blank" href="https://yousign.com/fr-fr"> Yousign</a> · solution de signature électronique
> française.

> Toute contribution à l'amélioration est fortement apprécié !

### Sommaire

- [Initialisation de la requête](#signature-request)
- [Ajout d'un document](#add-doc)
- [Ajout d'un signataire](#add-signer)
- [Mettre à jour les informations d'un signataire](#update-signer)
- [Ajout des champs](#add-fields)
- [Envoi de la signature](#send-request)
- [Récupération du document signé](#download-signed-document)
- [Annuler une requête de signature](#cancel-request)
- [Liens utiles](#useful-links)

<div id='signature-request'/></div>

### Initialisation de la requête

 ```PHP
use YousignApiClient\YousignApiClient;
use YousignApiClient\SignatureRequest;

$apikey = 'API_KEY';
$env = 'test';

$client = new YousignApiClient($apikey, $env);

// Nouvelle requête
$signatureRequest = new SignatureRequest(string $name, string $deliveryMode, string $timezone, bool $orderedSigners);

$signatureRequest = new SignatureRequest('Contrat', 'email', 'Europe/Paris', true);

// Optionel
$signatureRequest->setCustomExperienceId("xxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxxx");

$client->initiateSignatureRequest($signatureRequest);
```

<div id='add-doc'/></div>

### Création et ajout d'un document

```PHP
use YousignApiClient\Document;

$document = new Document(string $nature, string $path, bool $parseAnchors);

$document = new Document('signable_document', 'contrat.pdf', true);
$client->addDocument($document);
$documentId = $document->getId();
```

<div id='add-signer'/></div>

### Création d'un objet signataire

```PHP
use YousignApiClient\Signer;
$signer = new Signer(string $firstName, string $lastName, string $email, ?string $phoneNumber, string $locale, ?string $signatureAuthenticationMode, ?string $signatureLevel);
$signer = new Signer("Jean", "DUPOND", 'j.dupond@mail.fr', '+33123456789', 'fr', 'otp_sms', 'electronic_signature');
```

### Paramétrage de la police

```PHP
use YousignApiClient\Font;

$font = new Font(string $family, string $color, int $size = 12);
$font = new Font('Raleway', '#000000', 9);
```

<div id='update-signer'/></div>

### Mettre à jour les informations d'un signataire

 ```PHP
use YousignApiClient\YousignApiClient;

$client = new YousignApiClient($apikey, $env);
$client->updateSignerInformation(string $signatureRequestId, string $signerId, string $firstName, string $lastName, string $email, string $phoneNumber)
$client->updateSignerInformation('xxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxx', 'Jean', 'DUPOND', 'j.dupond@mail.fr', '+33123456789');


```

<div id='add-fields'/></div>

### Création et ajout des champs

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

$textField = new TextField(10, 'Votre prénom :', false, $font, 'xxxxxxxxxxxxxx', 1, 468, 428, 24, 54);
$signer->addField($textField);

// Checkbox
$cb = new CheckboxField($size, $name, $checked, $optional, $documentId, $page, $x, $y);
$signer->addField($cb);

// Pour les documents avec beaucoup de champs, vous pouvez faire un tableau et boucler dessus
$mentionsArray = array(
    "signataire1" => array(
        array(
            "page" => 2,
            "x" => 57,
            "y" => 675,
            "width" => 197,
            "height" => 24,
            "mention" => "Certifié exact et sincère",
        ),
        array(
            "page" => 14,
            "x" => 35,
            "y" => 318,
            "width" => 156,
            "height" => 24,
            "mention" => "Bon pour acceptation",
        ),
    ),
    "signataire2" => array(
        array(
            "page" => 14,
            "x" => 206,
            "y" => 318,
            "width" => 156,
            "height" => 24,
            "mention" => "Certifié exact et sincère",
        ),
        array(
            "page" => 2,
            "x" => 297,
            "y" => 675,
            "width" => 197,
            "height" => 24,
            "mention" => "Bon pour acceptation",
        ),
    ),
);

  // Exemple : On boucle sur les champs mentions du signataire 2
  foreach ($mentionsArray["signataire2"] as $mention) {
    $mf = new MentionField($mention["mention"], $font, $documentId, $mention["page"], $mention["x"], $mention["y"], $mention["height"], $mention["width"]);
    $signer2->addField($mf);
  }

```

<div id='send-request'/></div>

### Ajout des signataires et envoi de la signature

```PHP
 $client->addSigner($signer);
 $client->sendSignatureRequest();
```

<div id='webhooks'/></div>

### Webhooks

 ```PHP
use YousignApiClient\Webhook\Scope;
use YousignApiClient\Webhook\Webhook;
use YousignApiClient\Webhook\WebhookEvent;

// Nouveau Webhook

$allEvents = new WebhookEvent('*');
$event = new WebhookEvent('signature_request.done');

$allScopes = new Scope('*');
$scope = new Scope('public_api');

$webhook = new Webhook(bool $sandbox, bool $autoRetry, bool $enabled, string $endpoint, string $description, array $events, array $scopes);
$webhook = new Webhook(true, true, true, 'endpoint.fr', 'test', [$allEvents], [$allScopes]);

$client->createWebhook($webhook);

// Liste des Webhooks
$webhooks = $client->listWebhooks();

```

<div id='download-signed-document'/></div>

### Récupérer le document signé

 ```PHP

$response = $client->downloadSignedDocument(string $signatureRequestId);
$response = $client->downloadSignedDocument('xxxxxxxxxxxxxxxx');
$file = fopen('path/to/file.pdf', 'w');
fwrite($file, $response);
fclose($file);

```

<div id='cancel-request'/></div>

### Annuler une requête de signature

 ```PHP
use YousignApiClient\CancellationRequest;

// Nouvelle requête d'annulation

$cancellationRequest = new CancellationRequest(string $reason, string $signatureRequestId, ?string $customNote = null);
$cancellationRequest = new CancellationRequest('other', 'xxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxxx', 'Note personnalisée');
$client->cancelRequest($cancellationRequest);

```

<div id='useful-links'/></div>

### Liens utiles

> Pour récupérer les coordonnées d'un endroit sur le document : https://placeit.yousign.fr

> Documentation complète de l'API Yousign : https://developers.yousign.com/reference/oas-specification

