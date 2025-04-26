@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h1 class="mb-4">Ingredient Sources</h1>

    <p>Understanding what goes into your skincare and haircare is powerful. At <strong>Natural Products India</strong>, we believe in science-backed facts — not marketing fluff.</p>

    <h3 class="mt-4">Where We Get Our Ingredient Data</h3>
    <ul>
        <li><strong>INCI Decoder</strong> – a popular, trustworthy source for cosmetic ingredient research.</li>
        <li><strong>PubChem</strong> – a database from the U.S. National Institutes of Health (NIH).</li>
        <li><strong>EWG’s Skin Deep Database</strong> – analyzes toxicity and safety of cosmetic ingredients.</li>
        <li><strong>Official Product Labels</strong> – we verify directly from packaging and company websites.</li>
    </ul>

    <h3 class="mt-4">How We Interpret This Data</h3>
    <p>We read research papers, study ingredient safety ratings, and check for red flags like carcinogens, endocrine disruptors, allergens, and artificial fragrances.</p>

    <p class="mt-4">We're constantly learning and evolving. If you ever spot incorrect info, we welcome your corrections or suggestions via <a href="{{ url('/contact') }}">our contact page</a>.</p>
</div>
@endsection
