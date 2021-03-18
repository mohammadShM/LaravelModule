<div class="box-filter">
    <div class="b-head">
        <h2>جدید ترین دوره ها</h2>
        <a href="/">مشاهده همه</a>
    </div>
    <div class="posts">
        @foreach($latestCourses as $courseItem)
            @include('Front::layout.SingleCourseBox')
        @endforeach
    </div>
</div>
