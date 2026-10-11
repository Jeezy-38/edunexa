@extends('layouts.default')
@push('title', get_phrase('Onyesho la Miradi ya Wanafunzi (Student Showcase)') . ' | Edunexa')
@push('meta')
    <meta name="description" content="Tazama miradi halisi ya vitendo (websites, apps, design) iliyotengenezwa na wanafunzi wa Edunexa.">
@endpush
@push('css')
    <style>
        .showcase-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #4338ca 100%);
            padding: 55px 0 45px;
        }
        .project-card {
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
        }
        .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08) !important;
            border-color: #3b82f6;
        }
    </style>
@endpush
@section('content')
    <!-- Hero Header -->
    <section class="showcase-hero text-white text-center">
        <div class="container">
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold mb-3" style="font-size: 12px;">
                <i class="fa-solid fa-code me-1"></i> EDUNEXA STUDENT SHOWCASE
            </span>
            <h1 class="text-white fw-bold display-6 mb-3">Onyesho la Miradi Halisi ya Wanafunzi</h1>
            <p class="text-white-50 mx-auto mb-4" style="max-width: 650px; font-size: 15px;">
                Tazama mifumo, programu, na ubunifu uliotengenezwa na wanafunzi wetu baada ya kujifunza kozi za vitendo kwenye Edunexa.
            </p>

            @auth
                <div>
                    <a href="{{ route('my.projects') }}" class="btn btn-light text-primary rounded-pill px-4 py-2 fw-semibold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> {{ get_phrase('Weka Mradi Wako Hapa') }}
                    </a>
                </div>
            @endauth
        </div>
    </section>

    <!-- Showcase Projects Grid -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark">Miradi Yote ya Vitendo ({{ $total_projects }})</h5>
                <form action="{{ route('showcase') }}" method="GET" class="d-flex align-items-center gap-2">
                    <input type="text" name="search" class="form-control form-control-sm rounded-pill px-3" placeholder="{{ get_phrase('Tafuta mradi au teknolojia...') }}" value="{{ $search }}" style="width: 250px;">
                    <button class="btn btn-sm btn-primary rounded-pill px-3" type="submit">{{ get_phrase('Tafuta') }}</button>
                </form>
            </div>

            <div class="row g-4">
                @forelse ($projects as $project)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden project-card bg-white d-flex flex-column justify-content-between">
                            <div>
                                <!-- Thumbnail -->
                                <div class="position-relative bg-light" style="height: 190px;">
                                    @if (!empty($project->thumbnail))
                                        <img src="{{ asset($project->thumbnail) }}" alt="{{ $project->title }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="background: linear-gradient(135deg, #e2e8f0, #cbd5e1);">
                                            <i class="fa-solid fa-laptop-code fs-1 text-primary"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <!-- Author Header -->
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        @if ($project->user)
                                            <a href="{{ route('student.portfolio', $project->user->id) }}" class="d-flex align-items-center gap-2 text-decoration-none">
                                                <img src="{{ get_image($project->user->photo) }}" alt="{{ $project->user->name }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                                                <span class="small fw-semibold text-dark">{{ $project->user->name }}</span>
                                            </a>
                                        @endif
                                        <small class="text-muted" style="font-size: 11px;">{{ $project->created_at->format('M Y') }}</small>
                                    </div>

                                    <!-- Project Title -->
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 16px;">
                                        {{ $project->title }}
                                    </h5>

                                    <!-- Description -->
                                    <p class="text-muted small mb-3" style="font-size: 13px; line-height: 1.6;">
                                        {{ Str::limit(strip_tags($project->description), 110) }}
                                    </p>

                                    <!-- Tags -->
                                    @if (!empty($project->tags))
                                        <div class="mb-3">
                                            @foreach (explode(',', $project->tags) as $tag)
                                                <span class="badge bg-light text-muted border me-1 mb-1" style="font-size: 10px;">{{ trim($tag) }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Associated Course -->
                                    @if ($project->course)
                                        <div class="small text-muted mb-2" style="font-size: 11px;">
                                            <i class="fa-solid fa-graduation-cap text-primary me-1"></i> Kozi: <strong>{{ Str::limit($project->course->title, 35) }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer Links -->
                            <div class="p-3 bg-light border-top d-flex align-items-center justify-content-between">
                                @if (!empty($project->project_url))
                                    <a href="{{ $project->project_url }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold" style="font-size: 12px;">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Live Demo
                                    </a>
                                @else
                                    <span></span>
                                @endif

                                @if (!empty($project->github_url))
                                    <a href="{{ $project->github_url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold" style="font-size: 12px;" title="View Source Code">
                                        <i class="fa-brands fa-github me-1"></i> Code
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                            <div class="fs-1 text-muted mb-3"><i class="fa-solid fa-laptop-code"></i></div>
                            <h5 class="fw-bold mb-1">{{ get_phrase('Hakuna miradi iliyopatikana') }}</h5>
                            <p class="text-muted small mb-3">{{ get_phrase('Kuwa wa kwanza kuweka mradi wako kwenye onyesho hili!') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $projects->withQueryString()->links() }}
            </div>
        </div>
    </section>
@endsection
