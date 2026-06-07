<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;
use App\Helpers\PageHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;
use Spatie\PdfToImage\Pdf;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpgEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;

class ToolsController extends Controller
{
    public function index()
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('tools');
        $tools = Tool::all()->groupBy('category');
        $botKeywords = [
            'bot', 'crawl', 'spider', 'slurp', 'facebook', 'meta-externalagent',
            'headless', 'okhttp', 'go-http-client', 'secscan', 'python-requests',
            'wget', 'curl', 'node-fetch'
        ];
        $userAgent = request()->userAgent();
        $isBot = false;
        foreach ($botKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $isBot = true;
                break;
            }
        }
        $type = $isBot ? 'bot' : 'human';

        \Log::info('Page hit', [
            'type' => $type,
            'userAgent' => $userAgent,
            'path' => request()->path(),
            'referer' => request()->header('referer'),
        ]);
        return view('static.tools', array_merge($data, ['tools' => $tools]));
    }

    public function pdfConverter(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:pdf,jpg,jpeg,doc,docx',
        'type' => 'required|string'
    ]);

    $type = $request->input('type');
    $file = $request->file('file');
    $ext = $file->getClientOriginalExtension();
    $filename = Str::random(10) . '.' . $ext;
    $path = $file->storeAs('temp', $filename);

    $outputPath = storage_path("app/temp/converted-" . Str::random(10));

    switch ($type) {
        case 'pdf-to-jpg':
            $pdf = new Pdf(storage_path('app/' . $path));
            $output = $outputPath . '.jpg';
            $pdf->saveImage($output);
            break;

        case 'jpg-to-pdf':
            $pdf = new Fpdi();
            $pdf->AddPage();
            $pdf->Image(storage_path('app/' . $path), 10, 10, 190);
            $output = $outputPath . '.pdf';
            $pdf->Output($output, 'F');
            break;

        case 'pdf-to-word':
            // Basic mock: rename as .docx
            $output = $outputPath . '.docx';
            copy(storage_path('app/' . $path), $output);
            break;

        case 'word-to-pdf':
            // Placeholder: rename to .pdf (real implementation needs LibreOffice or external API)
            $output = $outputPath . '.pdf';
            copy(storage_path('app/' . $path), $output);
            break;

        default:
            return response()->json(['error' => 'Unsupported type'], 400);
    }

    return response()->download($output)->deleteFileAfterSend(true);
}

}