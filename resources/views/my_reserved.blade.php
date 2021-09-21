<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
              <div>
              <form action="/my_reserve" method="get">
                <button type="submit" class="btn btn-success">Назад</button>
              </form>
            </div>
  @if (auth()->user()->role == 'admin')

<form action="/prodlenie_rezerva" method="post" enctype="multipart/form-data">
  @csrf

  <div class="form-group">
  <br>
  <?php $d=$_GET['description'];
   $end=$_GET['enddate']?>
  <input type="hidden" name="description" id="description" value = {{$d}}>
  <input type="hidden" name="olddate" id="olddate" value = {{$end}}>
  <label for="end_date">Новая дата окончания резерва</label>
  <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo date("Y-m-d", strtotime("+1 month"));?>">
  </div>
  <button class="btn btn-success">Продлить резерв</button>
</form>
  @endif
  <div>
    <br>
              <table class="table" id="myTable">
                <thead class="table-dark">
            <tr> <td>Номер</td> <td>Оператор</td><td>Регион</td></tr>
            </thead>
            <tbody>

            </tbody>
            </table>

</div>
            </div>
          </div>
        </div>
        <script>
        const tableData = {!! json_encode($hmodel) !!};
        const renderOptions = (data, dataKey, filterId) => {
          const options = Array.from(new Set(data.map(row => row[dataKey]))).map(value => `<option value="${value}">${value}</option>`).join('');
          document.querySelector(`#${filterId}`).innerHTML = options;
        };
        const getTableBody =  data => data.map(row => `<tr>${Object.values(row).map(td => `<td>${td}</td>`).join('')}</tr>`).join('');
        const renderTable = data => {
          document.querySelector('#myTable tbody').innerHTML = getTableBody(data);
          renderOptions(data, 'number', 'number');
          renderOptions(data, 'operator', 'operator');
          renderOptions(data, 'region', 'region');
        };
        renderTable(tableData);
        </script>

</x-app-layout>
