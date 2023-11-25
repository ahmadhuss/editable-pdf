<?php

namespace Leekim;

require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


// API endpoint
$request = Request::createFromGlobals();
if ($request->isMethod('GET')) {

    $data = [
        'company_name' => $request->query->get('company_name', ''),
        'uen' => $request->query->get('uen', ''),
        'address' => $request->query->get('address', ''),
        // Director and Secretary
        'director' => $request->query->get('director', ''),
        'secretary' => $request->query->get('secretary', ''),
        'director_id' => $request->query->get('director_id', ''),
        'secretary_id' => $request->query->get('secretary_id', ''),
        // Phone
        'phone' => $request->query->get('phone', ''),
        // Applicant
        'applicant' => $request->query->get('applicant', ''),
        'applicant_fin' => $request->query->get('applicant_fin', ''),
        'visa_type' => $request->query->get('visa_type', '')
    ];

    // Load Twig
    $loader = new FilesystemLoader(__DIR__ . '/../templates'); // Adjust the path to your templates directory
    $twig = new Environment($loader);

    // Render the first Twig template to generate HTML content
    $templatePage1 = $twig->load('lka-visa-authorisation-pdf.html.twig');
    $htmlContentPage1 = $templatePage1->render($data);

    // Render the second Twig template to generate HTML content
    $templatePage2 = $twig->load('lka-visa-authorisation-page-2-pdf.html.twig');
    $htmlContentPage2 = $templatePage2->render($data);

    $pdfFilename = getcwd() . '/output_' . time() . '.pdf';

    // Call the function to generate the PDF with both pages
    convertHtmlToPdf([$htmlContentPage1, $htmlContentPage2], $pdfFilename);

    // Create a BinaryFileResponse to send the PDF
    $response = new BinaryFileResponse($pdfFilename);

    // Set response headers to open the PDF in Chrome PDF viewer
    $response->headers->set('Content-Type', 'application/pdf');
    $response->setContentDisposition(
        ResponseHeaderBag::DISPOSITION_INLINE,
        $pdfFilename
    );

    // Optionally, remove the file after sending
    $response->deleteFileAfterSend(true);
} else {
    $response = new Response('Invalid request method', Response::HTTP_BAD_REQUEST);
}

$response->send();

