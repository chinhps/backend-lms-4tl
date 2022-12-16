<table>
    <tr>
        <th colspan="5">BẢNG ĐIỂM {{$class}}</th>
    </tr>
    <tr>
        <th>STT</th>
        <th>Mã sinh viên</th>
        <th>Tên sinh viên</th>
        @foreach ($list_quiz as $title)
            <th>{{ $title->name }}</th>
        @endforeach
    </tr>
    @foreach ($students as $key => $student)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ $student['user']->user_code }}</td>
            <td>{{ $student['user']->name }}</td>
            @foreach ($student['points'] as $point)
                <td>{{ count($point) == 0 ? "NULL" : $point[0]->point }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
