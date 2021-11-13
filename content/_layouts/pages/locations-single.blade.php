@extends('_layouts.main')

@section('body')
    <div class="p-8">
        <h1 class="text-3xl font-bold">{!! $page !!}</h1>

        Organizacje w mieście:

        @foreach ($page->getOrganizations() as $organization)
            <li>
                <a href="{{ $organization->getUrl() }}">
                    {{ $organization->name }}
                </a>
            </li>
        @endforeach
    </div>
@endsection
