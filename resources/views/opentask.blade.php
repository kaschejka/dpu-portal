<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Задачи пришедшие через этот портал') }}
        </h2>
    </x-slot>

    <div class="py-12">

      <table  id="errTable" align="center" hidden>
      <tr> <td><img src="/attention.jpg" alt="Ошибки в номерах!"></td> <td><label id="errlable"></lable></td></tr>
      </table>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">



              @if (auth()->user()->role == 'manager')
              <?php $rpodp = DB::table('napominalka')->where('mail','=', auth()->user()->email)->get();
            echo "  <tr> <td>Номер задачи</td> <td>Конец резерва</td><td>Компания</td><td></td></tr>";
            echo "  </thead>";
            echo "  <tbody>";
                   foreach ($rpodp as $rpodp) {
                   echo "<tr>";
                   echo "<td><form action='my_reserved' method='get'><input type='hidden' id='descr' name='description'  value='".$rpodp->description."'><input type='hidden' id='enddate' name='enddate'  value='".$rpodp->end_date."'><button type='submit' class='btn btn-success'>".$rpodp->description."</button></form></td>";
                   echo "<td>".$rpodp->end_date."</td>";
                   echo "<td>".$rpodp->company."</td>";
                   echo "<td><form action='editcompany' method='get'><input type='hidden' id='descr' name='description'  value='".$rpodp->description."'><input type='hidden' id='comp' name='company'  value='".$rpodp->company."'><button type='submit' class='btn btn-success'>Редактировать</button></form></td>";
                   echo "</tr>";
                   }

                    ?>
              @endif

              @if (auth()->user()->role == 'admin')

              <form  action="" method="post" enctype="multipart/form-data">
                @csrf
                <lable id="author" hidden><?php $author = auth()->user()->name; echo $author;?></lable>
                <div class="form-group">
                  <label for="description">Номер задачи</label>
                  <input type="text" name="description"  id="description" class="form-control" value={{$DPUNUM}} disabled>
              </div>
              <div class="form-group">
                <label for="manager">Автор задачи</label>
                <input type="text" name="manager"  id="manager" class="form-control" value={{$manager}} disabled>
            </div>
            <div class="form-group">
              <label for="company">Компания</label>
              <input type="text" name="company"  id="company" class="form-control" value={{$task->company}} disabled>
          </div>

          <div class="form-group">
            <label for="typeTrafic">Тип трафика</label>
            <input type="text" name="typeTrafic"  id="typeTrafic" class="form-control" value={{$task->typeTrafic}} disabled>
          </div>
          <div class="form-group">
            <label for="opisanie">Описание</label>
            <textarea class="form-control" name="opisanie" id="opisanie" disabled>{{$task->description}}</textarea>
          </div>
          <div class="form-group">
             <label for="description">Выберите дату резервирования номеров</label>
             <input type="date" name="daterezerv" id="daterezerv" class="form-control" value="<?php echo date("Y-m-d", strtotime("+1 month"));?>">
             </div>
             <div class="form-group">
               <label for="sms">СМС на номерах</label>
               <input type="text" name="typeTrafic"  id="sms" class="form-control" value={{$task->defsms}} disabled>
             </div>
<table class="table" id="FTable" >
  <thead class="table-dark">
    <tr> <td>Регион</td><td>Остаток</td></tr>
  </thead>
    <tbody>

  @foreach ($task->selectnum as $region)
  <?php

  if ($region[0] == 'a495' || $region[0] == 'a499') {
    if ($region[0] == 'a495' ) {
    $ruNameRegion = 'Москва 495';
    $col = DB::table('ostatok_num')
                ->where([['id_region','=', '499'],['project','=','mttb']])
                ->first();
    if (isset($col)) {
      $col = $col->col_abc;
    } else {
      $col = 0;
    }
    }
    if ($region[0] == 'a499' ) {
     $ruNameRegion = 'Москва 499';
     $col = DB::table('ostatok_num')
                 ->where([['id_region','=', '495'],['project','=','mttb']])
                 ->first();
     if (isset($col)) {
       $col = $col->col_abc;
     } else {
       $col = 0;
     }
    }

  } else {
    if (substr($region[0],0,1) == 'a') {
      $idVr = substr($region[0], 1);
      $n = DB::table('region')->where('id','=', $idVr)->first();
    $ruNameRegion = $n->ru_name_abc;
    $col = DB::table('ostatok_num')
                ->where([['id_region','=', $idVr],['project','=','mttb']])
                ->first();
    if (isset($col)) {
      $col = $col->col_abc;
    } else {
      $col = 0;
    }
    }
    if (substr($region[0],0,1) == 'd') {
      $idVr = substr($region[0], 1);
      if ($idVr == 211) {
        $ruNameRegion = 'Москва (Московская область)';
      } else {
        $n = DB::table('region')->where('id','=', $idVr)->first();
      $ruNameRegion = $n->ru_name_def;
      }

    $col = DB::table('ostatok_num')
                ->where([['id_region','=', $idVr],['project','=','Gnezdo']])
                ->first();
    if (isset($col)) {
      $col = $col->col_def;
    } else {
      $col = 0;
    }
    }
  }
  ?>
  <tr>
    <td>
  <div class='input-group mb-3'>
  <div class='input-group-text'>
  <input class='form-check-input' type='checkbox' name='region[]' id='{{$region[0]}}' value='{{$region[0]}}' aria-label='Checkbox for following text input' checked>
  <label  for='{{$region[0]}}'>{{$ruNameRegion}}</label>
  </div>
  <input type='text' class='form-control' name='{{$region[0]}}' id='{{$region[0]}}' value='{{$region[1]}}' aria-label='Text input with checkbox'>
  </div>
</td>
<td>
  {{$col}}
</td>
  @endforeach

              @endif

            </tbody>
            </table>
<button type="button" class="btn btn-success" id="rezerv">Зарезервировать</button>
<button type="button" class="btn btn-success" id="closeTask">Отклонить</button>
</form>
            </div>
          </div>
        </div>

<script>
document.getElementById("closeTask").addEventListener('click', function() {
  var dpunum = document.getElementById("description").value;
  axios({
  method: 'post',
  url: 'http://192.168.146.82/api/closeTask',
  data: {
  task: dpunum,
  }
  })
  .then(function (response) {
  console.log(response.data);
  window.alert("Задача отклонена! Дальнейшая работа в Jira");
  })

})
document.getElementById("rezerv").addEventListener('click', function() {
  var id = Math.floor(Math.random() * 10000000);
  var author = document.getElementById('author').textContent;
var company = document.getElementById("company").value;
var dpunum = document.getElementById("description").value;
var daterezerv = document.getElementById("daterezerv").value;
var manager = document.getElementById('manager').value;
var formDataCheck = document.querySelectorAll('input[type="checkbox"]');
var selectnum = [];
for(let i = 0; i < formDataCheck.length; i++){
  if (formDataCheck[i].checked == true) {
    selectnum.push([formDataCheck[i].id, document.getElementsByName(formDataCheck[i].id)[0].value]);
  }
}
var selectInputNum = 'numberRandom';
console.log(selectnum);

axios({
method: 'post',
url: 'http://192.168.146.82/api/numreserv',
data: {
id: id,
selectInputNum: selectInputNum,
number: selectnum,
company: company,
dpunum: dpunum,
daterezerv: daterezerv,
manager: manager,
author: author,
}
})
.then(function (response) {
console.log(response.data);
// Закрытие задачи в локальной БД
axios({
method: 'post',
url: 'http://192.168.146.82/api/closeTask',
data: {
task: dpunum,
}
})
.then(function (response) {
console.log(response.data);
})
//
if (response.data['alarm'] != '') {
document.getElementById('errTable').hidden = false;
document.getElementById('errlable').textContent = response.data['alarm'];
}
if (response.data['result']) {
axios({
method: 'post',
responseType: 'blob',
url: 'http://192.168.146.82/api/exportResultReserve',
data: {
dpunum: dpunum,
result: response.data['result'],
}
})
.then(function (response) {
const url = window.URL.createObjectURL(new Blob([response.data]));
const link = document.createElement('a');
link.href = url;
link.setAttribute('download', dpunum+'.xlsx'); //or any other extension
document.body.appendChild(link);
link.click();
console.log(response.data);
})
}

})


})
</script>


</x-app-layout>
