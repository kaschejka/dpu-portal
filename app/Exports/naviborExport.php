<?php

namespace App\Exports;

use App\User;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class naviborExport implements FromArray, WithHeadings
{
  protected $invoices;

  public function __construct(array $invoices)
  {
      $this->invoices = $invoices;
  }

  public function array(): array
  {
      return $this->invoices;
  }

  public function headings(): array
      {
          return [
            'Номер',
            'Город'
          ];
      }


}
