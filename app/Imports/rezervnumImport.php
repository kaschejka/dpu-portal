<?php

namespace App\Imports;

use App\rezrvnumModel;
use Maatwebsite\Excel\Concerns\ToModel;

class rezervnumImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new rezrvnumModel([
            //
        ]);
    }
}
