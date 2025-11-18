<?php

namespace App\Utils;

class PDFGenerator {
    /**
     * Generate PDF for a quote
     * Using basic HTML to PDF conversion (in production, use library like TCPDF or DomPDF)
     */
    public static function generateQuotePDF($quote, $project, $artisan) {
        $html = self::getQuoteHTML($quote, $project, $artisan);

        // For demonstration, we'll create a simple HTML page
        // In production, use a library like TCPDF, mPDF, or DomPDF

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="devis_' . $quote['id'] . '.pdf"');

        // This is a placeholder - in production, use proper PDF library
        return $html;
    }

    private static function getQuoteHTML($quote, $project, $artisan) {
        $date = date('d/m/Y');
        $quoteNumber = 'DEV-' . str_pad($quote['id'], 6, '0', STR_PAD_LEFT);

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { text-align: center; padding: 20px; background: #2563eb; color: white; }
        .info-section { margin: 20px 0; }
        .info-block { display: inline-block; width: 48%; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f3f4f6; }
        .total { font-size: 1.5em; font-weight: bold; text-align: right; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 2px solid #2563eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DEVIS</h1>
        <p>N° {$quoteNumber}</p>
        <p>Date: {$date}</p>
    </div>

    <div class="info-section">
        <div class="info-block">
            <h3>Artisan</h3>
            <p>
                <strong>{$artisan['company_name']}</strong><br>
                {$artisan['first_name']} {$artisan['last_name']}<br>
                {$artisan['address']}<br>
                {$artisan['postal_code']} {$artisan['city']}<br>
                SIRET: {$artisan['siret']}<br>
                Email: {$artisan['email']}<br>
                Tél: {$artisan['phone']}
            </p>
        </div>

        <div class="info-block">
            <h3>Client</h3>
            <p>
                <strong>{$project['first_name']} {$project['last_name']}</strong><br>
                {$project['address']}<br>
                {$project['postal_code']} {$project['city']}<br>
                Email: {$project['email']}<br>
                Tél: {$project['phone']}
            </p>
        </div>
    </div>

    <h3>Projet: {$project['title']}</h3>
    <p>{$project['description']}</p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{$quote['description']}</td>
                <td>{$quote['amount']} €</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total HT: {$quote['amount']} €<br>
        TVA (20%): {$quote['amount'] * 0.2} €<br>
        <strong>Total TTC: {$quote['amount'] * 1.2} €</strong>
    </div>

    <div class="footer">
        <h4>Conditions</h4>
        <p><strong>Durée estimée:</strong> {$quote['estimated_duration']}</p>
        <p><strong>Date de début:</strong> {$quote['start_date']}</p>
        <p><strong>Conditions de paiement:</strong> {$quote['payment_terms']}</p>
        <p><strong>Validité du devis:</strong> Jusqu'au {$quote['valid_until']}</p>

        <p style="margin-top: 30px;">
            Ce devis est valable {$quote['valid_until']} et ne constitue pas un engagement ferme
            tant qu'il n'a pas été accepté par le client.
        </p>

        <p style="margin-top: 30px; text-align: right;">
            Signature du client:<br><br>
            _______________________
        </p>
    </div>

    <div style="text-align: center; margin-top: 50px; color: #666; font-size: 0.9em;">
        Document généré par Travaux Pro - www.travaux-pro.fr
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate comparison PDF for multiple quotes
     */
    public static function generateComparisonPDF($quotes, $project) {
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
        $html .= '<style>body{font-family:Arial;} table{width:100%;border-collapse:collapse;} th,td{padding:10px;border:1px solid #ddd;}</style>';
        $html .= '</head><body>';
        $html .= '<h1>Comparaison des devis</h1>';
        $html .= '<h2>Projet: ' . htmlspecialchars($project['title']) . '</h2>';

        $html .= '<table><thead><tr><th>Artisan</th><th>Montant</th><th>Durée</th><th>Note</th></tr></thead><tbody>';

        foreach ($quotes as $quote) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($quote['company_name']) . '</td>';
            $html .= '<td>' . number_format($quote['amount'], 2) . ' €</td>';
            $html .= '<td>' . htmlspecialchars($quote['estimated_duration'] ?? 'N/A') . '</td>';
            $html .= '<td>' . number_format($quote['rating_average'] ?? 0, 1) . '/5</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        return $html;
    }
}
