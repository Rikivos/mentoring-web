<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recap Presensi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            text-align: center;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Recap Presensi</h2>

    @if ($course)
    <p><strong>Kelas:</strong> {{ $course->course_title }}</p>
    <p><strong>Mentor:</strong> {{ $course->mentor_name }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>NIM</th>
                <th>Nama</th>
                @for ($i = 1; $i <= 10; $i++)
                    <th>P{{ $i }}</th>
                    @endfor
                    <th>Jml</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recap as $index => $data)
            @php
            $statuses = explode(',', $data->statuses);
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->nim }}</td>
                <td>{{ $data->name }}</td>
                @for ($i = 0; $i < 10; $i++)
                    <td>
                    @php
                    $status = $statuses[$i] ?? '-';
                    if ($status == 'hadir') {
                    $status = 'H';
                    } elseif ($status == 'tidak hadir') {
                    $status = 'T';
                    } elseif ($status == 'izin') {
                    $status = 'I';
                    }
                    @endphp
                    {{ $status }}
                    </td>
                    @endfor

                    <td>{{ $data->total_hadir }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>