<form  action="/config/submit" method="post" enctype="multipart/form-data">
  @csrf
<div class="form-group">
<label for="fiom">ФИО менеджера</label>
<input type="text" class="form-control" name="fiom" placeholder="" id="fiom" required><span id="status1"></span>
</div>

<div class="form-group">
<label for="mail">E-mail</label>
<input type="text" class="form-control" name="mail" placeholder="" id="mail" required><span id="status"></span>
</div>
<button type="submit" class="btn btn-success">Добавить менеджера</button>
</form>

<br>

<?php $manager = DB::table('manager')->get();
?>
<table class="table">
<thead class="thead-dark">
  <tr>
    <th scope="col">ФИО</th>
    <th scope="col">Email</th>
    <th scope="col"></th>
    <th scope="col"></th>
  </tr>
</thead>
<tbody>
@foreach ($manager as $manager)

<form  method="post" enctype="multipart/form-data"  >
  @csrf
<tr id="mm{{$manager->id}}">


<td> <input type="text"  name="fiomanager" id="m{{$manager->FIO}}" value = "{{$manager->FIO}}" disabled> </td>
<td> <input type="text" name="emailmanager" id="m{{$manager->mail}}" value = "{{$manager->mail}}" disabled></td>

<td> <button type="button" id="m{{$manager->id}}" name="m{{$manager->mail}}" class="btn btn-success" value="edit" onclick="edit(this)">Редактировать</button> </td>
<td> <button type="button" id="m{{$manager->id}}" class="btn btn-success" onclick="del(this)" >Удалить</button> </td>
</tr>
</form>

@endforeach
</tbody>
</table>
<script>
function edit(obj) {
var idm = obj.id;
var indm = 'm'+obj.id;
if (obj.value == 'edit') {
obj.innerHTML = 'Сохранить';
obj.value = 'save';
var mdivElem = document.getElementById(indm);
var minputElements = mdivElem.querySelectorAll('input[type="text"]');
for(let i = 0; i < minputElements.length; i++){
  minputElements[i].disabled = false;
  minputElements[i].style.background = '#e4f5f0';
}

} else {

var mdivElem = document.getElementById(indm);
var minputElements = mdivElem.querySelectorAll('input[type="text"]');
for(let i = 0; i < minputElements.length; i++){
  minputElements[i].disabled = true;
  minputElements[i].style.background = 'white';
  if (minputElements[i].name == 'fiomanager') {
    fio = document.getElementById(minputElements[i].id).value;
  }
  if (minputElements[i].name == 'emailmanager') {
    mail = document.getElementById(minputElements[i].id).value;
  }
}
axios({
method: 'post',
url: 'api/editManager',
data: {
id: idm,
fio: fio,
mail: mail,
}
})
.then(function (response) {
  console.log(response.data);
})

obj.innerHTML = 'Редактировать';
obj.value = 'edit';
}

}

function del(obj) {
var mid = obj.id;
axios({
method: 'post',
url: 'api/delManager',
data: {
id: mid,
}
})
.then(function (response) {
  console.log(response.data);
})
document.location.reload();
return false;
}


</script>
