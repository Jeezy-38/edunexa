@php
    $words = str_word_count(strip_tags($blog->description ?? ''));
    $readMinutes = max(1, ceil($words / 160));
@endphp
<div class="Ecard card b-card h-100 shadow-2xs border rounded-3 overflow-hidden transition-all">
    <div class="card-head position-relative">
        <img src="{{ get_image($blog->thumbnail) }}" alt="blog-thumbnail" style="height: 200px; width: 100%; object-fit: cover;">
        <span class="position-absolute top-0 start-0 m-2 badge bg-primary text-white rounded-pill px-3 py-1 shadow-sm" style="font-size: 11px;">
            {{ get_blog_category_name($blog->category_id) }}
        </span>
    </div>
    <div class="card-body d-flex flex-column p-3">
        <div class="d-flex align-items-center gap-2 mb-2 text-muted small" style="font-size: 12px;">
            <span><i class="fa-regular fa-calendar me-1"></i>{{ date('d M, Y', strtotime($blog->created_at)) }}</span>
            <span>&bull;</span>
            <span><i class="fa-regular fa-clock me-1 text-primary"></i>{{ $readMinutes }} {{ get_phrase('dakika za kusoma') }}</span>
        </div>

        <h4 class="ellipsis-2 fw-bold mb-2 text-dark" style="font-size: 16px; line-height: 1.4;">
            {{ ucfirst($blog->title) }}
        </h4>
        
        <div class="description ellipsis-line-2 text-muted mb-3 flex-grow-1" style="font-size: 13px; line-height: 1.6;">
            {{ ellipsis(strip_tags($blog->description), 140) }}
        </div>

        <div class="b_bottom d-flex align-items-center justify-content-between pt-2 border-top mt-auto">
            <a href="{{ route('blog.details', $blog->slug) }}" class="read-text mt-0 fw-semibold text-primary stretched-link d-flex align-items-center gap-1" style="font-size: 13px;">
                <span>{{ get_phrase('Soma Zaidi') }}</span>
                <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
            <span class="text-muted" style="font-size: 13px;"><i class="fa-regular fa-bookmark"></i></span>
        </div>
    </div>
</div>
