<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder;

class UsersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private int $rowNumber = 0;

    /**
     * Mengambil query data untuk diekspor.
     */
    public function query(): Builder
    {
        // Mengambil data user dengan relasi roles
        return User::query()->with('roles');
    }

    /**
     * Header untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'NO',             // Kolom Nomor
            'ID User',
            'Username',
            'Nama User',
            'Email',
            'Roles',
            'Status Login',
            'Status Akun',
        ];
    }

    /**
     * Memetakan data untuk setiap baris.
     */
    public function map($user): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $user->id,
            $user->username,
            $user->name,
            $user->email,
            $user->roles->pluck('name')->implode(', '),
            $user->status_login ? 'Sedang' : 'Non-aktif',
            $user->email_verified_at ? 'Aktif' : 'Non-aktif',
        ];
    }

    /**
     * Mengatur style untuk worksheet.
     */
    public function styles(Worksheet $sheet): void
    {
        // Style header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Teks putih
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0070C0'], // Latar belakang biru
            ],
        ]);

        // Style border untuk seluruh data
        $sheet->getStyle('A1:H' . $sheet->getHighestRow())
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'], // Border hitam
                    ],
                ],
            ]);

        // Style untuk nomor (kolom pertama)
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
    }

    
}
