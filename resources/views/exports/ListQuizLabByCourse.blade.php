<table>
    <tbody>
        <tr>
            <td class="s0"></td>
            <td colspan="2" rowspan="5" style="width: 210px; height: 30px">
                <img src="{{ public_path() . '/logo.png' }}"
                    style="width: 200px; object-fit: scale-down; object-position: center center" />
            </td>
            <td class="s0"></td>
            <td class="s0"></td>
            <td class="s0"></td>
            <td class="s0"></td>
            <td class="s0"></td>
            <td class="s0"></td>
            <td class="s0"></td>
            <td class="s0"></td>
            <td class="s0"></td>
        </tr>
        <tr style="height: 20px">
            <td class="s0"></td>
            <td style="font-weight: bold; font-size: 30px" colspan="7" rowspan="3">
                BẢNG ĐIỂM {{ $class }}
            </td>
            <td class="s0"></td>
            <td class="s0"></td>
        </tr>
        <tr style="height: 20px">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="height: 20px">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="height: 20px">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="height: 20px">
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
            <td class="s3"></td>
        </tr>
        <tr>
            <td style="text-align: center; background-color: #dcf0ff;">STT</td>
            <td style="text-align: center; width: 100px; background-color: #dcf0ff;">Mã sinh viên</td>
            <td style="text-align: center; width: 200px; background-color: #dcf0ff;">Tên sinh viên</td>
            @foreach ($list as $title)
                <td style="text-align: center; width: 100px; background-color: #dcf0ff;">{{ $title->name }}</td>
            @endforeach
        </tr>
        @if (isset($students['quizs']))
            @foreach ($students['quizs'] as $key => $student)
                <tr style="height: 20px">
                    <td style="text-align: center">{{ $key + 1 }}</td>
                    <td style="text-align: center">{{ $student['user']->user_code }}</td>
                    <td style="text-align: center">{{ $student['user']->name }}</td>
                    @foreach ($student['points'] as $point)
                        <td style="text-align: center">
                            {{ count($point) == 0 ? 'NULL' : $point[0]->point }}
                        </td>
                        @endforeach @foreach ($students['labs'][$key]['points'] as $point)
                            <td style="text-align: center">
                                {{ count($point) == 0 ? 'NULL' : $point[0]->point }}
                            </td>
                        @endforeach
                </tr>
            @endforeach
        @else
            @foreach ($students as $key => $student)
                <tr style="height: 20px">
                    <td style="text-align: center">{{ $key + 1 }}</td>
                    <td style="text-align: center">{{ $student['user']->user_code }}</td>
                    <td style="text-align: center">{{ $student['user']->name }}</td>
                    @foreach ($student['points'] as $point)
                        <td style="text-align: center">
                            {{ count($point) == 0 ? 'NULL' : $point[0]->point }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
