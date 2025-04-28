<h2 class="fw-semibold mb-3">Frequently Asked Questions (FAQ)</h2>
<div class="accordion" id="faqAccordion">
    @foreach ($tool['faqs'] as $faq)
        @php
            $faqId = 'faq-' . $loop->index;
        @endphp
        <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="{{ $faqId }}">
                <button class="accordion-button @if (!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse-{{ $loop->index }}">
                    {{ $faq['question'] }}
                </button>
            </h2>
            <div id="collapse-{{ $loop->index }}" class="accordion-collapse collapse @if ($loop->first) show @endif" aria-labelledby="{{ $faqId }}" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    {{ $faq['answer'] }}
                </div>
            </div>
        </div>
    @endforeach
</div>