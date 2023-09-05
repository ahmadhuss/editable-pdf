<?php
namespace Leekim;

require_once __DIR__ . '/vendor/autoload.php'; // Load Composer autoload

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TCPDF;
use TCPDF_FONTS;

// Function to convert HTML to PDF
function convertHtmlToPdf($htmlContent, $outputFilename) {

    // TCPDF constants:
    // PDF_PAGE_ORIENTATION: The page orientation, which can be P for portrait or L for landscape.
    // PDF_UNIT: The unit of measurement, which can be mm, cm, in, or pt.
    // PDF_PAGE_FORMAT: The page format A4, which can be one of the predefined values in the PDF_PAGE_FORMATS array.
    // TRUE: Whether to create a header and footer.
    // 'UTF-8': The character encoding.
    //  FALSE: Whether to subset fonts.
    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
    $font_name = TCPDF_FONTS::addTTFfont(getcwd() . '/ArialMT.ttf', 'TrueTypeUnicode', '', 32);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);    
    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);
    $tcpdf->SetDefaultMonospacedFont($font_name);
    $tcpdf->SetMargins(10, 8, 10);
    $tcpdf->SetHeaderMargin(5);
    $tcpdf->SetFooterMargin(5);
    // $tcpdf->setFontSubsetting(false);

    $tcpdf->SetAutoPageBreak(TRUE, 0);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $tcpdf->SetCreator(PDF_CREATOR);
    $tcpdf->SetTitle('Visa Authorisation Form');
    $tcpdf->SetSubject('Visa Authorisation Form');
    $tcpdf->SetKeywords('Visa Authorisation Form');
    $tcpdf->addPage();
    $tcpdf->SetFont($font_name, '', 10);
    $tcpdf->writeHTML($htmlContent, TRUE, 0, TRUE, TRUE);
    $tcpdf->Output($outputFilename, 'D');
}

// API endpoint
$request = Request::createFromGlobals();
if ($request->isMethod('POST')) {
    // Get JSON content from the request body
    // $jsonData = json_decode($request->getContent(), true);
    // $htmlContent = $request->request->get('html');
    $htmlContent = $request->getContent();




    // Check if 'html' key exists in JSON data
    // if (isset($jsonData['html'])) {
        // $htmlContent = $jsonData['html'];


        $pdfFilename = getcwd() . '/output_' . time() . '.pdf';
        convertHtmlToPdf($htmlContent, $pdfFilename);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $pdfFilename . '"');
        $response->setContent(file_get_contents($pdfFilename));
        unlink($pdfFilename);

    // } else {
    //     $response = new Response('Invalid JSON format or missing "html" key', Response::HTTP_BAD_REQUEST);
    //     $response->send();
    // }
} else {
    $response = new Response('Invalid request method', Response::HTTP_BAD_REQUEST);
}
$response->send();

