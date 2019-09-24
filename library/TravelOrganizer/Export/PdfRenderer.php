<?php

class TravelOrganizer_Export_PdfRenderer
{
    public function renderDocument($title, $filename, $content)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('TravelOrganizer');
        $pdf->SetTitle($title);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 8);

        $pdf->writeHTML(
            $content,
            true,
            false,
            true
        );

        $pdf->lastPage();

        return $pdf->Output($filename, 'S');
    }
}
