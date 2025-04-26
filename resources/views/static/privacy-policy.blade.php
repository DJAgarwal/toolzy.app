@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1 class="mb-4">Privacy Policy</h1>

    <p><strong>Toolzy</strong> is committed to protecting your privacy. We do not collect any personal information unless you voluntarily choose to contact us.</p>

    <h3 class="mt-4">What We Don’t Do</h3>
    <ul>
        <li>We don’t require sign-ups or logins.</li>
        <li>We don’t track or store your files or data after processing.</li>
        <li>We don’t sell or share your information with third parties.</li>
    </ul>

    <h3 class="mt-4">What We May Collect</h3>
    <p>Basic anonymous usage data (like browser type, device info, or general analytics) may be collected to help us improve the website — but this data is never linked to you personally.</p>

    <h3 class="mt-4">Third-Party Services</h3>
    <p>We may use third-party services like analytics tools or advertising providers in the future. If so, we will update this policy to reflect those changes.</p>

    <p class="mt-4">By using Toolzy, you agree to this Privacy Policy. If you have any questions or concerns, please <a href="{{ url('/contact') }}">contact us</a>.</p>
</div>
@endsection
