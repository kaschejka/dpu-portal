<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('История резерва') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
@if(isset($hmodel))
<button type="button" class="btn btn-success" onclick="fnExcelReport();">Скачать</button>
<button type="button" class="btn btn-success" id="filter">Отфильтровать</button>
<div>
  <iframe id="txtArea1" style="display:none"></iframe>
  <br>
</div>
<div class="form-floating">

  <table class="table" id="FTable">
    <thead class="table-dark">
<tr> <td>Номер</td> <td>Задача</td><td>Дата выдачи</td><td>Конец резерва</td><td>Кто выдал</td><td>Кому выдано</td></tr>
</thead>
<tbody>
<tr>
   <td><select class="form-select" size="3" name="number[]" id="number"  multiple></select></td>
   <td><select class="form-select" size="3" name="description[]" id="description"  multiple>  </select></td>
   <td><select class="form-select" size="3" name="date_issue[]" id="date_issue"  multiple></select></td>
   <td><select class="form-select" size="3" name="end_date[]" id="end_date"  multiple></select></td>
   <td><select class="form-select" size="3" name="author[]" id="author"  multiple></select></td>
   <td><select class="form-select" size="3" name="manager[]" id="manager"  multiple></select></td>
 </tr>
</tbody>
</table>
</div>
 @endif
<div>
<br>
              <table class="table" id="myTable">
                <thead class="table-dark">
    <tr> <td>Номер</td> <td>Задача</td><td>Дата выдачи</td><td>Конец резерва</td><td>Кто выдал</td><td>Кому выдано</td></tr>
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
      renderOptions(data, 'num', 'number');
      renderOptions(data, 'description', 'description');
      renderOptions(data, 'date_issue', 'date_issue');
      renderOptions(data, 'end_date', 'end_date');
      renderOptions(data, 'author', 'author');
      renderOptions(data, 'manager', 'manager');
    };
    const getSelectFilter = filterId => {
      const numbersOptions = document.querySelectorAll(`#${filterId} option:checked`);
      return Array.from(numbersOptions).map(option => option.value);
    };
    const filterData = (data, filters) => data.filter(row => Object.entries(row).every(([key, value]) => !filters[key] || filters[key].length === 0 || filters[key].includes(value)));
    const filterTable = () => {
      const filteredData = filterData(tableData, {
        num: getSelectFilter('number'),
        description: getSelectFilter('description'),
        date_issue: getSelectFilter('date_issue'),
        end_date: getSelectFilter('end_date'),
        author: getSelectFilter('author'),
        manager: getSelectFilter('manager'),
      });

      renderTable(filteredData);
    }

    renderTable(tableData);
    document.querySelector('#filter').addEventListener('click', filterTable);

    function fnExcelReport() {
      var table= document.getElementById("myTable");
      var html = table.outerHTML;

      window.open('data:application/vnd.ms-excel,' + '\uFEFF' + encodeURIComponent(html));
    }
</script>
</x-app-layout>
