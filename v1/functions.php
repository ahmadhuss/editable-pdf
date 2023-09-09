<?php

namespace Leekim;

use TCPDF;
use TCPDF_FONTS;


/**
 * Converts HTML content to a PDF using TCPDF library.
 *
 * @param array $htmlContent The HTML content to convert to PDF. If an array is provided,
 *                                  each element will be treated as a separate page.
 * @param string $outputFilename The name of the output PDF file.
 *
 * @return void
 */
function convertHtmlToPdf(array $htmlContent, string $outputFilename)
{

    // TCPDF constants:
    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
    $arialFontName = TCPDF_FONTS::addTTFfont(getcwd() . '/../ArialMT.ttf', 'TrueTypeUnicode', '', 32);
    $brushSciFontName = TCPDF_FONTS::addTTFfont(getcwd() . '/../BRUSHSCI.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);
    // Set Two Fonts
    $tcpdf->SetDefaultMonospacedFont($arialFontName);
    $tcpdf->SetFont($brushSciFontName, '', 12);

    $tcpdf->SetMargins(10, 8, 10);
    $tcpdf->SetHeaderMargin(5);
    $tcpdf->SetFooterMargin(5);

    $tcpdf->SetAutoPageBreak(TRUE, 0);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $tcpdf->SetCreator(PDF_CREATOR);
    $tcpdf->SetTitle('Visa All Authorisation Form');
    $tcpdf->SetSubject('Visa All Authorisation Form');
    $tcpdf->SetKeywords('Visa All Authorisation Form');


    foreach ($htmlContent as $htmlPage) {
        // Add a new page for each HTML page
        $tcpdf->AddPage();

        // Write the HTML content for this page
        $tcpdf->writeHTML($htmlPage, TRUE, 0, TRUE, TRUE, '');
    }
    $tcpdf->Output($outputFilename, 'I');
}