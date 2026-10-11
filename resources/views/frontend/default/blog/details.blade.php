@extends('layouts.default')
@push('title', get_phrase('Blog Details'))
@push('meta')@endpush
@push('css')
    <style>
        .playing-breadcum {
            height: 350px;
        }

        .breadcum-area {
            z-index: -1;
        }
    </style>
@endpush
@section('content')
    @php
        $total_comments = count_comments_by_blog_id($blog_details->id);
        $total_likes = count_likes_by_blog_id($blog_details->id);
    @endphp
    <!------------------- Breadcum Area Start  ------>
    <section class="breadcum-area playing-breadcum details-breadcum">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="eNtry-breadcum">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('blogs') }}">{{ get_phrase('Blogs') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Blog Details') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!------------------- Breadcum Area End  --------->

    <!------------------- Blog Details Area Start  --------->
    <section class="blog-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ps-box blog-box">

                        <div class="details-intro">
                            <h4 class="g-title text-center f-40 mt-4">{{ $blog_details->title }}</h4>
                            <ul class="course-motion-top flex-wrap gap-4 justify-content-center">
                                <li>
                                    <div class="figar d-flex align-items-center mb-0 me-auto">
                                        <img src="{{ get_image($blog_details->author_photo) }}" alt="author-image">
                                        <p class="description">{{ $blog_details->author_name }}</p>
                                    </div>
                                </li>
                                @if ($blog_details->keywords)
                                    <li>
                                        <img class="pro-20" src="{{ asset('assets/frontend/default/image/elearn.png') }}" alt="blog-tag">
                                        @php
                                            $tags = json_decode($blog_details->keywords, true);
                                            if (is_array($tags) && count($tags) > 0) {
                                                $tags = array_column($tags, 'value');
                                            }
                                        @endphp
                                        {{ $tags ? implode(', ', $tags) : '' }}
                                    </li>
                                @endif
                                @php
                                    $words = str_word_count(strip_tags($blog_details->description ?? ''));
                                    $readMinutes = max(1, ceil($words / 160));
                                    $blogShareUrl = urlencode(route('blog.details', $blog_details->slug));
                                    $blogShareMsg = urlencode("Soma mwongozo huu kutoka Edunexa: " . $blog_details->title . " \n");
                                @endphp
                                <li>
                                    <i class="fa-regular fa-clock text-primary me-1"></i>
                                    <span>{{ $readMinutes }} {{ get_phrase('dakika za kusoma') }}</span>
                                </li>
                                <li>
                                    <img class="pro-20" src="{{ asset('assets/frontend/default/image/elearn2.png') }}" alt="blog-comment">
                                    {{ $total_comments }}
                                </li>
                                <li>
                                    <img class="pro-20" src="{{ asset('assets/frontend/default/image/elearn3.png') }}" alt="created-date">
                                    {{ date('d M, Y', strtotime($blog_details->created_at)) }}
                                </li>
                            </ul>
                        </div>


                        <div class="blog-f-image">
                            <img src="{{ get_image($blog_details->banner) }}" alt="blog-thumbnail" class="rounded-3 shadow-2xs mb-4">
                            <div class="description description-style" style="font-size: 16px; line-height: 1.8; color: #334155;">
                                {!! removeScripts($blog_details->description) !!}
                            </div>

                            <!-- Social Sharing & Engagement Bar -->
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 my-4 bg-light rounded-3 border">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted fw-semibold small">{{ get_phrase('Shiriki Makala Hii:') }}</span>
                                    <a href="https://api.whatsapp.com/send?text={{ $blogShareMsg }}{{ $blogShareUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-2xs" style="font-size: 12px;">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $blogShareUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 shadow-2xs" style="font-size: 12px;">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?url={{ $blogShareUrl }}&title={{ urlencode($blog_details->title) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info rounded-pill px-2 py-1 shadow-2xs" style="font-size: 12px;">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @auth
                                        @php
                                            $is_liked = App\Models\BlogLike::where('blog_id', $blog_details->id)
                                                ->where('user_id', auth()->user()->id)
                                                ->first();
                                        @endphp
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="like-svg cursor-pointer @if ($is_liked) active @endif" style="font-size: 18px;">
                                                <i class="fa-solid fa-heart @if ($is_liked) text-danger @else text-muted @endif"></i>
                                            </div>
                                            <span id="total-blog-likes" class="fw-semibold small text-muted">{{ $total_likes }}</span>
                                        </div>
                                    @endauth
                                </div>
                            </div>

                            <ul class="tags">
                                @php
                                    $tags = $blog_details->keywords ? json_decode($blog_details->keywords, true) : [];
                                    if (is_array($tags) && count($tags) > 0) {
                                        $tags = array_column($tags, 'value');
                                    } else {
                                        $tags = [];
                                    }
                                @endphp
                                @foreach ($tags as $tag)
                                    <li><a href="{{ route('blogs', ['search' => $tag]) }}">{{ ucfirst($tag) }}</a></li>
                                @endforeach
                            </ul>

                            <!-- Edunexa Course CTA Banner -->
                            <div class="card border-0 rounded-4 shadow-sm my-4 p-4 text-white text-center text-md-start" style="background: linear-gradient(135deg, #1e3a8a, #4338ca, #6366f1);">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-8">
                                        <h5 class="text-white fw-bold mb-1">Unataka Kujenga Ujuzi Zaidi na Cheti Rasmi? 🚀</h5>
                                        <p class="text-white-50 mb-0" style="font-size: 13px;">Gundua kozi za vitendo kwenye Edunexa ukiwa na msaada wa mwalimu wa AI (AI Tutor) na vyeti vyenye QR Code vinavyotambulika.</p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <a href="{{ route('courses') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm text-nowrap" style="font-size: 13px;">
                                            Gundua Kozi Zote <i class="fa-solid fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        <span id="total-blog-likes">{{ $total_likes }}</span>
                                    </span>
                                </div>
                            @endauth
                        </div>

                        <div class="comment-wrap">
                            @include('frontend.default.blog.author_details')

                            <div class="comment-head">
                                <h4 class="g-title"> {{ $total_comments }} </h4>
                            </div>

                            @isset(auth()->user()->id)
                                <h4 class="g-title g_font mt-5 mb-0">{{ get_phrase('Post A Comment') }}</h4>
                                <form action="{{ route('blog.comment.store') }}" class="comment-form" method="post">@csrf
                                    <input type="hidden" name="blog_id" value="{{ $blog_details->id }}">
                                    <textarea name="comment" id="comment" class="form-control" placeholder="{{ get_phrase('Write your comment ...') }}"></textarea>
                                    <button type="submit" class="eBtn gradient">{{ get_phrase('Post Comment') }}</button>
                                </form>
                            @endisset

                            @foreach ($blog_comments as $comment)
                                @include('frontend.default.blog.comment')
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!------------------- Blog Details Area End  --------->
@endsection
@push('js')
    <script>
        "use strict";
        $(document).ready(function() {
            $('.replay').on('click', function(e) {
                e.preventDefault();
                let comment_id = $(this).attr('id');

                if ($('#replay-' + comment_id).hasClass('d-none')) {
                    $('#replay-' + comment_id).removeClass('d-none');
                } else {
                    $('#replay-' + comment_id).addClass('d-none');
                }
                $('.replay-form:not(#replay-' + comment_id + ')').addClass('d-none');
            });

            $('.like-svg').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "get",
                    url: "{{ route('blog.like') }}",
                    data: {
                        blog_id: "{{ $blog_details->id }}",
                    },
                    success: function(response) {
                        let likes = +($('#total-blog-likes').text());
                        if (response.like) {
                            $('.like-svg').addClass('active');
                        } else {
                            $('.like-svg').removeClass('active');
                        }
                    }
                });
            });
        });
    </script>
@endpush
