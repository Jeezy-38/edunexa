@extends('layouts.default')
@push('title', $job->title . ' - ' . $job->company_name . ' | Edunexa Career Hub')
@push('meta')
    <meta name="description" content="Apply for {{ $job->title }} at {{ $job->company_name }} on Edunexa Career Hub.">
@endpush
@section('content')
    <!-- Breadcrumb Area -->
    <section class="py-4 bg-light border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 14px;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">{{ get_phrase('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('careers') }}" class="text-decoration-none">{{ get_phrase('Careers') }}</a></li>
                    <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 300px;">{{ $job->title }}</li>
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
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white border">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 58px; height: 58px; font-size: 22px;">
                                    {{ strtoupper(substr($job->company_name, 0, 2)) }}
                                </div>
                                <div>
                                    <h2 class="fw-bold mb-1 text-dark" style="font-size: 24px;">{{ $job->title }}</h2>
                                    <div class="d-flex align-items-center gap-2 text-muted small flex-wrap" style="font-size: 14px;">
                                        <span class="fw-bold text-dark">{{ $job->company_name }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $job->location }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fa-regular fa-clock me-1"></i>{{ $job->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="badge {{ $job->job_type == 'Internship' ? 'bg-success' : 'bg-primary' }} rounded-pill px-4 py-2" style="font-size: 13px;">
                                {{ $job->job_type }}
                            </span>
                        </div>

                        <div class="row g-3 pt-3 border-top mt-2">
                            @if (!empty($job->salary_range))
                                <div class="col-sm-6">
                                    <small class="text-muted d-block" style="font-size: 13px;">{{ get_phrase('Salary / Compensation:') }}</small>
                                    <strong class="text-success fs-6">{{ $job->salary_range }}</strong>
                                </div>
                            @endif
                            @if (!empty($job->deadline))
                                <div class="col-sm-6">
                                    <small class="text-muted d-block" style="font-size: 13px;">{{ get_phrase('Application Deadline:') }}</small>
                                    <strong class="text-dark fs-6">{{ date('d M, Y', strtotime($job->deadline)) }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Job Description Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white border">
                        <h4 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 20px;">
                            <i class="fa-solid fa-file-lines text-primary"></i>
                            <span>{{ get_phrase('Job Description & Responsibilities') }}</span>
                        </h4>
                        <div class="text-muted" style="font-size: 15px; line-height: 1.85;">
                            {!! removeScripts($job->description) !!}
                        </div>
                    </div>

                    <!-- Requirements Card -->
                    @if (!empty($job->requirements))
                        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white border">
                            <h4 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 20px;">
                                <i class="fa-solid fa-list-check text-primary"></i>
                                <span>{{ get_phrase('Requirements & Qualifications') }}</span>
                            </h4>
                            <div class="text-muted" style="font-size: 15px; line-height: 1.85;">
                                {!! removeScripts($job->requirements) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Application Box -->
                    @php
                        $userPortfolioUrl = auth()->check() ? route('student.portfolio', auth()->user()->id) : url('/');
                        $waText = urlencode("Hello " . $job->company_name . "! I am applying for the " . $job->title . " position (via Edunexa Career Hub)." . (auth()->check() ? " You can view my verified digital portfolio & certificates here: " . $userPortfolioUrl : ""));
                        $mailSubject = urlencode("Job Application: " . $job->title . " - via Edunexa");
                        $mailBody = urlencode("Hello,\n\nI am applying for the " . $job->title . " position posted on Edunexa Career Hub.\n\nMy verified Edunexa Digital Portfolio: " . $userPortfolioUrl . "\n\nThank you.");
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-light border-primary border">
                        <h4 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2" style="font-size: 20px;">
                            <i class="fa-solid fa-paper-plane text-primary"></i>
                            <span>{{ get_phrase('How to Apply') }}</span>
                        </h4>
                        <p class="text-muted mb-4" style="font-size: 14px; line-height: 1.7;">
                            {{ get_phrase('Submit your application directly to the employer. Your verified Edunexa Portfolio link will be included to showcase your skills, certifications, and live projects.') }}
                        </p>

                        <div class="d-flex flex-wrap gap-3">
                            @if ($job->apply_type == 'whatsapp' || !empty($job->apply_value))
                                @php
                                    $cleanWa = preg_replace('/[^0-9]/', '', $job->apply_value);
                                    if (str_starts_with($cleanWa, '0') && strlen($cleanWa) === 10) {
                                        $cleanWa = '255' . substr($cleanWa, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanWa }}?text={{ $waText }}" target="_blank" class="btn btn-success rounded-pill px-4 py-3 fw-bold d-inline-flex align-items-center gap-2 shadow-2xs">
                                    <i class="fa-brands fa-whatsapp fs-5"></i>
                                    <span>{{ get_phrase('Apply via WhatsApp') }}</span>
                                </a>
                            @endif

                            @if (filter_var($job->apply_value, FILTER_VALIDATE_EMAIL))
                                <a href="mailto:{{ $job->apply_value }}?subject={{ $mailSubject }}&body={{ $mailBody }}" class="btn btn-primary rounded-pill px-4 py-3 fw-semibold d-inline-flex align-items-center gap-2">
                                    <i class="fa-regular fa-envelope fs-6"></i>
                                    <span>{{ get_phrase('Apply via Email') }}</span>
                                </a>
                            @elseif(str_starts_with($job->apply_value, 'http'))
                                <a href="{{ $job->apply_value }}" target="_blank" class="btn btn-primary rounded-pill px-4 py-3 fw-semibold d-inline-flex align-items-center gap-2">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    <span>{{ get_phrase('Apply on Company Website') }}</span>
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
                                💡 {{ get_phrase('Build Skills First') }}
                            </span>
                            <h5 class="text-white fw-bold mb-2">{{ get_phrase('Recommended Preparation Course:') }}</h5>
                            <p class="text-white-50 small mb-3" style="line-height: 1.6;">
                                {{ get_phrase('Students who complete this course and earn their certificate have a significant hiring advantage!') }}
                            </p>
                            <div class="bg-white rounded-3 p-3 text-dark mb-3">
                                <h6 class="fw-bold mb-1" style="font-size: 14px;">{{ $related_course->title }}</h6>
                                <span class="text-primary fw-bold" style="font-size: 13px;">
                                    {{ $related_course->is_paid ? currency($related_course->price) : get_phrase('Free') }}
                                </span>
                            </div>
                            <a href="{{ route('course.details', $related_course->slug) }}" class="btn btn-light text-primary fw-bold rounded-pill w-100 py-2">
                                {{ get_phrase('View Course & Enroll') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif

                    <!-- User Portfolio Reminder -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border">
                        <h6 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2">
                            <i class="fa-regular fa-id-card text-primary"></i>
                            <span>{{ get_phrase('Your Digital Portfolio') }}</span>
                        </h6>
                        <p class="text-muted small mb-3" style="line-height: 1.6;">
                            {{ get_phrase('Complete your profile and publish projects on Edunexa so employers can verify your practical capabilities.') }}
                        </p>
                        @auth
                            <a href="{{ route('student.portfolio', auth()->user()->id) }}" class="btn btn-outline-primary rounded-pill btn-sm w-100 fw-semibold py-2">
                                <i class="fa-solid fa-eye me-1"></i> {{ get_phrase('View My Portfolio') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill btn-sm w-100 fw-semibold py-2">
                                {{ get_phrase('Log In to Your Account') }}
                            </a>
                        @endauth
                    </div>

                    <!-- Similar Jobs -->
                    @if (count($similar_jobs) > 0)
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border">
                            <h6 class="fw-bold mb-3 text-dark">{{ get_phrase('Similar Opportunities') }}</h6>
                            <div class="d-flex flex-column gap-3">
                                @foreach ($similar_jobs as $simJob)
                                    <div class="border-bottom pb-2">
                                        <h6 class="fw-bold mb-1" style="font-size: 14px;">
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
