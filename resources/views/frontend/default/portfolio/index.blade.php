@extends('layouts.default')
@push('title', ($user->name ?? 'Mwanafunzi') . ' - Wasifu wa Kidijitali (Portfolio) | Edunexa')
@push('meta')
    <meta name="description" content="Tazama wasifu wa kidijitali, miradi, na vyeti vilivyoidhinishwa vya {{ $user->name }} kutoka Edunexa.">
@endpush
@push('css')
    <style>
        .portfolio-header-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #312e81 100%);
            min-height: 220px;
            position: relative;
        }
        .portfolio-avatar-box {
            margin-top: -75px;
        }
        .portfolio-avatar {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border: 5px solid #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .cert-card:hover, .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
            transition: all 0.25s ease;
        }
        @media print {
            header, footer, .no-print, .btn, .breadcrumb {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
            }
            .portfolio-header-gradient {
                background: #f8fafc !important;
                min-height: auto !important;
                padding: 10px 0 !important;
            }
            .card {
                border: 1px solid #cbd5e1 !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush
@section('content')
    <!-- Banner Header -->
    <div class="portfolio-header-gradient d-flex align-items-center">
        <div class="container text-white py-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold mb-2" style="font-size: 11px;">
                        <i class="fa-solid fa-award me-1"></i> EDUNEXA VERIFIED TALENT
                    </span>
                    <h2 class="text-white fw-bold mb-1">{{ $user->name }}</h2>
                    <p class="text-white-50 mb-0" style="font-size: 14px;">
                        {{ $user->address ? $user->address . ' | ' : '' }} Wasifu Rasmi wa Kidijitali & Vyeti vya Umahiri
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0 no-print">
                    <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" onclick="window.print()">
                        <i class="fa-solid fa-print text-primary"></i>
                        <span>{{ get_phrase('Pakua / Chapisha CV') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container pb-5">
        <div class="row">
            <!-- Left Profile Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4 bg-white portfolio-avatar-box">
                    <div class="position-relative d-inline-block mx-auto mb-3">
                        <img src="{{ get_image($user->photo) }}" alt="{{ $user->name }}" class="rounded-circle portfolio-avatar">
                        <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle p-2" title="Active Graduate"></span>
                    </div>

                    <h4 class="fw-bold mb-1 text-dark">{{ $user->name }}</h4>
                    <p class="text-muted small mb-3">
                        {{ $user->skills ? (is_array($skills) ? implode(' • ', array_slice($skills, 0, 3)) : '') : 'Mhitimu wa Edunexa' }}
                    </p>

                    <!-- Contact & Hire Action Buttons -->
                    <div class="d-grid gap-2 mb-4 no-print">
                        @if (!empty($user->phone))
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                                if (str_starts_with($cleanPhone, '0') && strlen($cleanPhone) === 10) {
                                    $cleanPhone = '255' . substr($cleanPhone, 1);
                                }
                                $msg = urlencode("Habari " . $user->name . "! Nimeona wasifu wako kwenye Edunexa Portfolio na ningependa kuwasiliana nawe kuhusu nafasi ya kazi/mradi.");
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $msg }}" target="_blank" class="btn btn-success rounded-pill fw-semibold py-2 d-flex align-items-center justify-content-center gap-2 shadow-2xs">
                                <i class="fa-brands fa-whatsapp fs-5"></i>
                                <span>{{ get_phrase('Wasiliana kwa WhatsApp') }}</span>
                            </a>
                        @endif

                        @if (!empty($user->email))
                            <a href="mailto:{{ $user->email }}?subject={{ urlencode('Fursa ya Kazi / Mradi - Kutoka Edunexa Portfolio') }}" class="btn btn-outline-primary rounded-pill fw-semibold py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-regular fa-envelope"></i>
                                <span>{{ get_phrase('Tuma Barua Pepe') }}</span>
                            </a>
                        @endif
                    </div>

                    <!-- Social Channels -->
                    <div class="d-flex align-items-center justify-content-center gap-3 pt-3 border-top no-print">
                        @if (!empty($user->linkedin))
                            <a href="{{ $user->linkedin }}" target="_blank" class="text-primary fs-5" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if (!empty($user->website))
                            <a href="{{ $user->website }}" target="_blank" class="text-dark fs-5" title="Website / GitHub"><i class="fa-solid fa-globe"></i></a>
                        @endif
                        @if (!empty($user->twitter))
                            <a href="{{ $user->twitter }}" target="_blank" class="text-info fs-5" title="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
                        @endif
                        @if (!empty($user->facebook))
                            <a href="{{ $user->facebook }}" target="_blank" class="text-primary fs-5" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
                        @endif
                    </div>
                </div>

                <!-- Skills Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 16px;">
                        <i class="fa-solid fa-code text-primary"></i>
                        {{ get_phrase('Ujuzi na Teknolojia (Skills)') }}
                    </h5>
                    @if (count($skills) > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($skills as $skill)
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium" style="font-size: 12px;">
                                    {{ ucfirst(trim($skill)) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-0">{{ get_phrase('Ujuzi bado haujaorodheshwa.') }}</p>
                    @endif
                </div>

                <!-- Quick Stats Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 16px;">
                        <i class="fa-solid fa-chart-simple text-primary"></i>
                        {{ get_phrase('Muhtasari wa Mafanikio') }}
                    </h5>
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3">
                                <div class="fs-4 fw-bold text-primary">{{ $completed_count }}</div>
                                <div class="text-muted small">{{ get_phrase('Vyeti vya QR') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3">
                                <div class="fs-4 fw-bold text-success">{{ count($projects) }}</div>
                                <div class="text-muted small">{{ get_phrase('Miradi ya Vitendo') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-lg-8 pt-4">
                <!-- Biography / About Me -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 17px;">
                        <i class="fa-regular fa-user text-primary"></i>
                        {{ get_phrase('Kuhusu Mhitimu (About Me)') }}
                    </h5>
                    <div class="text-muted" style="font-size: 14px; line-height: 1.8;">
                        @if (!empty($user->biography))
                            {!! removeScripts($user->biography) !!}
                        @elseif(!empty($user->about))
                            {!! removeScripts($user->about) !!}
                        @else
                            <p class="mb-0">Mwanafunzi aliyejituma anayeendelea kujenga ujuzi wa kidijitali na kitaalamu kupitia jukwaa la Edunexa.</p>
                        @endif
                    </div>
                </div>

                <!-- Verified Certifications -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 17px;">
                            <i class="fa-solid fa-certificate text-warning"></i>
                            {{ get_phrase('Vyeti Vilivyothibitishwa (Verified Certifications)') }}
                        </h5>
                        <span class="badge bg-warning text-dark rounded-pill px-3">{{ count($certificates) }} {{ get_phrase('Vyeti') }}</span>
                    </div>

                    @if (count($certificates) > 0)
                        <div class="row g-3">
                            @foreach ($certificates as $cert)
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 cert-card h-100 d-flex flex-column justify-content-between bg-white shadow-2xs">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 10px;">
                                                    <i class="fa-solid fa-check-circle me-1"></i> Verified
                                                </span>
                                                <small class="text-muted">{{ date('M Y', strtotime($cert->created_at)) }}</small>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 14px;">
                                                {{ $cert->course->title ?? 'Cheti cha Mafunzo' }}
                                            </h6>
                                            <small class="text-muted d-block mb-3">Namba: <code>{{ $cert->identifier }}</code></small>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 pt-2 border-top no-print">
                                            <a href="{{ route('certificate.verify', $cert->identifier) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-grow-1" style="font-size: 11px;">
                                                <i class="fa-solid fa-shield-halved me-1"></i> {{ get_phrase('Hakiki QR') }}
                                            </a>
                                            <a href="{{ route('certificate', $cert->identifier) }}" class="btn btn-sm btn-light rounded-pill px-3" style="font-size: 11px;" title="{{ get_phrase('Pakua Cheti') }}">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border rounded-3 p-3 text-center my-2 text-muted small">
                            <i class="fa-solid fa-graduation-cap me-1"></i> Mwanafunzi yupo katika hatua ya kukamilisha kozi zake ili kupokea cheti.
                        </div>
                    @endif
                </div>

                <!-- Projects Showcase -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style="font-size: 17px;">
                            <i class="fa-solid fa-laptop-code text-primary"></i>
                            {{ get_phrase('Miradi Aliyotengeneza (Projects Showcase)') }}
                        </h5>
                        <span class="badge bg-primary text-white rounded-pill px-3">{{ count($projects) }} {{ get_phrase('Miradi') }}</span>
                    </div>

                    @if (count($projects) > 0)
                        <div class="row g-3">
                            @foreach ($projects as $project)
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 project-card h-100 d-flex flex-column justify-content-between bg-white shadow-2xs">
                                        @if (!empty($project->thumbnail))
                                            <img src="{{ asset($project->thumbnail) }}" alt="{{ $project->title }}" class="rounded-2 mb-2 w-100" style="height: 140px; object-fit: cover;">
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 15px;">{{ $project->title }}</h6>
                                            <p class="text-muted small mb-2" style="font-size: 13px; line-height: 1.5;">
                                                {{ Str::limit($project->description, 110) }}
                                            </p>
                                            @if (!empty($project->tags))
                                                <div class="mb-3">
                                                    @foreach (explode(',', $project->tags) as $tag)
                                                        <span class="badge bg-light text-muted border me-1 mb-1" style="font-size: 10px;">{{ trim($tag) }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2 pt-2 border-top no-print">
                                            @if (!empty($project->project_url))
                                                <a href="{{ $project->project_url }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 flex-grow-1" style="font-size: 12px;">
                                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Live Demo
                                                </a>
                                            @endif
                                            @if (!empty($project->github_url))
                                                <a href="{{ $project->github_url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="font-size: 12px;" title="Source Code">
                                                    <i class="fa-brands fa-github me-1"></i> Code
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border rounded-3 p-3 text-center my-2 text-muted small">
                            <i class="fa-solid fa-folder-open me-1"></i> Bado hajaweka miradi ya umma.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
