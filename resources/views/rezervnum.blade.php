<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Резерв номеров') }}
        </h2>
    </x-slot>

    <div class="py-12">

      <table  id="errTable" align="center" hidden>
      <tr> <td><img src="/attention.jpg" alt="Ошибки в номерах!"></td> <td><label id="errlable"></lable></td></tr>
      </table>

      <form  action="/rezervnum/submit" method="post" enctype="multipart/form-data">
        @csrf
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
              <div class="form-group">
              <label for="manager">Указать для какого менеджера осуществляется резерв (*)</label>

            <?php $rpodp = DB::table('manager')->get();
            echo "<input list='character' id='manager' class='form-control' name='manager' autocomplete='off' required>";
            echo "<datalist id='character' name='manager'>";
            foreach ($rpodp as $rpodp) {
            echo "<option value='".$rpodp->FIO."'</option>";
            }
            echo "</datalist>";
            ?>
            </div>

              <div class="form-group">
                <label for="description">Введите номер задачи (*)</label>
                <input type="text" name="description" placeholder="DPUNUM-XXXXX" id="description" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="company">Название компании/ЛС для которой осуществляется резерв</label>
              <input type="text" name="company"  id="company" class="form-control">
            </div>
             <div class="form-group">
                <label for="description">Выберите дату резервирования номеров</label>
                <input type="date" name="daterezerv" id="daterezerv" class="form-control" value="<?php echo date("Y-m-d", strtotime("+1 month"));?>">
                </div>
                <label>Выберите вариант резервирования: </label>
                <input type="radio" class="btn-check" name="typeRezerv" id="numSpisok" value="spisok">
                <label class="btn btn-outline-success" for="numSpisok">Резерв из списка</label>
                <input type="radio" class="btn-check" name="typeRezerv" id="numRandom" value="random">
                <label class="btn btn-outline-success" for="numRandom">Случайные номера</label>
                <span hidden id="abcDef">
                <input type="checkbox" class="btn-check" id="abcCh" autocomplete="off">
              <label class="btn btn-outline-success" for="abcCh">ABC</label>
              <input type="checkbox" class="btn-check" id="defCh" autocomplete="off">
              <label class="btn btn-outline-success" for="defCh">DEF</label>
            </span>

                <div class="form-group" hidden id="rezervSpisok">
                  <label>Выберите вариант предоставления номеров: </label>
                  <div class="input-group">

        <div class="input-group-text">

          <input class="form-check-input" type="radio" name="rs"  id="num" value="num">
          <label for="num">Номер(а) через пробел</label>
        </div>
        <input type="text" class="form-control" name="reznum" placeholder="79587001234 78005550345" id="reznum">
        <div class="input-group-text">
          <input class="form-check-input" type="radio" name="rs"  id="sfile" value="fnum">
          <label for="sfile">Файл с номерами</label>
        </div>
      <input type="file" class="form-control" name="file" id = "file">
      </div>
                </div>
            </div>
        </div>
        <br>
        @include('/inc/abcRezerv')
        <br>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="rdef" hidden>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
        @include('/inc/defRezerv')
            <button type="button"  id="printcount" class="btn btn-success" style="display:flex;justify-content:center;align-items:center;">Показать количество</button>
            </div>
        </div>
        <br>
        <div class="form-group" style="display:flex;justify-content:center;align-items:center;">
        <button type="button" class="btn btn-success" id="rezerv">Зарезервировать</button> &nbsp&nbsp
        <button type="button" class="btn btn-success" id="vibor">Номера на выбор (обычной категории)</button>

      </div>
      </form>
    </div>
<lable id="author" hidden><?php $author = auth()->user()->name; echo $author;?></lable>
    <script>

    document.getElementById("rezerv").addEventListener('click', function() {
      document.getElementById('errTable').hidden = true;
      var id = Math.floor(Math.random() * 10000000);
var company = document.getElementById("company").value;
var dpunum = document.getElementById("description").value;
var daterezerv = document.getElementById("daterezerv").value;
var author = document.getElementById('author').textContent;
      var val = document.getElementById('manager').value;
      var manager = $('#character').find('option[value="' + val + '"]').attr('value');
      if (document.getElementById("numSpisok").checked == true) {
        if (document.getElementById("num").checked == true) {
          var selectInputNum = 'numberString';
          var number = document.getElementById("reznum").value;
        } else {
          var selectInputNum = 'numberFile';
        }
      }
      if (document.getElementById("numRandom").checked == true) {
        var formDataCheck = document.querySelectorAll('input[type="checkbox"]');
        var selectnum = [];
        for(let i = 0; i < formDataCheck.length; i++){
          if ((formDataCheck[i].checked == true) && (formDataCheck[i].id != 'abcCh') && (formDataCheck[i].id != 'defCh')) {
            selectnum.push([formDataCheck[i].id, document.getElementsByName(formDataCheck[i].id)[0].value]);
          }
        }
        var selectInputNum = 'numberRandom';
        var number = selectnum;
        console.log(selectnum);
      }

if (selectInputNum == 'numberFile') {
  var formData = new FormData();
  var numfile = document.querySelector('#file');
  if (numfile.files[0]) {
    var number = numfile.files[0];
  } else {
    var number = '';
  }
  formData.append("number", number);
  formData.append("id", id);
  formData.append("selectInputNum", selectInputNum);
  formData.append("company", company);
  formData.append("dpunum", dpunum);
  formData.append("daterezerv", daterezerv);
  formData.append("manager", manager);
  formData.append("author", author);
  axios.post('api/numreserv', formData, {
  headers: {
  'Content-Type': 'multipart/form-data'
  }
  })
  .then(function (response) {
  console.log(response.data);
  if (response.data['alarm'] != '') {
    document.getElementById('errTable').hidden = false;
    document.getElementById('errlable').textContent = response.data['alarm'];
  }
  if (response.data['result']) {
    axios({
  method: 'post',
  responseType: 'blob',
  url: 'api/exportResultReserve',
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
} else {
  axios({
method: 'post',
url: 'api/numreserv',
data: {
  id: id,
selectInputNum: selectInputNum,
number: number,
company: company,
dpunum: dpunum,
daterezerv: daterezerv,
manager: manager,
author: author,
}
})
.then(function (response) {
console.log(response.data);
if (response.data['alarm'] != '') {
  document.getElementById('errTable').hidden = false;
  document.getElementById('errlable').textContent = response.data['alarm'];
}
if (response.data['result']) {
  axios({
method: 'post',
responseType: 'blob',
url: 'api/exportResultReserve',
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
}
    })


document.getElementById("vibor").addEventListener('click', function() {
  var nvibor = [];
  var vibor = document.querySelectorAll('input[type="checkbox"]');
  for(let i = 0; i < vibor.length; i++){
    if (vibor[i].checked == true) {
      nvibor.push(vibor[i].id);
    };
  }

  axios({
method: 'post',
responseType: 'blob',
url: 'api/viborNumber',
data: {
  id: nvibor,
}
})
.then(function (response) {
  const url = window.URL.createObjectURL(new Blob([response.data]));
const link = document.createElement('a');
link.href = url;
link.setAttribute('download', 'nunNaVybor.xlsx'); //or any other extension
document.body.appendChild(link);
link.click();
console.log(response.data);
})

})





    document.getElementById("printcount").addEventListener('click', function() {
    var  sum = 0;
        var x = document.getElementsByClassName("count-control");
        for(var i = 0; i<x.length; i++){
           sum += +x[i].value;
        }
        window.alert("Количество номеров к выдаче - " +sum);
    })

    var selectElem = document.getElementById('selectRegion')

    // Когда выбран новый элемент <option>
    selectElem.addEventListener('change', function() {

      var selind = selectElem.options.selectedIndex
         var val= selectElem.options[selind].value
         var pElem = document.getElementById(val)

    pElem.hidden = false;
    var minputElements = pElem.querySelectorAll('input[type="checkbox"]');
    for(let i = 0; i < minputElements.length; i++){
      minputElements[i].checked = true;
    }



  })

var selectElemDef = document.getElementById('selectRegionDEF');

selectElemDef.addEventListener('change', function() {

  var selindDef = selectElemDef.options.selectedIndex
     var valDef= selectElemDef.options[selindDef].value
     var pElemDef = document.getElementById(valDef)

pElemDef.hidden = false;
var minputElementsDEF = pElemDef.querySelectorAll('input[type="checkbox"]');
for(let i = 0; i < minputElementsDEF.length; i++){
  minputElementsDEF[i].checked = true;
}

})

    document.getElementById("numSpisok").addEventListener('change', function() {
      document.getElementById("rezervSpisok").hidden = false;
      document.getElementById("abcDef").hidden = true;
      document.getElementById("rdef").hidden = true;
      document.getElementById("rabc").hidden = true;
      var minputElementsDEF = document.querySelectorAll('input[type="checkbox"]');
      for(let i = 0; i < minputElementsDEF.length; i++){
        minputElementsDEF[i].checked = false;
      }

    })
    document.getElementById("numRandom").addEventListener('change', function() {
      document.getElementById("abcDef").hidden = false;
      document.getElementById("rezervSpisok").hidden = true;

    })
    document.getElementById("abcCh").addEventListener('change', function() {
      var index = document.getElementById("abcCh").checked;
      if (index == true) {
        document.getElementById("rabc").hidden = false;
      } else {   document.getElementById("rabc").hidden = true;
      var abcsel = document.querySelectorAll(".abc-sel");
      for(let i = 0; i < abcsel.length; i++){
        abcsel[i].checked = false;
      }

      }
    })
    document.getElementById("defCh").addEventListener('change', function() {
      var index = document.getElementById("defCh").checked;
      if (index == true) {
        document.getElementById("rdef").hidden = false;
      } else {   document.getElementById("rdef").hidden = true;
      var defsel = document.querySelectorAll(".def-sel");
      for(let i = 0; i < defsel.length; i++){
        defsel[i].checked = false;
      }
      }
    })
</script>
</x-app-layout>
