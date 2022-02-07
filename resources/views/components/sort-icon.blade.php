@props(['sortBy', 'sortAsc', 'sortFiled'])
@if ( $sortBy == $sortFiled)
    @if ( $sortAsc )
        <span class="w-4 h-4 ml-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </span>
    @endif

    @if ( !$sortAsc )
        <span class="w-4 h-4 ml-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    @endif
@endif