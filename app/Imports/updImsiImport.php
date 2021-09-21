<?php


// namespace App\Imports;
//
// use App\rezrvnumModel;
// use Maatwebsite\Excel\Concerns\ToModel;
//
// class upnmsImport implements ToModel
// {
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function model(array $row)
//     {
//         return new rezrvnumModel([
//             //
//         ]);
//     }
// }



namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class updImsiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)

    {
            return new updImsi([
                'number'  => $row['pNum_Code'],
                'imsi'  => $row['pIMSI'],
            ]);
        }
    }
