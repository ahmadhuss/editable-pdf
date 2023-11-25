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

    $applicants = [];
    if (isset($_GET['applicants'])) {
        // The 'applicants' parameter is present in the query string
        // "[{'applicant_id': 'APPL-00001', 'applicant': 'Hamza Ali', 'fin_no': '123456', 'visa_type': 'Dependants Pass'}]"
        // Php Note:
        // The problem here is that single quotes (') are used for enclosing keys
        // and string values, which is not valid JSON. In JSON, double quotes (")
        // should be used for both keys and string values. Once you correct the
        // format, json_decode should be able to parse the JSON string correctly.
        $applicantsQueryParam = $_GET['applicants'];

        // Replace single quotes with double quotes
        $applicantsQueryParam = str_replace("'", '"', $applicantsQueryParam);

        // Convert the JSON string to a PHP array
        $applicantsArray = json_decode($applicantsQueryParam, true);

        // Check if $applicantsArray is not empty
        if (!empty($applicantsArray)) {
            $applicants = $applicantsArray;
        }
    }

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
        // Applicants
        'applicants' => $applicants
    ];

    // Load Twig
    $loader = new FilesystemLoader(__DIR__ . '/../templates'); // Adjust the path to your templates directory
    $twig = new Environment($loader);

    // Render the first Twig template to generate HTML content
    $templatePage1 = $twig->load('lka-visa-all-authorisation-pdf.html.twig');
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

