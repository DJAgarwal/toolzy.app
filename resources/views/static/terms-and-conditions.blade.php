@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1 class="mb-4">Terms and Conditions</h1>

    <p>Welcome to <strong>Toolzy</strong>. By accessing or using this website, you agree to be bound by the following terms and conditions. If you do not agree with any part of these terms, please do not use our website.</p>

    <h3 class="mt-4">1. Free Use of Tools</h3>
    <p>All tools on Toolzy are provided for free and are intended to assist with everyday tasks. We do not guarantee perfect accuracy or uninterrupted availability.</p>

    <h3 class="mt-4">2. No Liability</h3>
    <p>Toolzy is not liable for any losses, damages, or issues that may arise from the use or misuse of our tools. Please verify all outputs before using them for critical purposes.</p>

    <h3 class="mt-4">3. Intellectual Property</h3>
    <p>The design, content, and layout of Toolzy are the property of the site owner. You may not copy or reproduce any part of this website without permission.</p>

    <h3 class="mt-4">4. Changes to Terms</h3>
    <p>We may update these terms from time to time. Continued use of the website means you accept any changes.</p>

    <p class="mt-4">If you have any questions about these terms, please reach out via our <a href="{{ url('/contact') }}">Contact</a> page.</p>
</div>
@endsection