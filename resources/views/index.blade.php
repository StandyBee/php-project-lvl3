
@extends('layout')

@section('content')

<main class="flex-grow-1">
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Последняя проверка</th>
                    <th>Код ответа</th>
                </tr>

                @foreach($urls as $url)

                <tr>
                    <td>{{ $url->id }}</td>
                    <td><a href="{{ route('urls.show', $url->id) }}">{{ $url->name }}</a></td>
                    <td>{{ $url->updated_at }}</td>
                    <td>{{ $status->get($url->id)->status_code ?? '' }}</td>
                </tr>

                @endforeach

            </table>
            <div class="pagination justify-content-end">

                {{ $urls->links() }}

            </div>
        </div>
    </div>
</main>

@endsection
