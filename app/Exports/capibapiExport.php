<?php

namespace App\Exports;

use App\User;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class capibapiExport implements FromArray, WithHeadings
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
            'type_name',
            'area_code',
            'category_id',
            'type_name_bapi',
            'subscription_once',
            'subscription_monthly',
            'category_id',
            'type_name_capi'
          ];
      }


}
