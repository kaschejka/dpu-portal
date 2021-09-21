<form   method="post" enctype="multipart/form-data">
  @csrf
<div class="form-group">
<label for="idregion">ID региона</label>
<input type="text" class="form-control" name="idregion" placeholder="" id="idregion" required>
</div>

<div class="form-group">
<label for="ru_name_abc">Название АВС города</label>
<input type="text" class="form-control" name="dolzh" placeholder="" id="ru_name_abc" >
</div>
<div class="form-group">
<label for="ru_name_def">Название DEF региона</label>
<input type="text" class="form-control" name="ru_name_def" placeholder="" id="ru_name_def" >
</div>
<div class="form-group">
  <input type="checkbox" class="btn-check" id="abc_activate" autocomplete="off">
  <label class="btn btn-outline-success" for="abc_activate">ABC on/off</label>
  <input type="checkbox" class="btn-check" id="def_activate" autocomplete="off">
  <label class="btn btn-outline-success" for="def_activate">DEF on/off</label>
</div>
<button type="button" class="btn btn-success" onclick="addregion()">Добавить регион</button>
</form>

<script>
function addregion(){

  var  idregion = document.getElementById('idregion').value;
  var  ru_name_abc = document.getElementById('ru_name_abc').value;
  var  ru_name_def = document.getElementById('ru_name_def').value;
  var abc_active = document.getElementById('abc_activate').checked;
  var def_active = document.getElementById('def_activate').checked;
  axios({
  method: 'post',
  url: 'api/config',
  data: {
  method: 'addRegion',
  id: idregion,
  ru_name_abc: ru_name_abc,
  ru_name_def: ru_name_def,
  abc_active: abc_active,
  def_active: def_active,
  }
  })
  .then(function (response) {
    console.log(response.data);
  })
  document.location.reload();
}
</script>

<br>

<div class="form-group">
<?php $region = DB::table('region')->orderBy('ru_name_abc', 'asc')->get();
?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">id</th>
      <th scope="col">Название города АВС</th>
      <th scope="col">Название региона для DEF</th>
      <th scope="col">on/off АВС</th>
      <th scope="col">on/off DEF</th>
      <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
@foreach ($region as $region)
<form  method="post" enctype="multipart/form-data">
<tr id="rr{{$region->id}}">
<td>{{ $region->id }}</td>
 <td>{{ $region->ru_name_abc }}<br> <input type="hidden"  name="ru_name_abc" id="a{{$region->id}}" value = "{{$region->ru_name_abc}}" ></td>
<td> {{ $region->ru_name_def }}<br> <input type="hidden"  name="ru_name_def" id="d{{$region->id}}" value = "{{$region->ru_name_def}}" ></td>
<td> @if ($region->abc_activate === 1) <input class="form-check-input" type="checkbox" id="abc_activer{{$region->id}}"  checked>  @else <input class="form-check-input" type="checkbox" id="abc_activer{{$region->id}}" > @endif</td>
<td> @if ($region->def_activate === 1) <input class="form-check-input" type="checkbox" id="def_activer{{$region->id}}"  checked>  @else <input class="form-check-input" type="checkbox" id="def_activer{{$region->id}}" > @endif </td>
<td><button type="button" id="r{{$region->id}}"  class="btn btn-success" value="edit" onclick="editregion(this)">Редактировать</button> </td>
<td><button type="button" class="btn btn-success" id="r{{$region->id}}" onclick="delregion(this)">Удалить</button> </td>
</tr>
</form>


@endforeach
</tbody>
</table>
</div>
<script>
function editregion(obj) {
var rid = obj.id;
var rind = 'r'+obj.id;

if (obj.value == 'edit') {
obj.innerHTML = 'Сохранить';
obj.value = 'save';
var rdivElem = document.getElementById(rind);
var rinputElements = rdivElem.querySelectorAll('input[type="hidden"]');
for(let i = 0; i < rinputElements.length; i++){
  rinputElements[i].type = 'text';
  rinputElements[i].style.background = '#e4f5f0';
}

} else {

var rdivElem = document.getElementById(rind);
var rinputElements = rdivElem.querySelectorAll('input[type="text"]');
var abc_active = document.getElementById('abc_active'+rid).checked;
var def_active = document.getElementById('def_active'+rid).checked;
for(let i = 0; i < rinputElements.length; i++){
  rinputElements[i].type = 'hidden';
  if (rinputElements[i].name == 'ru_name_abc') {
    ru_name_abc = document.getElementById(rinputElements[i].id).value;
  }
  if (rinputElements[i].name == 'ru_name_def') {
    ru_name_def = document.getElementById(rinputElements[i].id).value;
  }
}
axios({
method: 'post',
url: 'api/config',
data: {
method: 'editRegion',
id: rid,
ru_name_abc: ru_name_abc,
ru_name_def: ru_name_def,
abc_active: abc_active,
def_active: def_active,
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


function delregion(obj) {
var rid = obj.id;
axios({
method: 'post',
url: 'api/config',
data: {
method: 'delRegion',
id: rid,
}
})
document.location.reload();
}
</script>
