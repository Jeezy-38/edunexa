@extends('layouts.default')
@push('title', get_phrase('Fursa za Kazi na Mafunzo ya Vitendo (Career Hub)') . ' | Edunexa')
@push('meta')
    <meta name="description" content="Pata nafasi za kazi za teknolojia, biashara ya kidijitali, na mafunzo ya vitendo (internships) Tanzania kupitia Edunexa Career Hub.">
@endpush
@push('css')
    <style>
        .career-hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #312e81 100%);
            padding: 60px 0 50px;
        }
        .job-card {
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
        }
        .job-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1) !important;
            transform: translateY(-3px);
        }
    </style>
@endpush
@section('content')
    <!-- Hero Header -->
    <section class="career-hero-bg text-white">
        <div class="container text-center">
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold mb-3" style="font-size: 12px;">
                <i class="fa-solid fa-briefcase me-1"></i> EDUNEXA CAREER HUB
            </span>
            <h1 class="text-white fw-bold display-6 mb-3">Fursa za Kazi na Mafunzo ya Vitendo (Internships)</h1>
            <p class="text-white-50 mx-auto mb-4" style="max-width: 650px; font-size: 15px;">
                Unganishwa moja kwa moja na waajiri wanaotafuta ujuzi halisi wa kidijitali. Wanafunzi wa Edunexa wenye vyeti vilivyothibitishwa wanapewa kipaumbele cha kwanza!
            </p>

            <!-- Search Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ route('careers') }}" method="GET" class="card p-2 border-0 shadow-lg rounded-pill bg-white">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control border-0 shadow-none" placeholder="{{ get_phrase('Tafuta kazi, ujuzi, au jina la kampuni...') }}" value="{{ $search }}">
                            <button class="btn btn-primary rounded-pill px-4 fw-semibold" type="submit">
                                {{ get_phrase('Tafuta') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- Filter Pills -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pb-4 mb-3 border-bottom">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-muted small fw-semibold me-2"><i class="fa-solid fa-filter me-1"></i>{{ get_phrase('Aina:') }}</span>
                    <a href="{{ route('careers', ['type' => 'all', 'location' => $selected_location, 'search' => $search]) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ $selected_type == 'all' ? 'btn-primary' : 'btn-white border text-dark' }}">
                        {{ get_phrase('Zote') }}
                    </a>
                    <a href="{{ route('careers', ['type' => 'Internship', 'location' => $selected_location, 'search' => $search]) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ $selected_type == 'Internship' ? 'btn-primary' : 'btn-white border text-dark' }}">
                        🎓 {{ get_phrase('Internships') }}
                    </a>
                    <a href="{{ route('careers', ['type' => 'Full-time', 'location' => $selected_location, 'search' => $search]) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ $selected_type == 'Full-time' ? 'btn-primary' : 'btn-white border text-dark' }}">
                        💼 {{ get_phrase('Full-Time') }}
                    </a>
                    <a href="{{ route('careers', ['type' => 'Freelance', 'location' => $selected_location, 'search' => $search]) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ $selected_type == 'Freelance' ? 'btn-primary' : 'btn-white border text-dark' }}">
                        ⚡ {{ get_phrase('Freelance / Remote') }}
                    </a>
                </div>
                <div>
                    <span class="text-muted small">Nafasi zilizopo: <strong>{{ $total_openings }}</strong></span>
                </div>
            </div>

            <!-- Jobs List Grid -->
            <div class="row g-4">
                @forelse ($jobs as $job)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 job-card bg-white d-flex flex-column justify-content-between">
                            <div>
                                <!-- Company & Job Type -->
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 16px;">
                                            {{ strtoupper(substr($job->company_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px;">{{ $job->company_name }}</h6>
                                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $job->location }}</small>
                                        </div>
                                    </div>
                                    <span class="badge {{ $job->job_type == 'Internship' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} rounded-pill px-3 py-1" style="font-size: 11px;">
                                        {{ $job->job_type }}
                                    </span>
                                </div>

                                <!-- Job Title -->
                                <h5 class="fw-bold text-dark mb-2" style="font-size: 16px;">
                                    <a href="{{ route('career.details', $job->id) }}" class="text-dark text-decoration-none">
                                        {{ $job->title }}
                                    </a>
                                </h5>

                                <!-- Snippet -->
                                <p class="text-muted small mb-3" style="font-size: 13px; line-height: 1.6;">
                                    {{ Str::limit(strip_tags($job->description), 110) }}
                                </p>

                                <!-- Salary Range -->
                                @if (!empty($job->salary_range))
                                    <div class="mb-3 text-success fw-semibold small">
                                        <i class="fa-solid fa-money-bill-wave me-1"></i> {{ $job->salary_range }}
                                    </div>
                                @endif

                                <!-- Recommended Course Link -->
                                @if ($job->relatedCourse)
                                    <div class="alert alert-light border p-2 rounded-3 mb-3 small" style="font-size: 11px;">
                                        <span class="text-primary fw-semibold"><i class="fa-solid fa-lightbulb me-1"></i> Kozi Inayopendekezwa:</span>
                                        <a href="{{ route('course.details', $job->relatedCourse->slug) }}" class="d-block text-dark fw-bold text-truncate mt-1">
                                            {{ $job->relatedCourse->title }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer CTA -->
                            <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="fa-regular fa-clock me-1"></i>{{ $job->created_at->diffForHumans() }}
                                </small>
                                <a href="{{ route('career.details', $job->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                    {{ get_phrase('Tazama & Omba') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                            <div class="fs-1 text-muted mb-3"><i class="fa-solid fa-briefcase"></i></div>
                            <h5 class="fw-bold mb-1">{{ get_phrase('Hakuna fursa zilizopatikana kwa kigezo hiki') }}</h5>
                            <p class="text-muted small mb-3">{{ get_phrase('Jaribu kubadilisha maneno ya utafutaji au aina ya kazi.') }}</p>
                            <div>
                                <a href="{{ route('careers') }}" class="btn btn-primary rounded-pill px-4">{{ get_phrase('Orodha Kamili') }}</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $jobs->withQueryString()->links() }}
            </div>
        </div>
    </section>
@endsection
