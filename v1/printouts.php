<?php

namespace Leekim;

require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


function generateDocument($group_id, $document_id, $data, $htmlContentPage, $pdfFilename)
{
    // $docs_ids = explode("_", $document_id);
    // $group_id = $docs_ids[0];
    $doc_id = $document_id;
    switch ($group_id) {
        case 0:
            if ($doc_id == 0) {
                $pdf = companyDetailInternalPdf($htmlContentPage, $pdfFilename);
            } elseif ($doc_id == 1) {
                $pdf = companyDetailCustomerPdf($htmlContentPage, $pdfFilename);
            }
            break;

        case 1:
            if ($doc_id == '00') {
                $pdf = constitutionPdf($htmlContentPage, $pdfFilename);
            } elseif ($doc_id == '01') {
                $pdf = constitutionPdf_1($htmlContentPage, $pdfFilename);
            } elseif ($doc_id == '02') {
                $pdf = constitutionLimitedByGuaranteePdf($htmlContentPage, $pdfFilename);
            }
            break;

        case 2:
            if ($doc_id == '00') {
                $pdf = directorResolutionForIncorporationPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '01') {
                $pdf = directorResolutionLimitedByGuaranteePdf($htmlContentPage , $pdfFilename);
            }
            break;

        case '3':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = formDirectorParticularPdf($htmlContentPage , $pdfFilename);
            break;

        case '03S':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = formShareholderParticularPdf($htmlContentPage , $pdfFilename);
            break;

        case '03C':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = formCompanyParticularPdf($htmlContentPage , $pdfFilename);
            break;

        case 4:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            if ($doc_id == 'SFA') {
                $pdf = serviceIndemnityAgreementSFA($node, $docs_ids[2], $group_id, $type);
                break;
            } else {
                $pdf = form45Pdf($htmlContentPage , $pdfFilename);
                break;
            }
        case 5:
            if ($doc_id == '1I1D') {
                //   $officers = $node->get('field_director_shareholder')
                //     ->referencedEntities();
                $pdf = serviceIndemnityAgreementPdfTrustDeed($htmlContentPage , $pdfFilename);
                break;
            }
            if ($doc_id == '00') {
                //   $officers = $node->get('field_director_shareholder')$content
                //     ->referencedEntities();
                $pdf = serviceIndemnityAgreement1D2D($htmlContentPage , $pdfFilename);
                break;
            }
            if ($doc_id == '1D1S') {
                //   $officers = $node->get('field_director_shareholder')
                //     ->referencedEntities();
                $pdf = serviceIndemnityAgreement1D2S($htmlContentPage , $pdfFilename);
                break;
            }
            if ($doc_id == 'SFA') {
                $pdf = serviceIndemnityAgreementSFA($htmlContentPage , $pdfFilename);
                break;
            }
        case 6:
            $pdf = allotmentOfShareForm24Pdf($htmlContentPage , $pdfFilename);
            break;

        case 7:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = applicationForSharesPdf($htmlContentPage, $pdfFilename);
            break;

        case 8:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = form45BPdf($htmlContentPage , $pdfFilename);
            break;

        case 9:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            if ($doc_id == '00') {
                $pdf = certificateForSHolderCSealPdf($htmlContentPage, $pdfFilename);
            } elseif ($doc_id == '01') {
                $pdf = certificateForSHolderPdf($htmlContentPage, $pdfFilename);
            }
            break;

        case 10:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = changeOfOfficeAddressPdf($htmlContentPage , $pdfFilename);
            break;

        case '30':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = customerAcceptancePdf($htmlContentPage , $pdfFilename);
            break;

        case '30S':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = customerAcceptanceShareholderPdf($htmlContentPage , $pdfFilename);
            break;

        case '30f':
        case '30Sf':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = customerAcceptanceLimitedByGuaranteePdf($htmlContentPage , $pdfFilename);
            break;

        case 12:
            if ($doc_id == '21') {
                $pdf = terminationOfCorporateSecretarialServicesPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '22') {
                $pdf = resignationsAndAppointmentOfCompanySecretaryPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '23') {
                $pdf = changeOfFinancialYearPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '24') {
                $pdf = changeOfCompanyNamePdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '25') {
                $pdf = changeOfBusinessActivityPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '26') {
                $pdf = certificateOfCorporateSealPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '27') {
                $pdf = changeOfRegisteredOfficeAddressPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '28') {
                $pdf = changeOfRegisteredOfficeAddressPdf($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '29') {
                $pdf = changeOfRegisteredOfficeAddressPdf($htmlContentPage , $pdfFilename);
            }
            break;

        case '29A':
            $pdf = noticeForControllerAPdf($htmlContentPage , $pdfFilename);
            break;

        case '13BI':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = annexBIPdf($htmlContentPage , $pdfFilename);
            break;

        case '13BE':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = annexBEPdf($htmlContentPage , $pdfFilename);
            break;

        case '13BIS':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = annexBISPdf($htmlContentPage , $pdfFilename);
            break;

        case '13BES':
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = annexBeSPdf($htmlContentPage , $pdfFilename);
            break;

        case 28:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = certificateOfEmployment($htmlContentPage , $pdfFilename);
            break;
        case 31:
            if ($doc_id == 'S') {
                //   $officers = $node->get('field_nominator_shareholder')->referencedEntities();
                //   $officer = $officers[$docs_ids[2]] ?? null;
                $pdf = registrationOfNomineeShareholder($node, $officer, $type);
            } else if ($doc_id == 'S20') {
                $pdf = confirmationLetter($htmlContentPage , $pdfFilename);
            } else if ($doc_id == 'S20B') {
                $pdf = confirmationLetterWithBlanks($htmlContentPage , $pdfFilename);
            } else {
                $officers = $node->get('field_director_shareholder')->referencedEntities();
                $pdf = registrationOfNomineeDirector($node, $officers[$docs_ids[1]], $type);
            }
            break;

        case 33:
            if ($group_id == '33' && $doc_id == '04') {
                $pdf = agmPdf04($htmlContentPage , $pdfFilename);
            } else {
                $pdf = agmPdf($htmlContentPage , $pdfFilename);
            }
            break;
        case 34:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            if ($doc_id == '01') {
                $pdf = drAllotmentOfShares($htmlContentPage, $pdfFilename);
            } else {
                $pdf = drTransferOfShares($htmlContentPage , $pdfFilename);
            }
            break;
        case 35:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            if ($group_id == '35i') {
                $pdf = interimDividends($htmlContentPage , $pdfFilename);
            } else {
                $pdf = dividendsStatement($htmlContentPage , $pdfFilename);
            }
            break;
        case 36:
            if ($doc_id == '00') {
                $pdf = drWritingOffOfInvestment($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '01') {
                $pdf = drStrikingOff($htmlContentPage, $pdfFilename);
            } else {
                $pdf = drDivestment($htmlContentPage , $pdfFilename);
            }
            break;
        case 40:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            if ($doc_id == '00') {
                $pdf = changeOfDirectors($htmlContentPage, $pdfFilename);
            } elseif ($doc_id == '01') {
                $pdf = drChangeOfDirectors($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '02') {
                $pdf = form45ChangeOfDirectors($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == '03') {
                $pdf = formParticularChangeOfDirectors($htmlContentPage , $pdfFilename);
            } else {
                $pdf = resignationChangeOfDirectors($htmlContentPage , $pdfFilename);
            }
            break;
        case 41:
            $pdf = registerOfDirectors($htmlContentPage , $pdfFilename);
            break;
        case 42:
            $pdf = registerOfSecretaries($htmlContentPage , $pdfFilename);
            break;
        case 43:
            $pdf = registerOfApplications($htmlContentPage , $pdfFilename);
            break;
        case 44:
            $pdf = registerOfTransfers($htmlContentPage , $pdfFilename);
            break;
        case 45:
            // $officers = $node->get('field_director_shareholder')
            //   ->referencedEntities();
            $pdf = registerOfMembers($htmlContentPage , $pdfFilename);
            break;
        case 46:
            if ($doc_id == 01) {
                $pdf = appointmentOfAuditors($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == 02) {
                $pdf = consentToAct($htmlContentPage , $pdfFilename);
            } elseif ($doc_id == 03) {
                $pdf = engagementLetter($htmlContentPage , $pdfFilename);
            }
            break;
    }
    if (!empty($pdf)) {
        return $pdf;
    } else {
        return NULL;
    }
}


// API endpoint
$request = Request::createFromGlobals();
if ($request->isMethod('POST')) {
    // Get the raw JSON content from the request
    $json = $request->getContent();

    // Decode the JSON data
    $content = json_decode($json, true);
    $final_content = json_decode($content, true);

    // Check if the JSON decoding was successful
    // if (json_last_error() === JSON_ERROR_NONE) {
        // Access individual parameters
        $templateName = $final_content['template_name'];
        $groupId = $final_content['group_id'];
        $documentId = $final_content['document_id'];

        $data = [
            'app' => $final_content["app"] ?? null,
            'officer' => $final_content["officer"] ?? null
        ];

        // Load Twig
        $loader = new FilesystemLoader(__DIR__ . '/../templates/printouts'); // Adjust the path to your templates directory
        $twig = new Environment($loader);

        // Render the first Twig template to generate HTML content
        $templatePage1 = $twig->load($templateName);

        $htmlContentPage = $templatePage1->render($data);
        $pdfFilename = getcwd() . '/output_' . time() . '.pdf';


        generateDocument($groupId, $documentId, $data, $htmlContentPage, $pdfFilename);

        // Create a BinaryFileResponse to send the PDF
        $response = new BinaryFileResponse($pdfFilename);

        // Set response headers to open the PDF in Chrome PDF viewer
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $pdfFilename
        );

        // Optionally, remove the file after sending
        // $response->deleteFileAfterSend(true);
    // } else {
    //     // Handle JSON decoding error
    //     $errorMessage = 'Error decoding JSON: ' . json_last_error_msg();
    //     // Return an error response
    //     $response = new Response($errorMessage, Response::HTTP_BAD_REQUEST);
    //     // Return the response
    //     return $response;
    // }
} else {
    $response = new Response('Invalid request method', Response::HTTP_BAD_REQUEST);
}

$response->send();

