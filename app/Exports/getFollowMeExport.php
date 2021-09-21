<?php

namespace App\Exports;

use App\User;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class getFollowMeExport implements FromArray, WithHeadings
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
            "I_ACCOUNT",
    "I_FOLLOW_ORDER",
    "ACTIVE",
    "NAME",
    "REDIRECT_NUMBER",
    "DOMAIN",
    "PERIOD",
    "PERIOD_DESCRIPTION",
    "TIMEOUT",
    "KEEP_ORIGINAL_CLD",
    "KEEP_ORIGINAL_CLI"
          ];
      }


}
