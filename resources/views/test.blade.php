@foreach ($data as $branch)
    <p>
        @for ($i = 0; $i < $branch['level']; $i++)
            ----
        @endfor
        {{$branch['name']}}
    </p>
@endforeach