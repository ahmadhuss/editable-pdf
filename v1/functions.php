<?php

namespace Leekim;

use TCPDF;
use TCPDF_FONTS;


class PDF020 extends TCPDF
{
    public function Header()
    {
        $this->SetY(10);
        $this->SetX(170);                                                                // Position at 15 mm from bottom
        $this->SetFont('helvetica', 'I', 8);                        // Set font
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');// Page number
    }
}

class PDF020Footer extends TCPDF
{
    public function Footer()
    {
        $this->SetY(-15);                                                                // Position at 15 mm from bottom
        $this->SetFont('helvetica', 'I', 8);                        // Set font
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');// Page number
    }
}

class PDFAGMFooter extends \TCPDF
{
    private $customFooterText = "";
    private $pageNumber = [];

    /**
     * @param string $customFooterText
     */
    public function setCustomFooterText($customFooterText)
    {
        $this->customFooterText = $customFooterText;
    }

    /**
     * @param string $pageNumber
     */
    public function setpageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }

    public function Footer()
    {
        if (in_array($this->page, $this->pageNumber) || in_array(-1, $this->pageNumber)) {
            $this->SetY(-35);                                // Position at 15 mm from bottom
            $this->SetFont('times', '', 8);
            $this->writeHTML($this->customFooterText, false, true, false, true);
        }
    }
}

class PDFSFA extends \TCPDF
{

    private $customHeaderText = "";

    /**
     * @param string $customHeaderText
     */
    public function setCustomHeaderText($customHeaderText)
    {
        $this->customHeaderText = $customHeaderText;
    }

    // Custom page header
    public function Header()
    {
        $font_name = \TCPDF_FONTS::addTTFfont(getcwd() . '/static/font/malgunbd.ttf', 'TrueTypeUnicode', '', 32);
        $this->SetFont($font_name, '', 10);
        $this->writeHTML($this->customHeaderText, false, true, false, true);
    }

    // Custom page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetX(38);
        // Set font
        $this->SetFont('helvetica', 'B', 10);
        // Page number
        $this->Cell(0, 10, $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

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


function companyDetailInternalPdf($html_template, $pdf_name)
{
    // $html_template = [
    //   '#theme' => 'tcpdf_company_detail_html',
    //   '#datas' => DocumentWrapper::companyDetails($company),
    // ];
    // $html = $this->renderer->render($html_template);
    $tcpdf = new PDF020Footer(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle('CORPORATION_DETAILS_CUSTOMER');
    $tcpdf->SetSubject('CORPORATION_DETAILS_CUSTOMER');
    $tcpdf->SetKeywords('CORPORATION_DETAILS_CUSTOMER');

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(TRUE);
    $tcpdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->addPage();
    $tcpdf->writeHTML($html_template, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = $company->id() . '.' . $company->getTitle() . "_-_CORPORATION_DETAILS.pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => $company->id() . '.' . $company->getTitle() . "_-_CORPORATION_DETAILS",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, 'I');

}


function companyDetailCustomerPdf($html_template, $pdf_name)
{
    // $datas = DocumentWrapper::companyDetails($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_company_detail_html',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);
    ob_start();

    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $tcpdf->setPrintHeader(false);
    $tcpdf->setPrintFooter(false);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle('CORPORATION_DETAILS_CUSTOMER');
    // $tcpdf->SetSubject('CORPORATION_DETAILS_CUSTOMER');
    // $tcpdf->SetKeywords('CORPORATION_DETAILS_CUSTOMER');

    $tcpdf->AddPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_template, true, 0, true, true, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // Clean any content of the output buffer
    ob_end_clean();
    // $filename = 'CORPORATION_DETAILS_CUSTOMER.pdf';
    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => 'CORPORATION_DETAILS_CUSTOMER',
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, 'I');
}

function constitutionPdf($html_template, $pdf_name)
{
    // $datas = DocumentWrapper::constitution($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_01_00_constitution',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new PDF020(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(TRUE);
    $tcpdf->setPrintFooter(FALSE);
    // Set margins.
    $tcpdf->SetMargins(15, 17, 15);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle('CONSTITUTION');
    // $tcpdf->SetSubject('CONSTITUTION');
    // $tcpdf->SetKeywords('CONSTITUTION');

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_template, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();

    // The code for adding markup is omitted since it is part of the commented-out section.

    $tcpdf->lastPage();

    // $company_name = str_replace(' ', '_', $company->getTitle());
    // $company_type = str_replace(' ', '_', $company->get('field_company_type')
    //   ->referencedEntities()[0]->getName());
    // $filename = 'Constitution---' . $company_name . '_' . $company_type . '-' . date("dmY") . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => 'Constitution---' . $company_name . '_' . $company_type . '-' . date("dmY"),
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, 'I');
}

function constitutionPdf_1($html_template, $pdf_name)
{
    // $datas = DocumentWrapper::constitution($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_01_01_constitution',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new PDF020(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(TRUE);
    $tcpdf->setPrintFooter(FALSE);
    // Set margins.
    $tcpdf->SetMargins(15, 17, 15);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle('CONSTITUTION');
    // $tcpdf->SetSubject('CONSTITUTION');
    // $tcpdf->SetKeywords('CONSTITUTION');

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_template, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $company_name = str_replace(' ', '_', $company->getTitle());
    // $company_type = str_replace(' ', '_', $company->get('field_company_type')
    //   ->referencedEntities()[0]->getName());
    // $filename = 'Constitution---' . $company_name . '_' . $company_type . '-' . date("dmY") . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => 'Constitution---' . $company_name . '_' . $company_type . '-' . date("dmY"),
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, 'I');
}

function constitutionLimitedByGuaranteePdf($html_template, $pdf_name)
{
    // $datas = DocumentWrapper::constitution($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_01_02_constitution',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new PDF020(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(true);
    $tcpdf->setPrintFooter(false);
    // Set margins.
    $tcpdf->SetMargins(15, 17, 15);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle('CONSTITUTION');
    // $tcpdf->SetSubject('CONSTITUTION');
    // $tcpdf->SetKeywords('CONSTITUTION');

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_template, true, 0, true, true);
    $tcpdf->Ln();

    // Additional markup here if needed

    $tcpdf->lastPage();

    // $company_name = str_replace(' ', '_', $company->getTitle());
    // $company_type = str_replace(' ', '_', $company->get('field_company_type')->referencedEntities()[0]->getName());
    // $filename = 'GUARANTEE_CONSTITUTION_OF_' . $company_name . '_' . $company_type . '.pdf';

    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // } else {
    //   $data = [
    //     'client' => [],
    //     'title' => 'GUARANTEE_CONSTITUTION_OF_' . $company_name . '_' . $company_type,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, 'I');
}


function directorResolutionForIncorporationPdf($html_template, $pdf_name)
{
    // $datas = DocumentWrapper::directorResolution($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_02_00_director_resolution',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);
    // Set margins.
    $tcpdf->SetMargins(10, 30, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("DIRECTOR'S RESOLUTION FOR INCORPORATION");
    $tcpdf->SetSubject("DIRECTOR'S RESOLUTION FOR INCORPORATION");
    $tcpdf->SetKeywords("DIRECTOR'S RESOLUTION FOR INCORPORATION");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_template, TRUE, 0, TRUE, FALSE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '02_First_Director_Resolution_for_Incorporation.pdf';
    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '02_First_Director_Resolution_for_Incorporation',
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, 'I');
}


function directorResolutionLimitedByGuaranteePdf($html_template, $pdf_name, $author = 'Author')
{
    // Assuming the Helvetica font is placed correctly in the 'fonts' directory one level above.
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);
    $tcpdf->SetMargins(10, 17, 10);
    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->SetCreator($author);
    $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("DIRECTOR'S RESOLUTION LIMITED BY GUARANTEE");
    $tcpdf->SetSubject("DIRECTOR'S RESOLUTION LIMITED BY GUARANTEE");
    $tcpdf->SetKeywords("DIRECTOR'S RESOLUTION LIMITED BY GUARANTEE");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_template, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // Outputs the PDF to the browser or to a file depending on the specified $pdf_name.
    return $tcpdf->Output($pdf_name, 'I'); // Use 'S' for returning the PDF as a string, 'F' for saving it to a file.
}

function formDirectorParticularPdf($html_template, $pdf_name, $author = 'Author', $subject_suffix = '')
{
    // Load the Helvetica font from the specified path.
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);
    $tcpdf->SetMargins(10, 10, 10);
    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->SetCreator($author);
    $tcpdf->SetAuthor($author);
    $title = "FORM OF PARTICULARS-" . $subject_suffix;
    $tcpdf->SetTitle($title);
    $tcpdf->SetSubject($title);
    $tcpdf->SetKeywords($title);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_template, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // Replace spaces with underscores for the filename.
    $subject_suffix = str_replace(' ', '_', $subject_suffix);
    $filename = $pdf_name . '---' . $subject_suffix . '.pdf';

    // Output the PDF by specified type: 'I' for inline, 'D' for download, 'F' for saving on the server, etc.
    return $tcpdf->Output($filename, 'I'); // Change 'I' to the appropriate destination as needed.
}


function formCompanyParticularPdf($html_content, $pdf_name, $output_type = 'I')
{
    // Load the Helvetica font from the specified path.
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $tcpdf->setPrintHeader(false);
    $tcpdf->setPrintFooter(false);
    $tcpdf->SetMargins(10, 10, 10);
    $tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $author_name = '';
    $subject_suffix = '';
    $tcpdf->SetCreator($author_name);
    $tcpdf->SetAuthor($author_name);
    $title = "FORM OF PARTICULARS-" . $subject_suffix;
    $tcpdf->SetTitle($title);
    $tcpdf->SetSubject($title);
    $tcpdf->SetKeywords($title);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, true, 0, true, true);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    $subject_suffix = str_replace(' ', '_', $subject_suffix);
    $filename = $pdf_name . '---' . $subject_suffix . '.pdf';

    // Output the PDF by the specified type: 'I' for inline, 'D' for download, 'F' for saving on the server, etc.
    return $tcpdf->Output($filename, $output_type);
}

// Example usage:
// $html_content = ... // Your HTML content here
// $pdf_name = '03_D.Form_Of_Particulars';
// $author_name = ... // Get the author name from the context, not Drupal-specific
// $subject_suffix = ... // The full name of the officer
// $output_type = 'I'; // or 'D', 'F', etc., depending on the desired output

// $pdfOutput = formCompanyParticularPdf($html_content, $pdf_name, $output_type);

function form45Pdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::directorParticular($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_04_form_45',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(15, 10, 15);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("FORM 45 - " . $full_name);
    // $tcpdf->SetSubject("FORM 45 - " . $full_name);
    // $tcpdf->SetKeywords("FORM 45 - " . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);
    // $filename = '04_FORM_45---' . $full_name . '.pdf';
    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '04_FORM_45---' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);
}


function serviceIndemnityAgreementPdfTrustDeed($html_content, $pdf_name, $output_type = 'I')
{


    // $datas = DocumentWrapper::serviceIndemnityAgreementTrustDeed($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_05_trust_deed',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("TRUST DEED - " . $full_name);
    // $tcpdf->SetSubject("TRUST DEED - " . $full_name);
    // $tcpdf->SetKeywords("TRUST DEED - " . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '05_TRUST_DEED--' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['shareholder']['client'], $datas['nominee']['client'], $datas['secretary']['client']),
    //     'title' => '05_TRUST_DEED--' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

function allotmentOfShareForm24Pdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::form24($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_06_form24',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 5, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Use passed author name
    // $tcpdf->SetCreator($author_name);
    // $tcpdf->SetAuthor($author_name);
    // $tcpdf->SetTitle('FORM_24 - ' . $subject_suffix);
    // $tcpdf->SetSubject('FORM_24 - ' . $subject_suffix);
    // $tcpdf->SetKeywords('FORM_24 - ' . $subject_suffix);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // Output the PDF to the specified destination
    return $tcpdf->Output($pdf_name, $output_type);
}

function applicationForSharesPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas =  DocumentWrapper::applicationForShares($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_07_application_for_shares',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("07_Application For Shares_" . $full_name);
    // $tcpdf->SetSubject("07_Application For Shares_" . $full_name);
    // $tcpdf->SetKeywords("07_Application For Shares_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '07_Application For Shares_' . $full_name . '.pdf';
    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '07_Application For Shares_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 08.
 */
function form45BPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::applicationForShares($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_08_form_54b',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(15, 10, 15);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("08_FORM_45B_" . $full_name);
    // $tcpdf->SetSubject("08_FORM_45B_" . $full_name);
    // $tcpdf->SetKeywords("08_FORM_45B_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '08_FORM_45B-' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '08_FORM_45B-' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

function certificateForSHolderCSealPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $html_template = [
    //   '#theme' => 'tcpdf-09-certification-for-shareholder',
    //   '#datas' => DocumentWrapper::certificationForShareholder($company, $officer),
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, 10);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("09_SHAREHOLDER_CERTIFICATE");
    $tcpdf->SetSubject("09_SHAREHOLDER_CERTIFICATE");
    $tcpdf->SetKeywords("09_SHAREHOLDER_CERTIFICATE");

    $font_name = \TCPDF_FONTS::addTTFfont(getcwd() . '/../ArialMT.ttf', 'TrueTypeUnicode', '', 96);
    $tcpdf->addPage('L', 'A4');
    $tcpdf->SetFont($font_name, '', 10);
    $tcpdf->setCellHeightRatio(1.7);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    $tcpdf->SetLineStyle(['width' => 1, 'color' => [0, 0, 0]]);
    $tcpdf->Rect(5, 5, $tcpdf->getPageWidth() - 10, $tcpdf->getPageHeight() - 10);

    // $full_name = str_replace(' ', '_', $company->getTitle());

    // $filename = '09_Share_Certificate_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => '09_Share_Certificate_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }
    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 09-01.
 */
function certificateForSHolderPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $html_template = [
    //   '#theme' => 'tcpdf_09_01_certification_for_shareholder',
    //   '#datas' => DocumentWrapper::certificationForShareholder($company, $officer),
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, 10);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("09_SHAREHOLDER_CERTIFICATE");
    $tcpdf->SetSubject("09_SHAREHOLDER_CERTIFICATE");
    $tcpdf->SetKeywords("09_SHAREHOLDER_CERTIFICATE");

    $font_name = \TCPDF_FONTS::addTTFfont(getcwd() . '/../ArialMT.ttf', 'TrueTypeUnicode', '', 96);
    $tcpdf->addPage('L', 'A4');
    $tcpdf->SetFont($font_name, '', 10);
    $tcpdf->setCellHeightRatio(1.7);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    $tcpdf->SetLineStyle(['width' => 1, 'color' => [0, 0, 0]]);
    $tcpdf->Rect(5, 5, $tcpdf->getPageWidth() - 10, $tcpdf->getPageHeight() - 10);

    // $full_name = str_replace(' ', '_', $company->getTitle());

    // $filename = '09_Share_Certificate_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => '09_Share_Certificate_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 10.
 */
function changeOfOfficeAddressPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::applicationForShares($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_10_change_office_address',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("10_CHANGE_OFFICE_ADDRESS_" . $full_name);
    // $tcpdf->SetSubject("10_CHANGE_OFFICE_ADDRESS_" . $full_name);
    // $tcpdf->SetKeywords("10_CHANGE_OFFICE_ADDRESS_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '10_CHANGE_OFFICE_ADDRESS_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '10_CHANGE_OFFICE_ADDRESS_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 30.
 */
function customerAcceptancePdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::customerAcceptance($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_30_customer_acceptance_form',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new PDF020Footer(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(TRUE);

    $tcpdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);
    // $tcpdf->SetSubject("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);
    // $tcpdf->SetKeywords("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);
    // $company_title = str_replace(' ', '_', $company->getTitle() . '_' . $company->get('field_company_type')
    //     ->referencedEntities()[0]->getName());

    // $filename = '030_Annex_C---' . $company_title . '_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '030_Annex_C---' . $company_title . '_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}


/**
 * 30 Shareholder.
 */
function customerAcceptanceShareholderPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::customerAcceptance($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_30_customer_acceptance_shareholder_form',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new PDF020Footer(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(TRUE);

    $tcpdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);
    // $tcpdf->SetSubject("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);
    // $tcpdf->SetKeywords("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);
    // $company_title = str_replace(' ', '_', $company->getTitle() . '_' . $company->get('field_company_type')
    //     ->referencedEntities()[0]->getName());

    // $filename = '030_Annex_C---' . $company_title . '_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '030_Annex_C---' . $company_title . '_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 30. Limited by Guarantee
 */
function customerAcceptanceLimitedByGuaranteePdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::customerAcceptance($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_30_customer_acceptance_limited_guarantee_form',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new PDF020Footer(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(TRUE);

    $tcpdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);
    // $tcpdf->SetSubject("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);
    // $tcpdf->SetKeywords("CUSTOMER_ACCEPTANCE_FORM_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);
    // $company_title = str_replace(' ', '_', $company->getTitle() . '_' . $company->get('field_company_type')
    //     ->referencedEntities()[0]->getName());

    // $filename = '030_Annex_C---' . $company_title . '_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '030_Annex_C---' . $company_title . '_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }
    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 21.
 */
function terminationOfCorporateSecretarialServicesPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::resignationsAndAppointmentOfCompanySecretaryWrapper($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_21_termination_of_corporate_secretarial_services',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("21_TERMINATION_OF_CORPORATE_SECRETARIAL_SERVICES");
    // $tcpdf->SetSubject("21_TERMINATION_OF_CORPORATE_SECRETARIAL_SERVICES");
    // $tcpdf->SetKeywords("21_TERMINATION_OF_CORPORATE_SECRETARIAL_SERVICES");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '21_TERMINATION_OF_CORPORATE_SECRETARIAL_SERVICES.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "21_TERMINATION_OF_CORPORATE_SECRETARIAL_SERVICES",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 22.
 */
function resignationsAndAppointmentOfCompanySecretaryPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::resignationsAndAppointmentOfCompanySecretaryWrapper($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_22_resignation_and_appointment_of_company_secrectary',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("22_RESIGNATIONS_and_APPOINTMENT_OF_COMPANY_SECRETARY");
    $tcpdf->SetSubject("22_RESIGNATIONS_and_APPOINTMENT_OF_COMPANY_SECRETARY");
    $tcpdf->SetKeywords("22_RESIGNATIONS_and_APPOINTMENT_OF_COMPANY_SECRETARY");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '22_RESIGNATIONS_and_APPOINTMENT_OF_COMPANY_SECRETARY.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "22_RESIGNATIONS_and_APPOINTMENT_OF_COMPANY_SECRETARY",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 23.
 */
function changeOfFinancialYearPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::resignationsAndAppointmentOfCompanySecretaryWrapper($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_23_change_of_finance_year',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("23_CHANGE_OF_FINANCIAL_YEAR");
    $tcpdf->SetSubject("23_CHANGE_OF_FINANCIAL_YEAR");
    $tcpdf->SetKeywords("23_CHANGE_OF_FINANCIAL_YEAR");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    $filename = '23_CHANGE_OF_FINANCIAL_YEAR.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "23_CHANGE_OF_FINANCIAL_YEAR",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }
    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 24.
 */
function changeOfCompanyNamePdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::resignationsAndAppointmentOfCompanySecretaryWrapper($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_24_change_of_company_name',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("24_CHANGE_OF_COMPANY_NAME");
    $tcpdf->SetSubject("24_CHANGE_OF_COMPANY_NAME");
    $tcpdf->SetKeywords("24_CHANGE_OF_COMPANY_NAME");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    $filename = '24_CHANGE_OF_COMPANY_NAME.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client_sha'],
    //     'title' => "24_CHANGE_OF_COMPANY_NAME",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 25.
 */
function changeOfBusinessActivityPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::resignationsAndAppointmentOfCompanySecretaryWrapper($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_25_change_of_business_activity',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("25_CHANGE_OF_BUSINESS_ACTIVITY");
    $tcpdf->SetSubject("25_CHANGE_OF_BUSINESS_ACTIVITY");
    $tcpdf->SetKeywords("25_CHANGE_OF_BUSINESS_ACTIVITY");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    $filename = '25_CHANGE_OF_BUSINESS_ACTIVITY.pdf';
    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $email = array_merge($datas['client'], $datas['client_sha']);
    //   $data = [
    //     'client' => $email,
    //     'title' => "25_CHANGE_OF_BUSINESS_ACTIVITY",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }
    return $tcpdf->Output($pdf_name, $output_type);


}

/**
 * 26.
 */
function certificateOfCorporateSealPdf($html_content, $pdf_name, $output_type = 'I')
{
    // $datas = DocumentWrapper::resignationsAndAppointmentOfCompanySecretaryWrapper($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_26_certificate_of_corporate_seal',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("26_CERTIFICATE_OF_CORPORATE_SEAL");
    $tcpdf->SetSubject("26_CERTIFICATE_OF_CORPORATE_SEAL");
    $tcpdf->SetKeywords("26_CERTIFICATE_OF_CORPORATE_SEAL");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    $filename = '26_CERTIFICATE_OF_CORPORATE_SEAL.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "26_CERTIFICATE_OF_CORPORATE_SEAL",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

/**
 * 27 and 28.
 */
function changeOfRegisteredOfficeAddressPdf($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::resignationsAndAppointmentOfCompanySecretaryWrapper($company);

    // $html_template = [
    //   '#theme' => 'tcpdf_' . $doc_id . '_change_of_registered_office_address',
    //   '#datas' => $datas,
    // ];
    ob_start();
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // if ($doc_id == '27') {
    //   $tcpdf->SetTitle("27_CHANGE_OF_REGISTERED_OFFICE_ADDRESS");
    //   $tcpdf->SetSubject("27_CHANGE_OF_REGISTERED_OFFICE_ADDRESS");
    //   $tcpdf->SetKeywords("27_CHANGE_OF_REGISTERED_OFFICE_ADDRESS");
    //   $filename = '27_CHANGE_OF_REGISTERED_OFFICE_ADDRESS.pdf';
    // } else if ($doc_id == '28') {
    //   $tcpdf->SetTitle("28_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-07)");
    //   $tcpdf->SetSubject("28_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-07)");
    //   $tcpdf->SetKeywords("28_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-07)");
    //   $filename = '28_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-07).pdf';
    // } else {
    //   $tcpdf->SetTitle("29_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-01)");
    //   $tcpdf->SetSubject("29_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-01)");
    //   $tcpdf->SetKeywords("29_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-01)");
    //   $filename = '29_CHANGE_OF_REGISTERED_OFFICE_ADDRESS (SOMERSET #06-01).pdf';
    // }


    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    ob_end_clean();


    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => $doc_id . "_CHANGE_OF_REGISTERED_OFFICE_ADDRESS",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 13A.
//    */
function noticeForControllerAPdf($html, $pdf_name, $output_type = "I")
{
    // $html_template = [
    //   '#theme' => 'tcpdf_029a_annex_a',
    //   '#datas' => DocumentWrapper::noticeForControllerAnexA($company),
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("10_Annex_A");
    $tcpdf->SetSubject("10_Annex_A");
    $tcpdf->SetKeywords("10_Annex_A");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '10_Annex_A_' . $html_template['#datas']['company_name'] . '_' .
    //   $html_template['#datas']['field_company_type'] . '_' . $html_template['#datas']['uen'] . '_' . date('Y_m_d') . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => "10_Annex_A",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }
    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 029_BI.
//    */
function annexBIPdf($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::applicationForShares($company, $officer);
    // if (!empty($datas['directors'])) {
    //   if ($datas['incorporate_type'] && $datas['incorporate_type'] == 2719) {
    //     foreach ($datas['directors'] as $key => $item) {
    //       if (in_array(SGF_SHAREHOLDER_ID, $item['positions_id'])) {
    //         $datas['directors'][$key]['field_position'] = $item['position_str'];
    //       }
    //     }
    //   }
    // }
    // $datas['position_dir'] = 1;
    // $html_template = [
    //   '#theme' => 'tcpdf_029bi',
    //   '#datas' => $datas,
    // ];
    ob_start();
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("10_Annex_B_Directory_Individual_" . $full_name);
    // $tcpdf->SetSubject("10_Annex_B_Directory_Individual_" . $full_name);
    // $tcpdf->SetKeywords("10_Annex_B_Directory_Individual_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();
    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '10_Annex_B_Directory_Individual_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $email = array_merge($datas['client_share_holder'], $datas['client_se']);
    //   $data = [
    //     'client' => $email,
    //     'title' => '10_Annex_B_Directory_Individual_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 029_BE.
//    */
function annexBEPdf($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::applicationForShares($company, $officer);
    // $datas['position_dir'] = 1;
    // $html_template = [
    //   '#theme' => 'tcpdf_029be',
    //   '#datas' => $datas,
    // ];
    // ob_start();
    // $html = $this->renderer->render($html_template);


    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("10_Annex_B_Directory_Entity_" . $full_name);
    // $tcpdf->SetSubject("10_Annex_B_Directory_Entity_" . $full_name);
    // $tcpdf->SetKeywords("10_Annex_B_Directory_Entity_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();
    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '10_Annex_B_Directory_Entity_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $email = array_merge($datas['client_share_holder'], $datas['client_se']);
    //   $data = [
    //     'client' => $email,
    //     'title' => '10_Annex_B_Directory_Entity_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 029_BIS.
//    */
function annexBISPdf($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::applicationForShares($company, $officer);
    // if (!empty($datas['directors'])) {
    //   if ($datas['incorporate_type'] && $datas['incorporate_type'] == 2719) {
    //     foreach ($datas['directors'] as $key => $item) {
    //       if (in_array(SGF_SHAREHOLDER_ID, $item['positions_id'])) {
    //         $datas['directors'][$key]['field_position'] = $item['position_str'];
    //       }
    //     }
    //   }
    // }
    // $datas['position'] = 1;
    // $html_template = [
    //   '#theme' => 'tcpdf_029bi',
    //   '#datas' => $datas,
    // ];
    ob_start();
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("10_Annex_B_Shareholder_Individual_" . $full_name);
    // $tcpdf->SetSubject("10_Annex_B_Shareholder_Individual_" . $full_name);
    // $tcpdf->SetKeywords("10_Annex_B_Shareholder_Individual_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();
    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '10_Annex_B_Shareholder_Individual_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $email = array_merge($datas['client_share_holder'], $datas['client_se']);
    //   $data = [
    //     'client' => $email,
    //     'title' => '10_Annex_B_Shareholder_Individual_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 029_BES.
//    */
function annexBESPdf($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::applicationForShares($company, $officer);
    // $datas['position'] = 1;
    // $html_template = [
    //   '#theme' => 'tcpdf_029be',
    //   '#datas' => $datas,
    // ];
    // ob_start();
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("10_Annex_B_Shareholder_Entity_" . $full_name);
    // $tcpdf->SetSubject("10_Annex_B_Shareholder_Entity_" . $full_name);
    // $tcpdf->SetKeywords("10_Annex_B_Shareholder_Entity_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();
    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '10_Annex_B_Shareholder_Entity_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $email = array_merge($datas['client_share_holder'], $datas['client_se']);
    //   $data = [
    //     'client' => $email,
    //     'title' => '10_Annex_B_Shareholder_Entity_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 31.
//    */
function registrationOfNomineeDirector($html, $pdf_name, $output_type = "I")
{
    // $html_template = [
    //   '#theme' => 'tcpdf_031',
    //   '#datas' => DocumentWrapper::applicationForShares($company, $officer),
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("030_Annex_D_" . $full_name);
    // $tcpdf->SetSubject("030_Annex_D_" . $full_name);
    // $tcpdf->SetKeywords("030_Annex_D__" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '030_Annex_D__' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => '030_Annex_D__' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }
    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 31_S.
//    */
function registrationOfNomineeShareholder($html, $pdf_name, $output_type = "I")
{
    // $html_template = [
    //   '#theme' => 'tcpdf_031S',
    //   '#datas' => DocumentWrapper::nomineeShareholder($company, $officer),
    // ];
    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // if ($officer instanceof Paragraph) {
    //   $full_name = $officer->get('field_full_name')->value;
    // } else {
    //   $full_name = 'NONE';
    // }
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("031_Annex_E_" . $full_name);
    // $tcpdf->SetSubject("031_Annex_E_" . $full_name);
    // $tcpdf->SetKeywords("031_Annex_E_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '031_Annex_E__' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   return [
    //     'client' => [],
    //     'title' => '031_Annex_E__' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 31_S20.
//    */
function confirmationLetter($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::companyDetailsConfirmationLetter($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_031S20',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new PDF020Footer(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle('LETTER_OF_CONFIRMATION (NOMINEE SHAREHOLDER)');
    $tcpdf->SetSubject('LETTER_OF_CONFIRMATION (NOMINEE SHAREHOLDER)');
    $tcpdf->SetKeywords('LETTER_OF_CONFIRMATION (NOMINEE SHAREHOLDER)');

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(TRUE);
    $tcpdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->addPage();
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '031_20.LETTER_OF_CONFIRMATION__' . $company->getTitle() . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   return [
    //     'client' => $datas['client'],
    //     'title' => '031_20.LETTER_OF_CONFIRMATION__' . $company->getTitle(),
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 31_S20B.
//    */
function confirmationLetterWithBlanks($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::companyDetailsConfirmationLetter($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_031S20B',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    $tcpdf = new PDF020Footer(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle('LETTER_OF_CONFIRMATION (NOMINEE SHAREHOLDER) - BLANK');
    $tcpdf->SetSubject('LETTER_OF_CONFIRMATION (NOMINEE SHAREHOLDER) - BLANK');
    $tcpdf->SetKeywords('LETTER_OF_CONFIRMATION (NOMINEE SHAREHOLDER) - BLANK');

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(TRUE);
    $tcpdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->addPage();
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '031_20B.LETTER_OF_CONFIRMATION__' . $company->getTitle() . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // } else {
    //   return [
    //     'client' => $datas['client'],
    //     'title' => '031_20B.LETTER_OF_CONFIRMATION__' . $company->getTitle(),
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    *
//    */
function certificateOfEmployment($html, $pdf_name, $output_type = "I")
{
    // $html_template = [
    //   '#theme' => 'tcpdf_028',
    //   '#datas' => DocumentWrapper::applicationForShares($company, $officer),
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("CERTIFICATE_OF_EMPLOYMENT_" . $full_name);
    // $tcpdf->SetSubject("CERTIFICATE_OF_EMPLOYMENT_" . $full_name);
    // $tcpdf->SetKeywords("CERTIFICATE_OF_EMPLOYMENT_" . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = 'CERTIFICATE_OF_EMPLOYMENT_' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => 'CERTIFICATE_OF_EMPLOYMENT_' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Document 1st Director vs 2nd Director
//    */
function serviceIndemnityAgreement1D2D($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::serviceIndemnityCompanyDetails1D2D($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_05',
    //   '#datas' => $datas,
    // ];

    // $html = $this->renderer->render($html_template);
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $author = \Drupal::currentUser()->getAccountName();
    $tcpdf->SetCreator($author);
    $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetSubject("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetKeywords("05_Director_Service_Indemnity_Agreement");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '05_Director_Service_Indemnity_Agreement.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['director']['client'], $datas['witness']['client']),
    //     'title' => "05_Director_Service_Indemnity_Agreement",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);


}

//   /**
//    *
//    */
function serviceIndemnityAgreement1D2S($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::serviceIndemnityCompanyDetails1D1S($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_05_1d_1s',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetSubject("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetKeywords("05_Director_Service_Indemnity_Agreement");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    $filename = '05_Director_Service_Indemnity_Agreement.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['shareholder']['client'], $datas['witness']['client']),
    //     'title' => "05_Director_Service_Indemnity_Agreement",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    *
//    */
function serviceIndemnityAgreement3($html, $pdf_name, $output_type = "I")
{
    // TODO: Implement ServiceIndemnityAgreement3() method.
    // TODO: Implement ServiceIndemnityAgreement1() method.
    // $html_template = [
    //   '#theme' => 'tcpdf_05_1d_2s',
    //   '#datas' => DocumentWrapper::serviceIndemnityCompanyDetails($company),
    // ];
    // $html = $this->renderer->render($html_template);


    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $author = \Drupal::currentUser()->getAccountName();
    $tcpdf->SetCreator($author);
    $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetSubject("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetKeywords("05_Director_Service_Indemnity_Agreement");

    $tcpdf->addPage();
    $tcpdf->SetFont('pdfahelvetica', '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    $filename = '05_Director_Service_Indemnity_Agreement.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => "05_Director_Service_Indemnity_Agreement",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    *
//    */
function serviceIndemnityAgreement4($html, $pdf_name, $output_type = "I")
{
    // $html_template = [
    //   '#theme' => 'tcpdf_05_1d_1d',
    //   '#datas' => DocumentWrapper::serviceIndemnityCompanyDetails($company),
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetSubject("05_Director_Service_Indemnity_Agreement");
    $tcpdf->SetKeywords("05_Director_Service_Indemnity_Agreement");

    $tcpdf->addPage();
    $tcpdf->SetFont('pdfahelvetica', '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $filename = '05_Director_Service_Indemnity_Agreement.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => "05_Director_Service_Indemnity_Agreement",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * 00_00.
//    */
function agmPdf($html, $pdf_name, $output_type = "I")
{
    // if (!in_array($document_id, ['00', '01', '02'])) {
    //   throw new NotFoundHttpException();
    // }
    // $datas = [];
    // if ($company instanceof NodeInterface) {
    //   $datas['company'] = DocumentWrapper::companyDetails($company);
    //   foreach ($company->get('field_director_shareholder')->referencedEntities() as $officer) {
    //     $behalf = SGFDocumentController::checkIsCompany($officer);
    //     if (SGFDocumentController::checkIsShareholder($officer)) {
    //       $officer_id = $officer->id();
    //       if (isset($datas['company']['field_director_shareholder'][$officer_id])) {
    //         $datas['company']['field_director_shareholder'][$officer_id]['behalf'] = $behalf;
    //         $email[] = [
    //           'officer_name' => [$datas['company']['field_director_shareholder'][$officer_id]['field_email_id'] => $datas['company']['field_director_shareholder'][$officer_id]['field_full_name']],
    //           'email' => $datas['company']['field_director_shareholder'][$officer_id]['field_email_id'],
    //         ];
    //       }
    //       $datas['company']['shareholders'][$officer_id] = isset($datas['company']['field_director_shareholder'][$officer_id]) ? $datas['company']['field_director_shareholder'][$officer_id] : [];
    //     }
    //     if (SGFDocumentController::checkIsDirector($officer)) {
    //       $officer_id = $officer->id();
    //       if (isset($datas['company']['field_director_shareholder'][$officer_id])) {
    //         $datas['company']['field_director_shareholder'][$officer_id]['behalf'] = $behalf;
    //         $email[] = [
    //           'officer_name' => [$datas['company']['field_director_shareholder'][$officer_id]['field_email_id'] => $datas['company']['field_director_shareholder'][$officer_id]['field_full_name']],
    //           'email' => $datas['company']['field_director_shareholder'][$officer_id]['field_email_id'],
    //         ];
    //       }
    //       $datas['company']['directors'][$officer_id] = isset($datas['company']['field_director_shareholder'][$officer_id]) ? $datas['company']['field_director_shareholder'][$officer_id] : [];
    //     }
    //   }
    // }

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    // $html_template = [
    //   '#theme' => 'agm_pdf_' . $document_id,
    //   '#datas' => $datas,
    // ];
    // ob_start();
    // $html = $this->renderer->render($html_template);

    $tcpdf = new PDFAGMFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
    $tcpdf->setCustomFooterText('<div style="width:100%;text-align:left;font-size:9px;">Notes:<br/>
      A member entitled to attend and vote at the meeting is entitled to appoint a proxy to attend and vote in his/her
      stead. A proxy must be deposited at the company’s registered office, not less than 48 hours before the meeting.
    </div>');
    $tcpdf->setpageNumber([1]);
    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(TRUE);

    // Set margins.
    $tcpdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // if ($document_id == '02') {
    //   $tcpdf->SetTitle('03_' . $company->getTitle() . "_AGM_DORMANT");
    //   $name = '03_' . $company->getTitle() . "_AGM_DORMANT";
    // } else {
    //   $name =  $document_id . '_' . $company->getTitle() . "_AGM";
    //   $tcpdf->SetTitle('AGM DOCUMENT ' . $document_id);
    // }
    // $tcpdf->SetSubject('AGM PDF ' . $document_id);
    // $tcpdf->SetKeywords('AGM PDF ' . $document_id);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);

    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();

    // $filename = $name . ".pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $email,
    //     'title' => $name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * @param $company
//    * @param null $type
//    * @return array
//    * @throws \Exception
//    */
function agmPdf04($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::agmDormant($company);
    // $html_template = [
    //   '#theme' => 'agm_pdf_03',
    //   '#datas' => $datas,
    // ];

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    ob_start();
    // $html = $this->renderer->render($html_template);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle('04_'.$datas['title'].'_wo_AGM_DORMANT');
    // $tcpdf->SetSubject('04_'.$datas['title'].'_wo_AGM_DORMANT');
    // $tcpdf->SetKeywords('04_'.$datas['title'].'_wo_AGM_DORMANT');

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();

    // $filename = '04_'.$datas['title'].'_wo_AGM_DORMANT.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '04_'.$datas['title'].'_wo_AGM_DORMANT',
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Allotment of Shares.
//    */
function drAllotmentOfShares($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::drAllotment($company, $officer);

    // $html_template = [
    //   '#theme' => 'tcpdf_34_dr_allotment_of_shares',
    //   '#datas' => $datas,
    // ];
    // ob_start();
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $title = "DR Allotment of Shares - " . $datas['officer']['name'];
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle($title);
    // $tcpdf->SetSubject("DR Allotment of Shares");
    // $tcpdf->SetKeywords("DR Allotment of Shares");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();

    // $filename = $company->getTitle() . " DR Allotment of Shares.pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => $company->getTitle() . "_DR Allotment of Shares",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Transfer of Shares.
//    */
function drTransferOfShares($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::drAllotment($company, $officer);

    // $html_template = [
    //   '#theme' => 'tcpdf_34_dr_transfer_of_shares',
    //   '#datas' => $datas,
    // ];
    // ob_start();
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(20, 10, 20);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $title = "DR Transfer of Shares - " . $datas['officer']['name'];
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle($title);
    $tcpdf->SetSubject("DR Transfer of Shares");
    $tcpdf->SetKeywords("DR Transfer of Shares");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    ob_end_clean();

    // $filename = $company->getTitle() . " DR Transfer of Shares.pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => $company->getTitle() . "_DR Transfer of Shares",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Dr interim Dividends.
//    */
function interimDividends($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::drInterimDividends($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_35_dr_interim_dividends',
    //   '#datas' => $datas,
    // ];
    // ob_start();
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(20, 10, 20);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $title = "Dr interim Dividends - " . $datas['officer']['name'];
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle($title);
    $tcpdf->SetSubject("Dr interim Dividends");
    $tcpdf->SetKeywords("Dr interim Dividends");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // ob_end_clean();


    // $filename = $company->getTitle() . " Dr interim Dividends.pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => $company->getTitle() . "_Dr interim Dividends",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}


//   /**
//    * Dividends Statement.
//    */
function dividendsStatement($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::drInterimDividends($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_35_dividends_statement',
    //   '#datas' => $datas,
    // ];
    // ob_start();
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(12, 10, 12);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $title = "Dividends Statement - " . $datas['officer']['name'];
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle($title);
    $tcpdf->SetSubject("Dividends Statement");
    $tcpdf->SetKeywords("Dividends Statement");

    $tcpdf->addPage();
    $tcpdf->SetLineStyle(array('width' => 0.5, 'color' => array(0, 0, 0)));
    $tcpdf->Rect(10, 10, $tcpdf->getPageWidth() - 20, $tcpdf->getPageHeight() - 20);

    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // ob_end_clean();

    // $filename = $company->getTitle() . " Dividends Statement.pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => $company->getTitle() . "_Dividends Statement",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);


}

//   /**
//    * DR Writing off of Investment.
//   */
function drWritingOffOfInvestment($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::drWritingOffInvestment($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_36_dr_writing_off_of_investment',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("DR Writing off of Investment");
    $tcpdf->SetSubject("DR Writing off of Investment");
    $tcpdf->SetKeywords("DR Writing off of Investment");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    $filename = 'DR Writing off of Investment.pdf';
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['shareholder']['client'], $datas['witness']['client']),
    //     'title' => "DR Writing off of Investment",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * DR Striking Off.
//   */
function drStrikingOff($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::drStriking($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_36_dr_striking_off',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("DR Striking Off");
    $tcpdf->SetSubject("DR Striking Off");
    $tcpdf->SetKeywords("DR Striking Off");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = 'DR Striking Off.pdf';
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['shareholder']['client'], $datas['witness']['client']),
    //     'title' => "DR Striking Off",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * DR Divestment.
//   */
function drDivestment($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::drStriking($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_36_dr_divestment',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("DR Divestment");
    $tcpdf->SetSubject("DR Divestment");
    $tcpdf->SetKeywords("DR Divestment");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = 'DR Divestment.pdf';
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['shareholder']['client'], $datas['witness']['client']),
    //     'title' => "DR Divestment",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * @param Node $company
//    * @param Paragraph $officer
//    * @param null $type
//    * @return array
//    * @throws \Exception
//    */
function changeOfDirectors($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::serviceIndemnityCompany($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_40_00',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $title = ($datas['shareholder'] && !empty($datas['shareholder']['full_name'])) ? $datas['shareholder']['full_name'] : '';

    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("Director_Service_Indemnity_Agreement_" . $title);
    // $tcpdf->SetSubject("Director_Service_Indemnity_Agreement_" . $title);
    // $tcpdf->SetKeywords("Director_Service_Indemnity_Agreement");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = 'Director_Service_Indemnity_Agreement.pdf';
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['shareholder']['client'], $datas['witness']['client']),
    //     'title' => "Director_Service_Indemnity_Agreement_" . $title,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * @param Node $company
//    * @param Paragraph $officer
//    * @param null $type
//    * @return array
//    * @throws \Exception
//    */
function drChangeOfDirectors($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::directorsResolutions($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_40_01',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Directors' Resolutions - Change of Directors");
    $tcpdf->SetSubject("Directors' Resolutions - Change of Directors");
    $tcpdf->SetKeywords("Directors' Resolutions - Change of Directors");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "Directors' Resolutions - Change of Directors";
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "Directors' Resolutions - Change of Directors.pdf",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }


    return $tcpdf->Output($pdf_name, $output_type);


}

//   /**
//    * @param Node $company
//    * @param Paragraph $officer
//    * @param null $type
//    * @return array
//    * @throws \Exception
//    */
function form45ChangeOfDirectors($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::form45directorsResolutions($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_40_02',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Form 45 - TAN KUAN HUI");
    $tcpdf->SetSubject("Form 45 - TAN KUAN HUI");
    $tcpdf->SetKeywords("Form 45 - TAN KUAN HUI");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "Form 45 - TAN KUAN HUI";
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "Form 45 - TAN KUAN HUI.pdf",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * @param Node $company
//    * @param null $type
//    * @return array
//    * @throws \Exception
//    */
function formParticularChangeOfDirectors($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::formChangeOfDirectors($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_40_03',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("FORM OF PARTICULARS - TAN KUAN HUI");
    $tcpdf->SetSubject("FORM OF PARTICULARS - TAN KUAN HUI");
    $tcpdf->SetKeywords("FORM OF PARTICULARS - TAN KUAN HUI");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "FORM OF PARTICULARS - TAN KUAN HUI";
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "FORM OF PARTICULARS - TAN KUAN HUI.pdf",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Resignation of Director - LIN HUAYAN, LAWRENCE.pdf
//    */
function resignationChangeOfDirectors($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::resignationDirectors($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_40_04',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(30, 10, 30);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Resignation of Director - LIN HUAYAN, LAWRENCE");
    $tcpdf->SetSubject("Resignation of Director - LIN HUAYAN, LAWRENCE");
    $tcpdf->SetKeywords("Resignation of Director - LIN HUAYAN, LAWRENCE");

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "Resignation of Director - LIN HUAYAN, LAWRENCE";
    // if (empty($type)) {
    //   echo $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => "Resignation of Director - LIN HUAYAN, LAWRENCE.pdf",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Register of Director.
//    */
function registerOfDirectors($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::register($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_41',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Register of Director");
    $tcpdf->SetSubject("Register of Director");
    $tcpdf->SetKeywords("Register of Director");
    $tcpdf->addPage('L', 'A4');
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "Register of Director";

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Register of Secretaries.
//    */
function registerOfSecretaries($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::register($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_42',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();
    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Secretaries - Manager");
    $tcpdf->SetSubject("Secretaries - Manager");
    $tcpdf->SetKeywords("Secretaries - Manager");
    $tcpdf->addPage('L', 'A4');
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    $filename = "Secretaries - Manager";

    return $tcpdf->Output($pdf_name, $output_type);
}

//   /**
//    * Register of Applications.
//    */
function registerOfApplications($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::registerApplications($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_43',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Register of Applications and Allotments Ordinary");
    $tcpdf->SetSubject("Register of Applications and Allotments Ordinary");
    $tcpdf->SetKeywords("Register of Applications and Allotments Ordinary");
    $tcpdf->addPage('L', 'A4');
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "Register of Applications and Allotments Ordinary";

    return $tcpdf->Output($pdf_name, $output_type);
}

//   /**
//    * Register of Transfers.
//    */
function registerOfTransfers($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::registerTransfers($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_44',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Register of Transfers");
    $tcpdf->SetSubject("Register of Transfers");
    $tcpdf->SetKeywords("Register of Transfers");
    $tcpdf->addPage('L', 'A4');
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "Register of Transfers";

    return $tcpdf->Output($pdf_name, $output_type);
}

//   /**
//    * Register of Members.
//    */
function registerOfMembers($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::registerMember($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_45',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    $tcpdf->SetTitle("Register of Members");
    $tcpdf->SetSubject("Register of Members");
    $tcpdf->SetKeywords("Register of Members");
    $tcpdf->addPage('L', 'A4');
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $filename = "Register of Members";

    return $tcpdf->Output($pdf_name, $output_type);
}

function serviceIndemnityAgreementSFA($html_content, $pdf_name, $output_type = 'I')
{


    // $datas = DocumentWrapper::serviceIndemnityAgreementTrustDeed($company, $officer);
    // $html_template = [
    //   '#theme' => 'tcpdf_05_trust_deed',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    $tcpdf->setPrintHeader(FALSE);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins.
    $tcpdf->SetMargins(10, 10, 10);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // $author = \Drupal::currentUser()->getAccountName();
    // $full_name = $officer->get('field_full_name')->value;
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("TRUST DEED - " . $full_name);
    // $tcpdf->SetSubject("TRUST DEED - " . $full_name);
    // $tcpdf->SetKeywords("TRUST DEED - " . $full_name);

    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 10);
    $tcpdf->writeHTML($html_content, TRUE, 0, TRUE, TRUE);
    $tcpdf->Ln();
    $tcpdf->lastPage();

    // $full_name = str_replace(' ', '_', $full_name);

    // $filename = '05_TRUST_DEED--' . $full_name . '.pdf';

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => array_merge($datas['shareholder']['client'], $datas['nominee']['client'], $datas['secretary']['client']),
    //     'title' => '05_TRUST_DEED--' . $full_name,
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);
}

// function serviceIndemnityAgreementSFA ($company, $id, $group_id, $type = NULL) {
//   $datas = DocumentWrapper::serviceIndemnitySFA($company, $id);
//   $template = 'tcpdf_05_service_fee_agreement';
//   $title = $group_id. '_Service_Fee_Agreement';
//   $title_name = '법인 유지 비용 계약서';
//   if ($id == '03') {
//     $template = 'tcpdf_05_service_fee_agreement_03';
//     $title = $group_id . '_Service_Fee_Agreement_2nd/BlkChn';
//   }
//   if ($id == '02') {
//     $template = 'tcpdf_05_service_fee_agreement_principal';
//     $title = $group_id . '_Service_Fee_Agreement_2nd_Principal';
//   }
//   if ($id == '04') {
//     $template = 'tcpdf_05_service_fee_agreement_principal_04';
//     $title = $group_id . '_Service_Fee_Agreement_2nd_Principal/BlkChn';
//   }
//   if ($id == '1st') {
//     $title_name = '법인 설립 및 유지 비용 계약서';
//     $template = 'tcpdf_05_service_fee_agreement_1st';
//     $title = $group_id . '_Service_Fee_Agreement_(1st)';
//   }
//   if ($id == '3st') {
//     $title_name = '법인 설립 및 유지 비용 계약서';
//     $template = 'tcpdf_05_service_fee_agreement_3st';
//     $title = $group_id . '_Service_Fee_Agreement_(1st/BlkChn)';
//   }
//   if ($id == '2st') {
//     $title_name = '법인 설립 및 유지 비용 계약서';
//     $template = 'tcpdf_05_service_fee_agreement_1st_principal';
//     $title = $group_id . '_Service_Fee_Agreement_1st_Principal';
//   }
//   if ($id == '4st') {
//     $title_name = '법인 설립 및 유지 비용 계약서';
//     $template = 'tcpdf_05_service_fee_agreement_4st_principal';
//     $title = $group_id . '_Service_Fee_Agreement_1st_Principal/BlkChn';
//   }
//   $html_template = [
//     '#theme' => $template,
//     '#datas' => $datas,
//   ];
//   $html = $this->renderer->render($html_template);
//   ob_start();
//   $tcpdf = new PDFSFA(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

//   $tcpdf->setCustomHeaderText('
//     <style>
//       span {font-size: 11px;font-weight: normal;font-family: Arial, Helvetica, sans-serif;}
//     </style>
//     <table border="0" cellspacing="0" cellpadding="0">
//       <tr>
//         <td align="left"  style="width:50%;"><img src="/themes/custom/zurblk/img/pdf_logo.png"></td>
//         <td align="right" style="width:50%;"><br><br>
//           <strong>LEE KIM ALLIANCE PTE. LTD.</strong><br>
//           <span>111 Somerset, #06-07L</span><br>
//           <span>111 Somerset, Singapore 238164</span><br>
//           <span>TEL: (65) 6633-5051 / FAX: (65) 6826-4170</span>
//         </td>
//       </tr>
//     </table>
//     <h1 style="width:100%;text-align:center;">'. $title_name .'</h1>
//   ');
//   // Set default monospaced font.
//   $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//   // Set margins.
//   if (in_array($id, ['1st', '2st', '3st', '4st'])) {
//     $tcpdf->SetMargins(18, 50, 18);
//   } else {
//     $tcpdf->SetMargins(20, 50, 20);
//   }
//   $tcpdf->SetHeaderMargin(PDF_MARGIN_FOOTER);
//   $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);


//   $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//   $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//   $author = \Drupal::currentUser()->getAccountName();
//   $tcpdf->SetCreator($author);
//   $tcpdf->SetAuthor($author);
//   $tcpdf->SetTitle($title);
//   $tcpdf->SetSubject($title);
//   $tcpdf->SetKeywords($title);

//   $tcpdf->addPage();
//   $font_name = \TCPDF_FONTS::addTTFfont(getcwd() . '/static/font/malgun.ttf', 'TrueTypeUnicode', '', 32);
//   $tcpdf->SetFont($font_name, '', 9);
//   $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE);
//   $tcpdf->Ln();
//   $tcpdf->lastPage();
//   // Clean any content of the output buffer.
//   ob_end_clean();
//   $filename = $title . '.pdf';
//   if (empty($type)) {
//     echo $tcpdf->Output($filename, 'I');
//   }
//   else {
//     $data = [
//       'client' => $datas['client'],
//       'title' => $title,
//       'pdf_string' => $tcpdf->Output($filename, $type),
//     ];
//     return $data;
//   }
// }

//   /**
//    * Appointment of Auditors
//    */
function appointmentOfAuditors($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::appointmentOfAuditors($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_46_01_appointment_of_auditors',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();


    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new PDFSFA(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

    // $companyTitleStr = $datas['company_name'] . ' ' . $datas['field_company_type'];
    // $addressStr = $datas['address'] ? '<span>' . $datas['address'] . '</span><br>' : '';
    // $address2Str = $datas['address_2'] ? '<span>' . $datas['address_2'] . '</span><br>' : '';
    // $contactNoStr = $datas['contact_no'] ? '<span>TEL: ' . $datas['contact_no'] . '</span><br>' : '';
    // $mailStr = $datas['mail'] ? '<span>Email: ' . $datas['mail'] . '</span><br>' : '';
    // $tcpdf->setCustomHeaderText('
    //   <style>
    //     span {font-size: 11px;font-weight: normal;font-family: Arial, Helvetica, sans-serif;}
    //   </style>
    //   <table border="0" cellspacing="0" cellpadding="0">
    //     <tr>
    //       <td align="left"  style="width:50%;"></td>
    //       <td align="right" style="width:50%;"><br><br>
    //         <strong>' . $companyTitleStr . '</strong><br>
    //         ' . $addressStr . '
    //         ' . $address2Str . '
    //         ' . $contactNoStr . '
    //         ' . $mailStr . '
    //       </td>
    //     </tr>
    //   </table>
    // ');

    $tcpdf->SetHeaderMargin(PDF_MARGIN_FOOTER);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(20, 45, 20);

    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("APPOINTMENT OF AUDITORS ($companyTitleStr)");
    // $tcpdf->SetSubject("APPOINTMENT OF AUDITORS ($companyTitleStr)");
    // $tcpdf->SetKeywords("APPOINTMENT OF AUDITORS ($companyTitleStr)");
    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 11);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $companyTitleStr = str_replace('.', '', $companyTitleStr);
    // $filename = "APPOINTMENT OF AUDITORS ($companyTitleStr).pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '46_01_Appointment_Of_Auditors',
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Consent to act
//    */
function consentToAct($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::appointmentOfAuditors($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_46_02_consent_to_act',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();

    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);


    $tcpdf = new PDFSFA(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
    $tcpdf->setCustomHeaderText('
      <style>
        span {font-size: 11px;font-weight: normal;font-family: Arial, Helvetica, sans-serif;}
      </style>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left"  style="width:50%;"><img src="/themes/custom/zurblk/img/pdf_logo.png"></td>
          <td align="right" style="width:50%;"><br><br>
            <strong>LEE KIM AUDIT PAC</strong><br>
            <span>111 Somerset, #06-01</span><br>
            <span>111 Somerset, Singapore 238164</span><br>
            <span>TEL: (65) 6633-5051</span><br>
            <span>Email: Help@LeeKimAudit.com</span><br>
          </td>
        </tr>
      </table>
    ');

    $tcpdf->SetHeaderMargin(PDF_MARGIN_FOOTER);
    $tcpdf->setPrintFooter(FALSE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(20, 45, 20);

    // $companyTitleStr = $datas['company_name'] . ' ' . $datas['field_company_type'];
    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("CONSENT TO ACT ($companyTitleStr)");
    // $tcpdf->SetSubject("CONSENT TO ACT ($companyTitleStr)");
    // $tcpdf->SetKeywords("CONSENT TO ACT ($companyTitleStr)");
    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 11);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $companyTitleStr = str_replace('.', '', $companyTitleStr);
    // $filename = "CONSENT TO ACT ($companyTitleStr).pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => [],
    //     'title' => "46_02_Consent_To_Act",
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}

//   /**
//    * Engagement letter
//    */
function engagementLetter($html, $pdf_name, $output_type = "I")
{
    // $datas = DocumentWrapper::appointmentOfAuditors($company);
    // $html_template = [
    //   '#theme' => 'tcpdf_46_03_engagement_letter',
    //   '#datas' => $datas,
    // ];
    // $html = $this->renderer->render($html_template);
    // ob_start();


    $helvetica = TCPDF_FONTS::addTTFfont(getcwd() . '/../Helvetica.ttf', 'TrueTypeUnicode', '', 32);

    $tcpdf = new PDFSFA(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
    $tcpdf->setCustomHeaderText('
      <style>
        span {font-size: 11px;font-weight: normal;font-family: Arial, Helvetica, sans-serif;}
      </style>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left"  style="width:50%;"><img src="/themes/custom/zurblk/img/pdf_logo.png"></td>
          <td align="right" style="width:50%;"><br><br>
            <strong>LEE KIM AUDIT PAC</strong><br>
            <span>111 Somerset, #06-01</span><br>
            <span>111 Somerset, Singapore 238164</span><br>
            <span>TEL: (65) 6633-5051</span><br>
            <span>Email: Help@LeeKimAudit.com</span><br>
          </td>
        </tr>
      </table>
    ');

    $tcpdf->SetHeaderMargin(PDF_MARGIN_FOOTER);
    $tcpdf->setPrintFooter(TRUE);

    // Set default monospaced font.
    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // Set margins.
    $tcpdf->SetMargins(20, 45, 20);
    $tcpdf->setListIndentWidth(10);

    // $companyTitleStr = $datas['company_name'] . ' ' . $datas['field_company_type'];
    $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // $author = \Drupal::currentUser()->getAccountName();
    // $tcpdf->SetCreator($author);
    // $tcpdf->SetAuthor($author);
    // $tcpdf->SetTitle("ENGAGEMENT LETTER ($companyTitleStr)");
    // $tcpdf->SetSubject("ENGAGEMENT LETTER ($companyTitleStr)");
    // $tcpdf->SetKeywords("ENGAGEMENT LETTER ($companyTitleStr)");
    $tcpdf->addPage();
    $tcpdf->SetFont($helvetica, '', 11);
    $tcpdf->writeHTML($html, TRUE, 0, TRUE, TRUE, '');
    $tcpdf->Ln();
    $tcpdf->lastPage();
    // Clean any content of the output buffer.
    // ob_end_clean();
    // $companyTitleStr = str_replace('.', '', $companyTitleStr);
    // $filename = "ENGAGEMENT LETTER ($companyTitleStr).pdf";

    // if (empty($type)) {
    //   print $tcpdf->Output($filename, 'I');
    // }
    // else {
    //   $data = [
    //     'client' => $datas['client'],
    //     'title' => '46_03_Engagement_Letter',
    //     'pdf_string' => $tcpdf->Output($filename, $type),
    //   ];
    //   return $data;
    // }

    return $tcpdf->Output($pdf_name, $output_type);

}