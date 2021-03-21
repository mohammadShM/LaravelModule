<div class="episodes-list mb-10">
    <div class="episodes-list--title">
        فهرست جلسات
        <span>  دریافت همه لینک های دانلود  </span>
    </div>
    <div class="episodes-list-section">
        @foreach($lessons as $lesson)
            <div class="episodes-list-item  @cannot('download' , $lesson) lock @endcannot">
                <div class="section-right">
                    <span class="episodes-list-number">{{ $lesson->number }}</span>
                    <div class="episodes-list-title">
                        <a href="{{ $lesson->path() }}">{{ $lesson->title }}</a>
                    </div>
                </div>
                <div class="section-left">
                    <div class="episodes-list-details">
                        <div class="episodes-list-details">
                            <span class="detail-type">@Lang($lesson->type)</span>
                            <span class="detail-time">{{ $lesson->time }} دقیقه </span>
                            <a class="detail-download" @can('download' , $lesson)
                            href="{{ $lesson->downloadLink() }}" @endcan>
                                <i class="icon-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
