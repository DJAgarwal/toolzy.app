@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1 class="mb-4">Disclaimer</h1>

    <p>The information and tools provided on <strong>Toolzy</strong> are for general informational and utility purposes only. While we strive to ensure our tools are accurate and reliable, we make no guarantees regarding the results or outcomes they produce.</p>

    <p class="mt-3">By using this website, you agree that Toolzy and its creators are not responsible for any direct or indirect losses, damages, or issues that may arise from your use of any tool or information provided here.</p>

    <p class="mt-3">Always double-check your outputs if you are using them for critical or sensitive tasks. Toolzy is a free platform built to help, but it comes with no warranties.</p>

    <p class="mt-4">If you notice any bugs or errors in our tools, please reach out via our <a href="{{ url('/contact') }}">Contact</a> page — we appreciate your help in making Toolzy better!</p>
</div>
@endsection