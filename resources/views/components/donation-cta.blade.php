@props([
    'variant' => 'standard',
    'class' => ''
])

<div class="global-donation-cta {{ $class }}">
    <x-donation-widget :variant="$variant" />
</div>
