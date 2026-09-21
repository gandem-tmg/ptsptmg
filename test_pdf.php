<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use App\Models\Permohonan;
use Barryvdh\DomPDF\Facade\Pdf;

$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "Testing PDF generation\n";

$permohonan = Permohonan::first();
if ($permohonan) {
    $permohonan->load('lampiranPermohonan.persyaratan');
    try {
        $pdf = Pdf::loadView('pemohon.permohonan.pdf', compact('permohonan'));
        $pdfPath = 'storage/app/public/permohonan_pdf/' . $permohonan->no_tiket . '.pdf';
        file_put_contents($pdfPath, $pdf->output());
        echo "PDF generated successfully at $pdfPath\n";
    } catch (Exception $e) {
        echo "Error generating PDF: " . $e->getMessage() . "\n";
    }
} else {
    echo "No permohonan found\n";
}
