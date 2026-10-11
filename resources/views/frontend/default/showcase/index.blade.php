@extends('layouts.default')
@push('title', get_phrase('Student Project Showcase') . ' | Edunexa')
@push('meta')
    <meta name="description" content="Explore real practical projects, web applications, and software designs created by Edunexa students.">
@endpush
@push('css')
    <style>
        .showcase-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #4338ca 100%);
            padding: 65px 0 55px;
        }
        .project-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            background: #ffffff;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08) !important;
            border-color: #3b82f6;
        }
        .project-thumb-box {
            height: 210px;
            overflow: hidden;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }
        .project-thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .project-card:hover .project-thumb-box img {
            transform: scale(1.04);
        }
        .project-title {
            font-size: 19px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.45;
            margin-bottom: 12px;
        }
        .project-description {
            font-size: 14px;
            line-height: 1.75;
            color: #475569;
            margin-bottom: 18px;
        }
        .tech-pill {
            display: inline-block;
            background: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 11px;
            border-radius: 8px;
            margin-right: 6px;
            margin-bottom: 8px;
        }
    </style>
@endpush
@section('content')
    <!-- Hero Header -->
    <section class="showcase-hero text-white text-center">
        <div class="container">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3" style="font-size: 12px; letter-spacing: 0.5px;">
                <i class="fa-solid fa-code me-1"></i> EDUNEXA STUDENT SHOWCASE
            </span>
            <h1 class="text-white fw-bold display-6 mb-3">
                {{ get_phrase('Student Project Showcase') }}
            </h1>
            <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 16px; line-height: 1.7;">
                {{ get_phrase('Explore practical web systems, digital applications, and designs crafted by students using industry-standard technologies learned on Edunexa.') }}
            </p>

            @auth
                <div>
                    <a href="{{ route('my.projects') }}" class="btn btn-light text-primary rounded-pill px-4 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ get_phrase('Submit Your Project') }}</span>
                    </a>
                </div>
            @endauth
        </div>
    </section>

    <!-- Showcase Projects Grid -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pb-3 mb-4 border-bottom">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">{{ get_phrase('All Projects') }}</h4>
                    <p class="text-muted small mb-0">{{ get_phrase('Showing practical portfolio work by our active learners') }} ({{ $total_projects }})</p>
                </div>
                <form action="{{ route('showcase') }}" method="GET" class="d-flex align-items-center gap-2">
                    <input type="text" name="search" class="form-control rounded-pill px-3 py-2 shadow-2xs border-0" placeholder="{{ get_phrase('Search by project name or tech...') }}" value="{{ $search }}" style="width: 280px; font-size: 14px;">
                    <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" type="submit">{{ get_phrase('Search') }}</button>
                </form>
            </div>

            <div class="row g-4">
                @forelse ($projects as $project)
                    <div class="col-lg-6 col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm project-card d-flex flex-column justify-content-between overflow-hidden">
                            <div>
                                <!-- Thumbnail Box -->
                                <div class="project-thumb-box position-relative">
                                    @if (!empty($project->thumbnail))
                                        <img src="{{ asset($project->thumbnail) }}" alt="{{ $project->title }}">
                                    @else
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                                            <i class="fa-solid fa-laptop-code fs-1 text-primary mb-2"></i>
                                            <span class="text-white-50 small fw-medium">Live Practical Project</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <!-- Author Header Row -->
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                        @if ($project->user)
                                            <a href="{{ route('student.portfolio', $project->user->id) }}" class="d-flex align-items-center gap-2 text-decoration-none">
                                                <img src="{{ get_image($project->user->photo) }}" alt="{{ $project->user->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #e2e8f0;">
                                                <span class="small fw-bold text-dark">{{ $project->user->name }}</span>
                                            </a>
                                        @endif
                                        <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 11px;">
                                            <i class="fa-regular fa-calendar me-1"></i>{{ $project->created_at->format('M Y') }}
                                        </span>
                                    </div>

                                    <!-- Project Title -->
                                    <h3 class="project-title">
                                        {{ $project->title }}
                                    </h3>

                                    <!-- Description with generous spacing -->
                                    <p class="project-description">
                                        {{ Str::limit(strip_tags($project->description), 150) }}
                                    </p>

                                    <!-- Tags with breathing room -->
                                    @if (!empty($project->tags))
                                        <div class="mb-3 d-flex flex-wrap">
                                            @foreach (explode(',', $project->tags) as $tag)
                                                <span class="tech-pill">{{ trim($tag) }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Associated Course -->
                                    @if ($project->course)
                                        <div class="p-2 bg-light rounded-3 mb-2 small text-muted d-flex align-items-center gap-2" style="font-size: 12px;">
                                            <i class="fa-solid fa-graduation-cap text-primary fs-6"></i>
                                            <span>Course: <strong class="text-dark">{{ Str::limit($project->course->title, 40) }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer Links -->
                            <div class="p-3 px-4 bg-light border-top d-flex align-items-center justify-content-between gap-2">
                                @if (!empty($project->project_url))
                                    <a href="{{ $project->project_url }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 13px;">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span>Live Demo</span>
                                    </a>
                                @else
                                    <span></span>
                                @endif

                                @if (!empty($project->github_url))
                                    <a href="{{ $project->github_url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 13px;" title="View Source Code">
                                        <i class="fa-brands fa-github"></i>
                                        <span>Code</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                            <div class="fs-1 text-muted mb-3"><i class="fa-solid fa-laptop-code"></i></div>
                            <h4 class="fw-bold mb-2">{{ get_phrase('No projects found') }}</h4>
                            <p class="text-muted small mb-0">{{ get_phrase('Be the first student to publish your practical project here!') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $projects->withQueryString()->links() }}
            </div>
        </div>
    </section>
@endsection
