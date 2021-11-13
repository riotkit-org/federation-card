@extends('_layouts.main')

@section('body')
<div class="p-8">
    <h1 class="text-3xl font-bold">Organizacje</h1>
</div>

<ul>
    @foreach ($organizations as $organization)
        <li>{{ $organization->name }} z miasta {{ $organization->getLocation()->name }}, {{ $organization->getDescription() }}.
            @foreach ($organization->getBadges() as $badge)
                [Badge: {{ $badge->title }}]
            @endforeach
        </li>
    @endforeach
</ul>
@endsection
