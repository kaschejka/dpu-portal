<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Ошибки Ошибочки') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">


              @if(isset($alarm))
              <table  id="errTable" align="center">
              <tr> <td><img src="/attention.jpg" alt="Ошибки в номерах!"></td> <td>{{$alarm}}</td></tr>
              </table>
              @endif
              @if(isset($rezerv))
              <table class="table" id="rezervnumTable" style="display:none">
                <thead class="table-dark">
              <tr> <td>Номер</td><td>IMSI</td> <td>Оператор</td><td>Регион</td><td>Конец резерва</td><td>Reserv_uid/Ошибка</td></tr>

              </thead>
              <tbody>
              @foreach ($rezerv as $rezerv)
              <tr> <td>{{$rezerv['number']}}</td><td>{{$rezerv['imsi']}}</td> <td>{{$rezerv['operator']}}</td><td>{{$rezerv['region']}}</td><td>{{$rezerv['end_date']}}</td><td>{{$rezerv['uid']}}</td></tr>
              @endforeach
              </tbody>
              </table>
              <script>
              let table = document.querySelector("#rezervnumTable");
            TableToExcel.convert(table,{name: '{{$descr}}'});
              </script>
              @endif


            </div>
          </div>
        </div>

</x-app-layout>
