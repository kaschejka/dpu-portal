<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class rezervnumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'daterezerv'=>'required|date|after:tomorrow',
      'description'=>'required'
        ];
    }

 public function messages()
{
  return [
    'rezfile.required'=>'Не выбран файл с номерами',
    'description.required'=>'Не введен номер задачи в рамках которой происходит резервирование номеров',
    'daterezerv.required'=>'Не выбрана дата резервации номеров',
    'daterezerv.after'=>'Дата резервирования не может быть меньше чем сегодня',
  ];
}

}
