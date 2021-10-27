@extends('_layouts.main')

@section('body')
    <div class="p-8">
        <h1 class="text-3xl font-bold">{{ $page }}</h1>
    </div>

    <div>
        {{ $organization->getLocation() }}
    </div>
@endsection
