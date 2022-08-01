@extends('layout')

@section('content')


<div class="container-lg mt-5">
<div class="container-lg mt-5">
<div class="container">
    @include('flash::message')

    <p><h1 class="mt-5 mb-3">Сайт: {{ $url->name }}</h1></p>
</div>
</div>
</div>


@endsection
