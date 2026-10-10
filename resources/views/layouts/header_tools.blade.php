{{-- Language switcher + dark/light toggle (placed beside Login) --}}
@php
    $tools_lang = strtolower(session('language') ?? get_settings('language'));
@endphp
<div class="header-tools d-flex align-items-center gap-2 me-2 {{ $tools_class ?? '' }}">
    <form action="{{ route('select.lng') }}" method="get" class="lng-pill">
        <i class="fa-solid fa-globe"></i>
        <select name="language" class="js-lng-selector" aria-label="{{ get_phrase('Language') }}">
            @foreach (App\Models\Language::all() as $lng)
                <option value="{{ $lng->name }}" @selected(strtolower($lng->name) == $tools_lang)>{{ $lng->name }}</option>
            @endforeach
        </select>
    </form>
    <button type="button" class="theme-toggle" data-theme-toggle aria-label="{{ get_phrase('Toggle dark mode') }}" title="{{ get_phrase('Toggle dark mode') }}">
        <i class="fa-solid fa-moon icon-moon"></i>
        <i class="fa-solid fa-sun icon-sun"></i>
    </button>
</div>
