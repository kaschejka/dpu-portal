<form   method="post" enctype="multipart/form-data">
  @csrf
<div class="form-group">
<label for="fiop">ФИО подписанта</label>
<input type="text" class="form-control" name="fiop" placeholder="И.И. Иванов" id="fiop" required>
</div>

<div class="form-group">
<label for="dolzhn">Должность</label>
<input type="text" class="form-control" name="dolzh" placeholder="Ведущий специалист" id="dolzhn" required>
</div>
<div class="form-group">
<label for="podpiRP">Должность + ФИО (Род. падеж)</label>
<input type="text" class="form-control" name="podpiRP" placeholder="Ведущего специалиста Иванова Ивана Ивановича" id="podpiRP" required>
</div>
<div class="form-group">
<label for="osnpodpmtt">Основание подписи</label>
<input type="text" class="form-control" name="osnpodpmtt" placeholder="доверенности №123 от 23.10.2007 г." id="osnpodpmtt" required>
</div>
<button type="button" class="btn btn-success" onclick="addpodpisant()">Добавить подписанта</button>
</form>

<script>
function addpodpisant(){

  var  fio = document.getElementById('fiop').value;
  var  dolzhnost = document.getElementById('dolzhn').value;
  var  dolzhnostRP = document.getElementById('podpiRP').value;
  var  osnpodpmtt = document.getElementById('osnpodpmtt').value;
  axios({
  method: 'post',
  url: 'api/config',
  data: {
  method: 'addPodpisant',
  fio: fio,
  dolzhnost: dolzhnost,
  dolzhnostRP: dolzhnostRP,
  osnpodpmtt: osnpodpmtt,
  }
  })
  .then(function (response) {
    console.log(response.data);
  })
  document.location.reload();
}
</script>


<br>

<?php $podpisant = DB::table('podpisantmtt')->get();
?>
<table class="table">
<thead class="thead-dark">
  <tr>
    <th scope="col">ФИО</th>
    <th scope="col">Должность</th>
    <th scope="col">Должность + ФИО (Род. падеж)</th>
    <th scope="col">Основание подписи</th>
    <th scope="col"></th>
    <th scope="col"></th>
  </tr>
</thead>
<tbody>
@foreach ($podpisant as $podpisant)

<form  method="post" enctype="multipart/form-data"  >
<tr id="pp{{$podpisant->id}}">


<td> <lable>{{$podpisant->FIO}}</lable><input type="hidden"  name="FIO" id="p{{$podpisant->FIO}}" value = "{{$podpisant->FIO}}" > </td>
<td> <lable>{{$podpisant->dolzhnost}}</lable><input type="hidden" name="dolzhnost" id="p{{$podpisant->dolzhnost}}" value = "{{$podpisant->dolzhnost}}" ></td>
<td> <lable>{{$podpisant->podpiRP}}</lable><input type="hidden" name="dolzhnostRP" id="p{{$podpisant->podpiRP}}" value = "{{$podpisant->podpiRP}}" ></td>
<td> <lable>{{$podpisant->osnpodpmtt}}</lable><input type="hidden" name="osnpodpmtt" id="p{{$podpisant->osnpodpmtt}}" value = "{{$podpisant->osnpodpmtt}}" ></td>

<td> <button type="button" id="p{{$podpisant->id}}" name="p{{$podpisant->FIO}}" class="btn btn-success" value="edit" onclick="editpodp(this)">Редактировать</button> </td>
<td> <button type="button" id="p{{$podpisant->id}}" class="btn btn-success" onclick="delpodpisant(this)" >Удалить</button> </td>
</tr>
</form>

@endforeach
</tbody>
</table>
<script>
function editpodp(obj) {
var id = obj.id;
var ind = 'p'+obj.id;
if (obj.value == 'edit') {
obj.innerHTML = 'Сохранить';
obj.value = 'save';
var divElem = document.getElementById(ind);
var inputElements = divElem.querySelectorAll('input[type="hidden"]');
for(let i = 0; i < inputElements.length; i++){
  inputElements[i].type = 'text';

  inputElements[i].style.background = '#e4f5f0';
}

} else {

var divElem = document.getElementById(ind);
var inputElements = divElem.querySelectorAll('input[type="text"]');
for(let i = 0; i < inputElements.length; i++){
  inputElements[i].type = 'hidden';
  if (inputElements[i].name == 'FIO') {
    fio = document.getElementById(inputElements[i].id).value;
  }
  if (inputElements[i].name == 'dolzhnost') {
    dolzhnost = document.getElementById(inputElements[i].id).value;
  }
  if (inputElements[i].name == 'dolzhnostRP') {
    dolzhnostRP = document.getElementById(inputElements[i].id).value;
  }
  if (inputElements[i].name == 'osnpodpmtt') {
    osnpodpmtt = document.getElementById(inputElements[i].id).value;
  }
}
axios({
method: 'post',
url: 'api/config',
data: {
method: 'editPodpisant',
id: id,
fio: fio,
dolzhnost: dolzhnost,
dolzhnostRP: dolzhnostRP,
osnpodpmtt: osnpodpmtt,
}
})
.then(function (response) {
  console.log(response.data);
})

obj.innerHTML = 'Редактировать';
obj.value = 'edit';
document.location.reload();
}

}

function delpodpisant(obj) {
var id = obj.id;
axios({
method: 'post',
url: 'api/config',
data: {
  method: 'delPodpisant',
id: id,
}
})
document.location.reload();
return false;
}


</script>
