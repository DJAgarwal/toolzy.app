<?php

namespace App\Services;

use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use Spatie\PdfToImage\Pdf;
use Illuminate\Http\UploadedFile;

class ToolService
{
    public function convertPdf(UploadedFile $file, string $type): string
    {
        $ext = $file->getClientOriginalExtension();
        $filename = Str::random(10) . '.' . $ext;
        $path = $file->storeAs('temp', $filename);
        $fullPath = storage_path('app/' . $path);

        $outputPath = storage_path("app/temp/converted-" . Str::random(10));

        switch ($type) {
            case 'pdf-to-jpg':
                $pdf = new Pdf($fullPath);
                $output = $outputPath . '.jpg';
                $pdf->saveImage($output);
                break;

            case 'jpg-to-pdf':
                $pdf = new Fpdi();
                $pdf->AddPage();
                $pdf->Image($fullPath, 10, 10, 190);
                $output = $outputPath . '.pdf';
                $pdf->Output($output, 'F');
                break;

            case 'pdf-to-word':
                // Placeholder
                $output = $outputPath . '.docx';
                copy($fullPath, $output);
                break;

            case 'word-to-pdf':
                // Placeholder
                $output = $outputPath . '.pdf';
                copy($fullPath, $output);
                break;

            default:
                throw new \InvalidArgumentException('Unsupported conversion type');
        }

        return $output;
    }
}
