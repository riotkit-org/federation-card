@extends('_layouts.main')

@section('body')
<div class="p-8">
    <h1 class="text-3xl font-bold">Organizacje</h1>
</div>

<ul>
    @foreach ($organizations as $organization)
        <li>{{ $organization->name }}</li>
    @endforeach
</ul>
@endsection
