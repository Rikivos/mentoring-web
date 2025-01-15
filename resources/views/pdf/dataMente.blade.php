<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kelas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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