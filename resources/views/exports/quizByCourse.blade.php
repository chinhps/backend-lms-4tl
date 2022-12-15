<table>
    <tr>
        <th colspan="5">BẢNG ĐIỂM WE16302</th>
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
            <td>{{ $student->user->user_code }}</td>
            <td>{{ $student->user->name }}</td>
            <td>123</td>
        </tr>
    @endforeach
</table>
