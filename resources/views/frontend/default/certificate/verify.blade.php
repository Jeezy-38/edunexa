@extends('layouts.default')
@push('title', get_phrase('Uthibitisho wa Cheti') . ' | ' . ($course->title ?? get_phrase('Edunexa LMS')))
@push('meta')
    <meta name="description" content="{{ get_phrase('Uhakiki rasmi wa cheti cha kuhitimu mafunzo kutoka Edunexa LMS.') }}">
@endpush

@section('content')
<section class="py-5 bg-light" style="min-height: 80vh;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                @if($isValid)
                    <!-- Verified Certificate Card -->
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                        <!-- Top status bar -->
                        <div class="bg-success text-white py-3 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle-check fs-4"></i>
                                <span class="fw-bold tracking-wide" style="letter-spacing: 0.5px;">{{ get_phrase('CHETI KILICHOTHIBITISHWA NA HALALI') }}</span>
                            </div>
                            <span class="badge bg-white text-success fw-bold px-3 py-2 rounded-pill" style="font-size: 11px;">
                                <i class="fa-solid fa-shield-halved me-1"></i> VERIFIED
                            </span>
                        </div>

                        <div class="card-body p-4 p-md-5 text-center">
                            <!-- Security Hologram icon -->
                            <div class="mb-4 d-inline-flex p-3 rounded-circle bg-success-subtle text-success">
                                <i class="fa-solid fa-award" style="font-size: 54px;"></i>
                            </div>

                            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 12px; letter-spacing: 1.5px;">
                                {{ get_phrase('Cheti cha Umahiri na Kuhitimu Mafunzo') }}
                            </p>
                            
                            <h2 class="fw-bold text-dark mb-3 mt-2" style="font-family: inherit;">
                                {{ $user->name ?? get_phrase('Mhitimu wa Mafunzo') }}
                            </h2>

                            <p class="text-secondary mb-4 mx-auto" style="max-width: 500px; font-size: 15px;">
                                {{ get_phrase('Amekamilisha kwa ufanisi mafunzo yote na majaribio ya kozi ya kitaalamu ya:') }}
                            </p>

                            <div class="p-3 rounded-3 bg-light border mb-4 text-start">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <img src="{{ get_image($course->thumbnail ?? '') }}" alt="course thumbnail" class="rounded-2" style="width: 80px; height: 55px; object-fit: cover;">
                                    </div>
                                    <div class="col">
                                        <h6 class="fw-bold mb-1 text-dark">{{ $course->title ?? get_phrase('Kozi ya Mafunzo') }}</h6>
                                        <small class="text-muted d-block">
                                            <i class="fa-solid fa-chalkboard-user me-1 text-primary"></i>
                                            {{ get_phrase('Mkufunzi') }}: <strong>{{ $instructor->name ?? get_phrase('Edunexa Instructor') }}</strong>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Certificate Details Grid -->
                            <div class="row g-3 text-start mb-4">
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 11px;">{{ get_phrase('Namba ya Cheti (ID)') }}</small>
                                        <span class="fw-bold text-dark font-monospace" style="font-size: 14px;">{{ $identifier }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 11px;">{{ get_phrase('Tarehe Iliyotolewa') }}</small>
                                        <span class="fw-bold text-dark" style="font-size: 14px;">
                                            {{ $certificate->created_at ? $certificate->created_at->format('d M, Y') : date('d M, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 11px;">{{ get_phrase('Taasisi Iliyotoa') }}</small>
                                        <span class="fw-bold text-dark" style="font-size: 14px;">{{ get_settings('system_title') ?: 'Edunexa LMS' }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 11px;">{{ get_phrase('Hali ya Cheti') }}</small>
                                        <span class="badge bg-success py-1 px-2" style="font-size: 12px;"><i class="fa fa-check me-1"></i> {{ get_phrase('Hai na Kina Uhalali') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex flex-wrap gap-2 justify-content-center pt-2">
                                <a href="{{ route('certificate', ['identifier' => $identifier]) }}" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                    {{ get_phrase('Pakua / Tazama Cheti') }}
                                </a>

                                @php
                                    $linkedInUrl = "https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name=" . urlencode($course->title ?? 'Certificate') . "&organizationName=" . urlencode(get_settings('system_title') ?: 'Edunexa') . "&issueYear=" . ($certificate->created_at ? $certificate->created_at->format('Y') : date('Y')) . "&issueMonth=" . ($certificate->created_at ? $certificate->created_at->format('n') : date('n')) . "&certUrl=" . urlencode(route('certificate.verify', ['identifier' => $identifier])) . "&certId=" . urlencode($identifier);
                                @endphp
                                <a href="{{ $linkedInUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                                    <i class="fa-brands fa-linkedin"></i>
                                    {{ get_phrase('Weka LinkedIn') }}
                                </a>

                                @if($course)
                                    <a href="{{ route('course.details', $course->slug) }}" class="btn btn-light border px-3 py-2 fw-semibold" style="border-radius: 8px;">
                                        {{ get_phrase('Kuhusu Kozi Hii') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer bg-light py-3 px-4 text-center border-top">
                            <small class="text-muted">
                                <i class="fa-solid fa-lock me-1 text-success"></i>
                                {{ get_phrase('Uthibitisho huu umezalishwa moja kwa moja kupitia mfumo rasmi wa uthibitishaji wa Edunexa LMS.') }}
                            </small>
                        </div>
                    </div>
                @else
                    <!-- Invalid Certificate Notice -->
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white text-center p-4 p-md-5">
                        <div class="mb-4 d-inline-flex p-3 rounded-circle bg-danger-subtle text-danger mx-auto">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 50px;"></i>
                        </div>
                        <h3 class="fw-bold text-danger mb-2">{{ get_phrase('Cheti Hakikupatikana!') }}</h3>
                        <p class="text-muted mb-4 mx-auto" style="max-width: 450px;">
                            {{ get_phrase('Namba ya utambulisho wa cheti uliyoweka (ID: ') }}<strong>{{ $identifier }}</strong>{{ get_phrase(') haipo au cheti hiki hakijasajiliwa kwenye kumbukumbu zetu.') }}
                        </p>
                        <div>
                            <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 fw-semibold" style="border-radius: 8px;">
                                <i class="fa fa-home me-2"></i>{{ get_phrase('Rudi Nyumbani') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
