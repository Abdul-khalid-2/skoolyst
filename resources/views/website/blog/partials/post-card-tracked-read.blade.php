{{-- Optional aggregate read time; expects $post (BlogPost) --}}
@if((float) ($post->total_tracked_read_minutes ?? 0) > 0.0001)
<span title="Total time all visitors have spent reading this article" class="ms-1">· <i class="fas fa-hourglass-half me-1" aria-hidden="true"></i>{{ $post->formatted_tracked_read_time }}</span>
@endif
