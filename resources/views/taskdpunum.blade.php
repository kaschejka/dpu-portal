<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Создание задачи в DPUNUM') }}
        </h2>
    </x-slot>

    <div class="py-12">
<?php $manager = auth()->user()->email;?>

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="FormJiraLogin">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
      <form action="/" method="post" enctype="multipart/form-data" id="JiraLog">
        <lable>Необходимо ввести доменный пароль:</lable>
        <div class="form-group" hidden>
          <label for="officeLogin">Логин доменный</label>
          <input class="form-control" type="text" name="officeLogin" id="officeLogin" value="{{$manager}}">
        </div>
        <div class="form-group">
          <label for="officePassword">Пароль доменный</label>
          <input class="form-control" type="password" name="officePassword" id="officePassword">
        </div>
        <button type="button" id="loginJira"  class="btn btn-primary">Войти в jira</button>
      </form>

      <script>
      document.getElementById("loginJira").addEventListener('click', function() {
        var loginJira = document.getElementById("officeLogin").value;
        var passwordJira = document.getElementById("officePassword").value;
        axios({
      method: 'post',
      url: 'api/getSesionJira',
      data: {
        username: loginJira,
        password: passwordJira,
      }
      })
      .then(function (response) {
      console.log(response.data);
      if (response.data) {
        window.alert("Регистрация в Jira прошла успешно! Можете приступать к созданию задачи.");
      } else {
        window.alert("Неправильный пароль");
      }
      document.location.reload();
      })
      })
      </script>
</div>
</div>
<br>

@if (!empty(Cache::get($manager)))
  <input type="hidden" id="succes_login" value="">
@endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="formTask">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">



              <form  action="/" method="post" enctype="multipart/form-data" id="task">
              @csrf
              <div class="form-group">

            <div class="form-group">
              <label for="company">Название компании/ЛС для которой осуществляется резерв</label>
              <input type="text" name="company"  id="company" class="form-control">
            </div>
            <div class="form-group">
              <label for="typeTrafic">Тип трафика</label>
              <select name='typeTrafic' id='typeTrafic' class='form-control'>
              <option value="Коммерция">Коммерция</option>
              <option value="Тест">Тестовый</option>
            </select>
            </div>

            <div class="form-group">
              <label for="description">Описание</label>
              <textarea class="form-control" name="description" id="description"></textarea>
            </div>

            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
          <div class="form-group">
            <label for="company">Добавить в наблюдатели по задаче. Необходимо указать доменный login наблюдателя (Например: dkornev) </label>
            <input type="text" name="watcher"  id="watcher" placeholder="dkornev" class="form-control">
          </div>
        </div>
      
        <br>
        @include('/inc/abcTaskDPU')
        <br>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="rdef" >
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
        @include('/inc/defTaskDPU')
            </div>
        </div>
        <br>

        <div class="form-group" style="display:flex;justify-content:center;align-items:center;">

        <button type="button" id="createTask" name="{{$manager}}"class="btn btn-success">Отправить заявку</button>
      </div>
      </form>
    </div>
<script>


document.getElementById("createTask").addEventListener('click', function() {
  var formDataCheck = document.querySelectorAll('input[type="checkbox"]');
  var selectnum = [];
  for(let i = 0; i < formDataCheck.length; i++){
    if (formDataCheck[i].checked == true) {
      selectnum.push([formDataCheck[i].id, document.getElementsByName(formDataCheck[i].id)[0].value]);
    }

  }
var manger = document.getElementById("createTask");
var inp = document.getElementsByName('sms');
    for (var i = 0; i < inp.length; i++) {
        if (inp[i].type == "radio" && inp[i].checked) {
            var defsms = inp[i].value;
        }
    }
var watcher =  document.getElementById("watcher").value;
  axios({
method: 'post',
url: 'api/taskdpu',
data: {
  description: document.getElementById("description").value,
  company: document.getElementById("company").value,
  typeTrafic: document.getElementById("typeTrafic").value,
  defsms: defsms,
  selectnum: selectnum,
  manager: manger.name,
  watcher: watcher,
}
})
.then(function (response) {
console.log(response.data);
if (response.data == 'error_create_task') {
  window.alert('Пароль не обновлен или ошибка на стороне сервера Jira!');
  document.location.reload();
} else {
  window.alert('Создана задача - '+response.data);
}
})

})



  document.addEventListener("DOMContentLoaded", function(event) {
    if (document.getElementById('succes_login')) {
      document.getElementById('loginJira').disabled = true;
      document.getElementById('createTask').disabled = false;
    } else {
      document.getElementById('loginJira').disabled = false;
      document.getElementById('createTask').disabled = true;
    }

  });



</script>

</x-app-layout>
