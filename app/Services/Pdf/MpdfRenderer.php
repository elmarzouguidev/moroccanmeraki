<?php

namespace App\Services\Pdf;

use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class MpdfRenderer
{
    public function render(string $html): string
    {
        $tempDirectory = storage_path('app/mpdf');
        File::ensureDirectoryExists($tempDirectory);

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => $tempDirectory,
            'margin_left' => 16,
            'margin_right' => 16,
            'margin_top' => 16,
            'margin_bottom' => 16,
        ]);

        $pdf->WriteHTML($html);

        return $pdf->Output('', Destination::STRING_RETURN);
    }
}
