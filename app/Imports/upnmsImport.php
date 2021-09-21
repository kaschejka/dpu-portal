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

class upnmsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)

    {
            return new nms([
                'number'  => $row['pNum_Code'],
                'imsi'  => $row['pIMSI'],
                'project'  => $row['pProject_Name'],
                'chanel'  => $row['pDistrib_Channel'],
                'region'  => $row['pRegion'],
                'owner'  => $row['pOwner'],
            ]);
        }
    }
