<?php

namespace Leekim;

require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function generateDocument($group_id, $document_id, $data, $htmlContentPage , $pdfFilename) {
    // $docs_ids = explode("_", $document_id);
    // $group_id = $docs_ids[0];
    $doc_id = $document_id;
    switch ($group_id) {
      case 0:
        if ($doc_id == 0) {
          $pdf = companyDetailInternalPdf($htmlContentPage, $pdfFilename);
        }
        elseif ($doc_id == 1) {
          $pdf = companyDetailCustomerPdf($htmlContentPage, $pdfFilename);
        }
        break;

      case 1:
        if ($doc_id == '00') {
          error_log('yahan to aata hai@!!!');          
          $pdf = constitutionPdf($htmlContentPage, $pdfFilename);
        }
        elseif ($doc_id == '01') {
          $pdf = constitutionPdf_1($htmlContentPage, $pdfFilename);
        }
        elseif ($doc_id == '02') {
          $pdf = constitutionLimitedByGuaranteePdf($htmlContentPage, $pdfFilename);
        }
        break;

      case 2:
        if ($doc_id == '00') {
          $pdf = directorResolutionForIncorporationPdf($node, $type);
        }
        elseif ($doc_id == '01') {
          $pdf = directorResolutionLimitedByGuaranteePdf($node, $type);
        }
        break;

      case '3':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = formDirectorParticularPdf($node, $officers[$doc_id], $type);
        break;

      case '03S':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = formShareholderParticularPdf($node, $officers[$doc_id], $type);
        break;

      case '03C':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = formCompanyParticularPdf($node, $officers[$doc_id], $type);
        break;

      case 4:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        if ($doc_id == 'SFA') {
          $pdf = serviceIndemnityAgreementSFA($node, $docs_ids[2], $group_id, $type);
          break;
        } else {
          $pdf = form45Pdf($node, $officers[$doc_id], $type);
          break;
        }
      case 5:
        if ($doc_id == '1I1D') {
        //   $officers = $node->get('field_director_shareholder')
        //     ->referencedEntities();
          $pdf = serviceIndemnityAgreementPdfTrustDeed($node, $officers[$docs_ids[2]], $type);
          break;
        }
        if ($doc_id == '00') {
        //   $officers = $node->get('field_director_shareholder')
        //     ->referencedEntities();
          $pdf = serviceIndemnityAgreement1D2D($node, $officers[$docs_ids[2]], $type);
          break;
        }
        if ($doc_id == '1D1S') {
        //   $officers = $node->get('field_director_shareholder')
        //     ->referencedEntities();
          $pdf = serviceIndemnityAgreement1D2S($node, $officers[$docs_ids[2]], $type);
          break;
        }
        if ($doc_id == 'SFA') {
          $pdf = serviceIndemnityAgreementSFA($node, $docs_ids[2], $group_id, $type);
          break;
        }
      case 6:
        $pdf = allotmentOfShareForm24Pdf($node, $type);
        break;

      case 7:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = applicationForSharesPdf($htmlContentPage, $pdfFilename);
        break;

      case 8:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = form45BPdf($node, $officers[$doc_id], $type);
        break;

      case 9:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        if ($doc_id == '00') {
          $pdf = certificateForSHolderCSealPdf($node, $officers[$docs_ids[2]], $type);
        }
        elseif ($doc_id == '01') {
          $pdf = certificateForSHolderPdf($node, $officers[$docs_ids[2]], $type);
        }
        break;

      case 10:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = changeOfOfficeAddressPdf($node, $officers[$doc_id], $type);
        break;

      case '30':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = customerAcceptancePdf($node, $officers[$doc_id], $type);
        break;

      case '30S':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = customerAcceptanceShareholderPdf($node, $officers[$doc_id], $type);
        break;

      case '30f':
      case '30Sf':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = customerAcceptanceLimitedByGuaranteePdf($node, $officers[$doc_id], $type);
        break;

      case 12:
        if ($doc_id == '21') {
          $pdf = terminationOfCorporateSecretarialServicesPdf($node, $type);
        }
        elseif ($doc_id == '22') {
          $pdf = resignationsAndAppointmentOfCompanySecretaryPdf($node, $type);
        }
        elseif ($doc_id == '23') {
          $pdf = changeOfFinancialYearPdf($node, $type);
        }
        elseif ($doc_id == '24') {
          $pdf = changeOfCompanyNamePdf($node, $type);
        }
        elseif ($doc_id == '25') {
          $pdf = changeOfBusinessActivityPdf($node, $type);
        }
        elseif ($doc_id == '26') {
          $pdf = certificateOfCorporateSealPdf($node, $type);
        }
        elseif ($doc_id == '27') {
          $pdf = changeOfRegisteredOfficeAddressPdf($node, $doc_id,  $type);
        }
        elseif ($doc_id == '28') {
          $pdf = changeOfRegisteredOfficeAddressPdf($node, $doc_id,  $type);
        }
        elseif ($doc_id == '29') {
          $pdf = changeOfRegisteredOfficeAddressPdf($node, $doc_id,  $type);
        }
        break;

      case '29A':
        $pdf = noticeForControllerAPdf($node, $type);
        break;

      case '13BI':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = annexBIPdf($node, $officers[$doc_id], $type);
        break;

      case '13BE':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = annexBEPdf($node, $officers[$doc_id], $type);
        break;

      case '13BIS':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = annexBISPdf($node, $officers[$doc_id], $type);
        break;

      case '13BES':
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = annexBeSPdf($node, $officers[$doc_id], $type);
        break;

      case 28:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = certificateOfEmployment($node, $officers[$doc_id], $type);
        break;
      case 31:
        if ($doc_id == 'S')  {
        //   $officers = $node->get('field_nominator_shareholder')->referencedEntities();
        //   $officer = $officers[$docs_ids[2]] ?? null;
          $pdf = registrationOfNomineeShareholder($node, $officer, $type);
        } else if ($doc_id == 'S20')  {
          $pdf = confirmationLetter($node, $type);
        } else if ($doc_id == 'S20B')  {
          $pdf = confirmationLetterWithBlanks($node, $type);
        } else {
          $officers = $node->get('field_director_shareholder')->referencedEntities();
          $pdf = registrationOfNomineeDirector($node, $officers[$docs_ids[1]], $type);
        }
        break;

      case 33:
        if ($group_id == '33' && $doc_id == '04') {
          $pdf = agmPdf04($node, $type);
        } else {
          $pdf = agmPdf($node, $doc_id, $type);
        }
        break;
      case 34:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        if ($group_id == '34a') {
          $pdf = drAllotmentOfShares($node, $officers[$doc_id], $type);
        } else {
          $pdf = drTransferOfShares($node, $officers[$doc_id], $type);
        }
        break;
      case 35:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        if ($group_id == '35i') {
          $pdf = interimDividends($node, $officers[$doc_id], $type);
        } else {
          $pdf = dividendsStatement($node, $officers[$doc_id], $type);
        }
        break;
      case 36:
        if ($doc_id == '00') {
          $pdf = drWritingOffOfInvestment($node, $type);
        } elseif($doc_id == '01') {
          $pdf = drStrikingOff($node, $type);
        } else {
          $pdf = drDivestment($node, $type);
        }
        break;
      case 40:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        if ($doc_id == '00') {
          $pdf = changeOfDirectors($node, $officers[$docs_ids[2]], $type);
        } elseif ($doc_id == '01') {
          $pdf = drChangeOfDirectors($node, $type);
        } elseif ($doc_id == '02') {
          $pdf = form45ChangeOfDirectors($node, $type);
        } elseif ($doc_id == '03') {
          $pdf = formParticularChangeOfDirectors($node, $type);
        } else {
          $pdf = resignationChangeOfDirectors($node, $type);
        }
        break;
      case 41:
        $pdf = registerOfDirectors($node, $type);
        break;
      case 42:
        $pdf = registerOfSecretaries($node, $type);
        break;
      case 43:
        $pdf = registerOfApplications($node, $type);
        break;
      case 44:
        $pdf = registerOfTransfers($node, $type);
        break;
      case 45:
        // $officers = $node->get('field_director_shareholder')
        //   ->referencedEntities();
        $pdf = registerOfMembers($node, $officers[$doc_id], $type);
        break;
      case 46:
        if ($doc_id == 01) {
          $pdf = appointmentOfAuditors($node, $type);
        } elseif ($doc_id == 02) {
          $pdf = consentToAct($node, $type);
        } elseif ($doc_id == 03) {
          $pdf = engagementLetter($node, $type);
        }
        break;
    }
    if (!empty($pdf)) {
      return $pdf;
    }
    else {
      return NULL;
    }
  }


// API endpoint
$request = Request::createFromGlobals();
if ($request->isMethod('POST')) {

    $content = json_decode($request->getContent(), true);

    $template_name =  $content['template_name'];
    $group_id =  $content['group_id'];
    $document_id =  $content['document_id'];

    // $officer = json_decode($request->request->get('officer'), true);
   
    $data = [
        'app' => $content["app"],
        'officer' => $content["officer"]
    ];


    // Load Twig
    $loader = new FilesystemLoader(__DIR__ . '/../templates/printouts'); // Adjust the path to your templates directory
    $twig = new Environment($loader);

    // Render the first Twig template to generate HTML content
    $templatePage1 = $twig->load($template_name);
    // error_log($templatePage1);
    $htmlContentPage = $templatePage1->render($data);

    error_log($htmlContentPage);


    // Render the second Twig template to generate HTML content
    // $templatePage2 = $twig->load('lka-visa-authorisation-page-2-pdf.html.twig');
    // $htmlContentPage2 = $templatePage2->render($data);

    $pdfFilename = getcwd() . '/output_' . time() . '.pdf';

    // Call the function to generate the PDF with both pages

    // if($template_name == "tcpdf-40-00.html.twig") {
    //     changeOfDirectors($htmlContentPage1, $pdfFilename);
    // }

    generateDocument($group_id, $document_id, $data, $htmlContentPage , $pdfFilename);
    


    
    // $template_function_mapping[$template_name]($htmlContentPage1, $pdfFilename);

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
} else {
    $response = new Response('Invalid request method', Response::HTTP_BAD_REQUEST);
}

$response->send();

