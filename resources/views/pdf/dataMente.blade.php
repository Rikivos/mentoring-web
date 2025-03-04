<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kelas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
            text-align: left;
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

    <h1>Laporan Kelas: {{ $course_name }}</h1>
    <p><strong>Mentor:</strong> {{ $mentor_name }}</p>
    <p><strong>Jumlah Peserta:</strong> {{ count($participants) }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participants as $index => $participant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $participant['name'] }}</td>
                <td>{{ $participant['nim'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>