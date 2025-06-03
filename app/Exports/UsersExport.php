<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function __construct(public Collection $records)
    {
        //
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->records;
    }

    /**
     * @param User $user
     */
    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->student_classes[0]->name ?? 'Belum terikat kelas manapun',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Username',
            'Kelas',
        ];
    }
}
