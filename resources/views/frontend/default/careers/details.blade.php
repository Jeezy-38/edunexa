@extends('layouts.default')
@push('title', $job->title . ' - ' . $job->company_name . ' | Edunexa Career Hub')
@push('meta')
    <meta name="description" content="Omba nafasi ya {{ $job->title }} katika kampuni ya {{ $job->company_name }} kupitia Edunexa Career Hub.">
@endpush
@section('content')
    <!-- Breadcrumb Area -->
    <section class="py-4 bg-light border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 13px;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Nyumbani') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('careers') }}">{{ get_phrase('Career Hub') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $job->title }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Details Main Area -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <!-- Left Main Content -->
                <div class="col-lg-8">
                    <!-- Header Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px; font-size: 20px;">
                                    {{ strtoupper(substr($job->company_name, 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-1 text-dark">{{ $job->title }}</h4>
                                    <div class="d-flex align-items-center gap-2 text-muted small flex-wrap">
                                        <span class="fw-semibold text-dark">{{ $job->company_name }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $job->location }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fa-regular fa-clock me-1"></i>{{ $job->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="badge {{ $job->job_type == 'Internship' ? 'bg-success' : 'bg-primary' }} rounded-pill px-3 py-2" style="font-size: 12px;">
                                {{ $job->job_type }}
                            </span>
                        </div>

                        <div class="row g-2 pt-3 border-top mt-2">
                            @if (!empty($job->salary_range))
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">{{ get_phrase('Mshahara / Malipo:') }}</small>
                                    <strong class="text-success">{{ $job->salary_range }}</strong>
                                </div>
                            @endif
                            @if (!empty($job->deadline))
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">{{ get_phrase('Mwisho wa Maombi (Deadline):') }}</small>
                                    <strong class="text-dark">{{ date('d M, Y', strtotime($job->deadline)) }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Job Description Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 18px;">
                            <i class="fa-solid fa-file-lines text-primary"></i>
                            {{ get_phrase('Maelezo na Majukumu ya Kazi') }}
                        </h5>
                        <div class="text-muted" style="font-size: 15px; line-height: 1.8;">
                            {!! removeScripts($job->description) !!}
                        </div>
                    </div>

                    <!-- Requirements Card -->
                    @if (!empty($job->requirements))
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 18px;">
                                <i class="fa-solid fa-list-check text-primary"></i>
                                {{ get_phrase('Vigezo na Ujuzi Unaohitajika') }}
                            </h5>
                            <div class="text-muted" style="font-size: 15px; line-height: 1.8;">
                                {!! removeScripts($job->requirements) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Application Box -->
                    @php
                        $userPortfolioUrl = auth()->check() ? route('student.portfolio', auth()->user()->id) : url('/');
                        $waText = urlencode("Habari " . $job->company_name . "! Ninaomba nafasi ya " . $job->title . " (Kupitia Edunexa Career Hub)." . (auth()->check() ? " Unaweza kutazama wasifu wangu wa kidijitali na vyeti hapa: " . $userPortfolioUrl : ""));
                        $mailSubject = urlencode("Maombi ya Kazi: " . $job->title . " - Kupitia Edunexa");
                        $mailBody = urlencode("Habari,\n\nNinaomba nafasi ya " . $job->title . " iliyotangazwa kwenye Edunexa Career Hub.\n\nWasifu wangu wa kidijitali wa Edunexa: " . $userPortfolioUrl . "\n\nAsante.");
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border-primary border">
                        <h5 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-primary"></i>
                            {{ get_phrase('Jinsi ya Kutuma Maombi (Apply Now)') }}
                        </h5>
                        <p class="text-muted small mb-3">
                            Wasilisha maombi yako moja kwa moja kwa mwajiri. Wasifu wako wa **Edunexa Portfolio** utatumwa pamoja ili kuthibitisha ujuzi na vyeti vyako!
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            @if ($job->apply_type == 'whatsapp' || !empty($job->apply_value))
                                @php
                                    $cleanWa = preg_replace('/[^0-9]/', '', $job->apply_value);
                                    if (str_starts_with($cleanWa, '0') && strlen($cleanWa) === 10) {
                                        $cleanWa = '255' . substr($cleanWa, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanWa }}?text={{ $waText }}" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-2xs">
                                    <i class="fa-brands fa-whatsapp fs-5"></i>
                                    <span>{{ get_phrase('Tuma Maombi kwa WhatsApp') }}</span>
                                </a>
                            @endif

                            @if (filter_var($job->apply_value, FILTER_VALIDATE_EMAIL))
                                <a href="mailto:{{ $job->apply_value }}?subject={{ $mailSubject }}&body={{ $mailBody }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                                    <i class="fa-regular fa-envelope"></i>
                                    <span>{{ get_phrase('Tuma kwa Barua Pepe') }}</span>
                                </a>
                            @elseif(str_starts_with($job->apply_value, 'http'))
                                <a href="{{ $job->apply_value }}" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    <span>{{ get_phrase('Fungua Tovuti ya Maombi') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-lg-4">
                    <!-- Related Course Box -->
                    @if ($related_course)
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white" style="background: linear-gradient(135deg, #1e3a8a, #4338ca, #6366f1);">
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold mb-2" style="font-size: 11px;">
                                💡 Jenga Ujuzi Kwanza
                            </span>
                            <h5 class="text-white fw-bold mb-2">Kozi Inayopendekezwa kwa Kazi Hii:</h5>
                            <p class="text-white-50 small mb-3">
                                Wanafunzi waliomaliza kozi hii na kupata cheti wanayo nafasi kubwa zaidi ya kuchaguliwa!
                            </p>
                            <div class="bg-white rounded-3 p-3 text-dark mb-3">
                                <h6 class="fw-bold mb-1" style="font-size: 14px;">{{ $related_course->title }}</h6>
                                <span class="text-primary fw-bold" style="font-size: 13px;">
                                    {{ $related_course->is_paid ? currency($related_course->price) : 'Bure' }}
                                </span>
                            </div>
                            <a href="{{ route('course.details', $related_course->slug) }}" class="btn btn-light text-primary fw-bold rounded-pill w-100 py-2">
                                {{ get_phrase('Tazama Kozi Hii') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif

                    <!-- User Portfolio Reminder -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <h6 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2">
                            <i class="fa-regular fa-id-card text-primary"></i>
                            {{ get_phrase('Wasifu Wako wa Kidijitali') }}
                        </h6>
                        <p class="text-muted small mb-3">
                            Kamilisha wasifu wako na uweke miradi kwenye Edunexa ili waajiri waone ujuzi wako kwa vitendo.
                        </p>
                        @auth
                            <a href="{{ route('student.portfolio', auth()->user()->id) }}" class="btn btn-outline-primary rounded-pill btn-sm w-100 fw-semibold">
                                <i class="fa-solid fa-eye me-1"></i> {{ get_phrase('Tazama Portfolio Yangu') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill btn-sm w-100 fw-semibold">
                                {{ get_phrase('Ingia Kwenye Akaunti') }}
                            </a>
                        @endauth
                    </div>

                    <!-- Similar Jobs -->
                    @if (count($similar_jobs) > 0)
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <h6 class="fw-bold mb-3 text-dark">{{ get_phrase('Fursa Nyingine Zinazofanana') }}</h6>
                            <div class="d-flex flex-column gap-3">
                                @foreach ($similar_jobs as $simJob)
                                    <div class="border-bottom pb-2">
                                        <h6 class="fw-bold mb-1" style="font-size: 13px;">
                                            <a href="{{ route('career.details', $simJob->id) }}" class="text-dark text-decoration-none">
                                                {{ $simJob->title }}
                                            </a>
                                        </h6>
                                        <small class="text-muted d-block">{{ $simJob->company_name }} &bull; {{ $simJob->location }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
