<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
              <form action="/my_reserve" method="get">

    <button type="submit" class="btn btn-success">Назад</button>
  </form>
    <br>
    <?php $descr = $_GET['description'];
    $comp = $_GET['company']?>
    <form   method="post" enctype="multipart/form-data">
      @csrf
    <div class="form-group">
      <label for="description">Номер задачи</label>
      <input type="text" name="descrip"  id="descrip" class="form-control" value="{{$descr}}" disabled>
      <input type="hidden" name="description"  id="description" class="form-control" value="{{$descr}}">
  </div>
  <div class="form-group">
    <label for="company">Название компании</label>
    <input type="text" name="company"  id="company" class="form-control" value="{{$comp}}">
  </div>
  <div class="form-group">
  <button type="button" class="btn btn-success" id="submit">Сохранить</button>
</div>
</form>
            </div>
          </div>
        </div>

        <script>
           document.getElementById("submit").addEventListener('click', function() {
             var id = Math.floor(Math.random() * 10000000);
             var description = document.getElementById("description").value;
             var company = document.getElementById("company").value;


             axios({
             method: 'post',
             url: 'api/editcompany',
             data: {
             description: description,
             company: company,
             }
             })
             .then(function (response) {
               window.alert("Название компании успешно изменено");
             })



                       })



          </script>


</x-app-layout>
