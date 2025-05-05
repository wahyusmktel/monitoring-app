<ul class="breadcrumbs">
    <li class="nav-home">
        <a href="{{ url('/') }}">
            <i class="flaticon-home"></i>
        </a>
    </li>

    @foreach ($items as $index => $item)
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a>
        </li>
    @endforeach
</ul>
