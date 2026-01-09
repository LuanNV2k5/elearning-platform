@extends('layouts.student')

@section('content')
<div class="container">
    <h4 class="mb-3">{{ $lesson->title }}</h4>

    @php
        // Kiểm tra bài đã hoàn thành chưa (dựa bảng lesson_user)
        $isCompleted = \Illuminate\Support\Facades\DB::table('lesson_user')
            ->where('user_id', auth()->id())
            ->where('lesson_id', $lesson->id)
            ->where('completed', 1)
            ->exists();
    @endphp

    {{-- ===== FORM HOÀN THÀNH (dùng lại cho cả click và auto-complete) ===== --}}
    <form id="completeForm"
          method="POST"
          action="{{ route('student.lessons.complete', [$course, $lesson]) }}">
        @csrf
        <button
            id="completeBtn"
            type="submit"
            class="btn btn-success mb-3"
            @if($isCompleted) disabled @endif
        >
            @if($isCompleted)
                ✅ Đã hoàn thành
            @else
                ✅ Hoàn thành bài học
            @endif
        </button>
    </form>

    {{-- ===== VIDEO ===== --}}
    @if($lesson->youtube_id)
        <div class="ratio ratio-16x9 mb-4">
            {{-- dùng div để YouTube API attach --}}
            <div id="ytPlayer"></div>
        </div>
    @endif

    {{-- ===== PDF ===== --}}
    @if($lesson->pdf_path)
        <div class="mb-3">
            <a href="{{ asset('storage/'.$lesson->pdf_path) }}"
               target="_blank"
               class="btn btn-outline-primary">
                📄 Mở tài liệu PDF
            </a>
        </div>

        <iframe
            src="{{ asset('storage/'.$lesson->pdf_path) }}"
            width="100%"
            height="600"
            style="border:1px solid #ccc">
        </iframe>
    @endif

    {{-- ===== MÔ TẢ ===== --}}
    @if($lesson->description)
        <p class="mt-3">{{ $lesson->description }}</p>
    @endif

    {{-- ===== NEXT LESSON (nếu controller truyền $nextLesson) ===== --}}
    @if(!empty($nextLesson) && !empty($nextLesson->id))
        <a class="btn btn-primary mt-3"
           href="{{ route('student.lessons.show', [$course->id, $nextLesson->id]) }}">
            ⏭ Học bài tiếp theo
        </a>
    @endif

    <a href="{{ route('student.courses.show', $course) }}"
       class="btn btn-secondary mt-3">
        ← Quay lại khóa học
    </a>
</div>

{{-- ===== AUTO COMPLETE khi video kết thúc ===== --}}
@if($lesson->youtube_id)
<script>
    // Nếu đã completed rồi thì không auto-complete nữa
    const alreadyCompleted = @json($isCompleted);

    // Chặn submit nhiều lần
    let isSubmitting = false;
    function submitComplete() {
        if (alreadyCompleted) return;
        if (isSubmitting) return;

        isSubmitting = true;

        // disable nút để user thấy đang xử lý
        const btn = document.getElementById('completeBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerText = "⏳ Đang lưu...";
        }

        document.getElementById('completeForm').submit();
    }

    // Load YouTube Iframe API
    (function loadYT(){
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        document.head.appendChild(tag);
    })();

    let player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('ytPlayer', {
            videoId: @json($lesson->youtube_id),
            playerVars: {
                autoplay: 0,
                rel: 0,
                modestbranding: 1
            },
            events: {
                onStateChange: function(event) {
                    // 0 = ended
                    if (event.data === YT.PlayerState.ENDED) {
                        submitComplete();
                    }
                }
            }
        });
    }
    window.onYouTubeIframeAPIReady = onYouTubeIframeAPIReady;
</script>
@endif
@endsection
