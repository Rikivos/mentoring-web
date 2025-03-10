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

        h1,
        h2 {
            font-size: medium;
            margin: 5px;
            text-align: center;
        }

        .header-table {
            width: 100%;
            text-align: center;
            margin-bottom: 5px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .header-table img {
            width: 80px;
            height: 80px;
        }

        hr {
            border: 2px solid black;
            margin: 5px 0;
        }

        .presensi-table,
        .presensi-table th,
        .presensi-table td {
            border: 1px solid black;
            text-align: center;
            padding: 5px;
        }

        .presensi-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td style="width: 20%; text-align: left;">
                <img src="images/LPPI.png" alt="Logo LPPI">
            </td>
            <td style="width: 60%;">
                <h1>LEMBAGA PENGKAJIAN DAN PENGAMALAN ISLAM</h1>
                <h2>UNIVERSITAS MUHAMMADIYAH PURWOKERTO</h2>
            </td>
            <td style="width: 20%; text-align: right;">
                <img src="images/UMPLogo.png" alt="Logo UMP">
            </td>
        </tr>
    </table>
    <hr>

    <h2>Rekap Presensi</h2>

    @if ($course)
    <p><strong>Kelas:</strong> {{ $course->course_title }}</p>
    <p><strong>Mentor:</strong> {{ $course->mentor_name }}</p>
    @endif

    <table class="presensi-table">
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