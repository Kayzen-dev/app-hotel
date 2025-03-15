<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Masuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        h3 {
            text-align: center;
            margin: 20px 0;
        }
        table {
            width: 95%; /* Memperkecil ukuran tabel */
            margin: 0 auto; /* Menempatkan tabel di tengah */
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 6px 8px; /* Memperkecil padding */
            text-align: left;
            font-size: 10px; /* Menyesuaikan font untuk tabel */
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }
        td {
            vertical-align: top;
        }
        .center {
            text-align: center;
        }
        .date-format {
            text-transform: capitalize;
        }
        /* Tambahkan margin untuk batas tabel */
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h3>Daftar Users Agenda Surat</h3>
        <table>
            <thead>
                <tr>
                    <th>NO</th>             
                    <th>ID User</th>
                    <th>Username</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Status Akun</th>
                </tr>
            </thead>
            <tbody>

                
                @forelse($users as $index => $user)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td>
                            {{  $user->email_verified_at ? 'Aktif' : 'Non-aktif' }}
                        </td>
                    </tr>
                    @empty
                    <td colspan="8">Tidak Ada user</td>

                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
