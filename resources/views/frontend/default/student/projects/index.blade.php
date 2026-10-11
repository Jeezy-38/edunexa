@extends('layouts.default')
@push('title', get_phrase('Miradi Yangu ya Vitendo') . ' | Edunexa')
@push('meta')@endpush
@push('css')@endpush
@section('content')
    <section class="course-content">
        <div class="profile-banner-area"></div>
        <div class="container profile-banner-area-container">
            <div class="row">
                @include('frontend.default.student.left_sidebar')

                <div class="col-lg-9">
                    <!-- Header with Add Project Button -->
                    <div class="my-panel message-panel mb-4 p-4 bg-white rounded-3 shadow-2xs border">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div>
                                <h4 class="g-title mb-1">{{ get_phrase('Miradi Yangu ya Vitendo (Student Projects)') }}</h4>
                                <p class="text-muted small mb-0">{{ get_phrase('Ongeza miradi uliyotengeneza ili ionekane kwenye Wasifu wako wa Umma na Showcase ya Edunexa.') }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('student.portfolio', auth()->user()->id) }}" target="_blank" class="btn btn-outline-primary rounded-pill btn-sm fw-semibold">
                                    <i class="fa-solid fa-eye me-1"></i> {{ get_phrase('Tazama Portfolio Yangu') }}
                                </a>
                                <button type="button" class="btn btn-primary rounded-pill btn-sm fw-semibold" data-bs-toggle="collapse" data-bs-target="#addNewProjectForm">
                                    <i class="fa-solid fa-plus me-1"></i> {{ get_phrase('Ongeza Mradi') }}
                                </button>
                            </div>
                        </div>

                        <!-- Add New Project Form (Collapsible) -->
                        <div class="collapse mb-4" id="addNewProjectForm">
                            <div class="card card-body bg-light border-primary border rounded-3 p-4">
                                <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-cloud-arrow-up text-primary"></i>
                                    {{ get_phrase('Wasilisha Mradi Mpya') }}
                                </h6>
                                <form action="{{ route('my.projects.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">{{ get_phrase('Jina la Mradi') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" placeholder="k.m. Mfumo wa Mauzo ya Dukani (POS)" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">{{ get_phrase('Kozi Uliyosomea (Hiari)') }}</label>
                                            <select name="course_id" class="form-select">
                                                <option value="">{{ get_phrase('-- Chagua Kozi Husika --') }}</option>
                                                @foreach ($enrolled_courses as $c)
                                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">{{ get_phrase('Live Demo Link (URL ya Tovuti/App)') }}</label>
                                            <input type="url" name="project_url" class="form-control" placeholder="https://mradiwangu.com">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">{{ get_phrase('GitHub / Code URL') }}</label>
                                            <input type="url" name="github_url" class="form-control" placeholder="https://github.com/jina/repo">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">{{ get_phrase('Teknolojia / Tags (Tenganisha kwa mkato)') }}</label>
                                            <input type="text" name="tags" class="form-control" placeholder="k.m. PHP, Laravel, MySQL, Bootstrap">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">{{ get_phrase('Picha ya Mradi (Screenshot / Thumbnail)') }}</label>
                                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small fw-semibold text-muted">{{ get_phrase('Maelezo ya Mradi na Kazi Uliyofanya') }}</label>
                                            <textarea name="description" class="form-control" rows="3" placeholder="{{ get_phrase('Eleza kifupi mradi huu unatatua tatizo gani na ulijenga nini...') }}"></textarea>
                                        </div>

                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-secondary rounded-pill btn-sm px-3" data-bs-toggle="collapse" data-bs-target="#addNewProjectForm">{{ get_phrase('Ghairi') }}</button>
                                            <button type="submit" class="btn btn-primary rounded-pill btn-sm px-4 fw-semibold">{{ get_phrase('Hifadhi Mradi') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Projects List -->
                        <div class="row g-3">
                            @forelse ($projects as $project)
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 bg-white shadow-2xs h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            @if (!empty($project->thumbnail))
                                                <img src="{{ asset($project->thumbnail) }}" alt="{{ $project->title }}" class="rounded-2 w-100 mb-2" style="height: 140px; object-fit: cover;">
                                            @endif
                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 15px;">{{ $project->title }}</h6>
                                            <p class="text-muted small mb-2" style="font-size: 12px; line-height: 1.5;">
                                                {{ Str::limit($project->description, 100) }}
                                            </p>
                                            @if (!empty($project->tags))
                                                <div class="mb-2">
                                                    @foreach (explode(',', $project->tags) as $t)
                                                        <span class="badge bg-light text-muted border me-1" style="font-size: 10px;">{{ trim($t) }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">
                                            <div class="d-flex align-items-center gap-2">
                                                @if (!empty($project->project_url))
                                                    <a href="{{ $project->project_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-0" style="font-size: 11px;">
                                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Demo
                                                    </a>
                                                @endif
                                                @if (!empty($project->github_url))
                                                    <a href="{{ $project->github_url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-2 py-0" style="font-size: 11px;">
                                                        <i class="fa-brands fa-github"></i> Code
                                                    </a>
                                                @endif
                                            </div>
                                            <a href="{{ route('my.projects.delete', $project->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0" style="font-size: 11px;" onclick="return confirm('Una uhakika unataka kufuta mradi huu?')">
                                                <i class="fa-regular fa-trash-can"></i> Futa
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light border rounded-3 p-4 text-center my-3">
                                        <div class="fs-2 text-muted mb-2"><i class="fa-solid fa-laptop-code"></i></div>
                                        <h6 class="fw-bold mb-1">{{ get_phrase('Hujaweka mradi wowote bado') }}</h6>
                                        <p class="text-muted small mb-3">{{ get_phrase('Bofya kitufe cha "Ongeza Mradi" hapo juu ili kuweka mradi wako wa kwanza!') }}</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
