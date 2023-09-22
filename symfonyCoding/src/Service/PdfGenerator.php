<?php

namespace App\Service;

use TCPDF;

class PdfGenerator {
    public function generatePdf($html) {
        // Créez une instance de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Définissez les informations du document (facultatif)
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Document Title');
        $pdf->SetSubject('Document Subject');
        $pdf->SetKeywords('Keywords, PDF');

        // Définissez le contenu du PDF à partir du HTML
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        // Nommez le fichier PDF
        $pdfFileName = 'article.pdf';

        // Envoyez le PDF généré en tant que réponse HTTP
        $pdf->Output($pdfFileName, 'I');

        return $pdfFileName; // Facultatif : vous pouvez retourner le nom du fichier généré si nécessaire
    }
}