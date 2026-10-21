@php
    $btn = 'inline-block rounded-lg px-3 py-[0.35rem] mx-[0.2rem] text-sm no-underline transition-colors';
    $normal = 'bg-gray-50 text-gray-700 hover:bg-gray-200 hover:text-gray-900';
    $actual = 'bg-[#32989D] text-white';
    $inactivo = 'bg-gray-50 text-gray-400 cursor-default';
    $ultima = $paginator->lastPage();
@endphp

<div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-3 border-t border-gray-100">
    <p class="text-sm text-gray-500">
        @if ($paginator->total() > 0)
            Mostrando registros del {{ $paginator->firstItem() }} al {{ $paginator->lastItem() }} de un total de
            {{ $paginator->total() }} registros
        @else
            Mostrando registros del 0 al 0 de un total de 0 registros
        @endif
    </p>

    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Paginación" class="flex flex-wrap items-center justify-center">
            {{-- Primero / Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="{{ $btn }} {{ $inactivo }}">Primero</span>
                <span class="{{ $btn }} {{ $inactivo }}">Anterior</span>
            @else
                <a href="{{ $paginator->url(1) }}" class="{{ $btn }} {{ $normal }}">Primero</a>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $btn }} {{ $normal }}">Anterior</a>
            @endif

            {{-- Números de página --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="{{ $btn }} {{ $inactivo }}">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $btn }} {{ $actual }}">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="{{ $btn }} {{ $normal }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Siguiente / Último --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $btn }} {{ $normal }}">Siguiente</a>
                <a href="{{ $paginator->url($ultima) }}" class="{{ $btn }} {{ $normal }}">Último</a>
            @else
                <span class="{{ $btn }} {{ $inactivo }}">Siguiente</span>
                <span class="{{ $btn }} {{ $inactivo }}">Último</span>
            @endif
        </nav>
    @endif
</div>
