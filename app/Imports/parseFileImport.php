<?php

namespace App\Imports;

use App\parseFileModel;
use Maatwebsite\Excel\Concerns\ToModel;

class parseFileImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new parseFileModel([
            //
        ]);
    }
}
