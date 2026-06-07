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

use App\Http\Requests\PdfConverterRequest;
use App\Services\ToolService;

class ToolsController extends Controller
{
    protected ToolService $toolService;

    public function __construct(ToolService $toolService)
    {
        $this->toolService = $toolService;
    }

    public function index()
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('tools');
        $tools = Tool::all()->groupBy('category');
        return view('static.tools', array_merge($data, ['tools' => $tools]));
    }

    public function pdfConverter(PdfConverterRequest $request)
    {
        try {
            $output = $this->toolService->convertPdf($request->file('file'), $request->input('type'));
            return response()->download($output)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('PDF Conversion Error: ' . $e->getMessage());
            return back()->withErrors(['file' => 'An error occurred during conversion. Please try again.']);
        }
    }
}