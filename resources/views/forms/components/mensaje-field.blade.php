<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        @if ($type=="entrada")
            <h1>mensaje cuando es entrada</h1>
        @endif
        @if ($type=="ajuste")
            <h1>mensaje cuando es ajuste</h1>
        @endif
    </div>
</x-dynamic-component>
