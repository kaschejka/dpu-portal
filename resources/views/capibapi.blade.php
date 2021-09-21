<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Файл для CAPI/BAPI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
              @if(isset($err))
              <span style="color: red">{{$err}}</span>
              <br>
              <br>
              @endif
              <label id="progressStatus">Текущий процесс: </label>
              <div class="progress">
               <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" id="progress"  style="width: 0%"></div>
             </div>

<br>
              <form  method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">

                  <div class="input-group">
                  <div class="input-group-text">
                  <input class="form-check-input" type="radio" name="selectInputNum"  id="numberString" value="numberString" checked>
                  <label for="numberString">Номер(а) через пробел</label>
                  </div>
                  <input type="text" class="form-control" name="number" id = "number">
                  <div class="input-group-text">
                  <input class="form-check-input" type="radio" name="selectInputNum"  id="numberFile" value="numberFile" ref="file">
                  <label for="numberFile">Файл с номерами</label>
                  </div>
                  <input type="file" class="form-control" name="file" id="file">
                  </div>
               </div>


              <div>
                <br>
              </div>
                  <div class="form-group">
                  <button type="button" class="btn btn-success" id="submit">Определить</button>
                </div>
              </form>
            </div>
        </div>
<br>

    </div>

    <script>
       document.getElementById("submit").addEventListener('click', function() {
         var id = Math.floor(Math.random() * 10000000);
         var numberString = document.getElementById("numberString").checked;
         var numberFile = document.getElementById("numberFile").checked;
         if (numberString == true) {
           var selectInputNum = 'numberString';
           var number = document.getElementById("number").value;

         axios({
       method: 'post',
       responseType: 'blob',
       url: 'api/capibapi',
       data: {
         id: id,
       selectInputNum: selectInputNum,
       number: number,
     }
       })
       .then(function (response) {
         const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'capibapi.xlsx'); //or any other extension
    document.body.appendChild(link);
    link.click();
     })
         }

         if (numberFile == true) {
           var selectInputNum = 'numberFile';
           var number = document.getElementById("file").value;
           var formData = new FormData();
           var numfile = document.querySelector('#file');
           formData.append("number", numfile.files[0]);
           formData.append("id", id);
           formData.append("selectInputNum", selectInputNum);
           axios.post('api/capibapi', formData, {
           responseType: 'blob',
           headers: {
           'Content-Type': 'multipart/form-data'
           }
           })
       .then(function (response) {
         const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'capibapi.xlsx'); //or any other extension
      document.body.appendChild(link);
      link.click();
      })
         }


     let timerId = setInterval(function() {
       axios.get('api/progress?id='+id)
     .then(function (response) {
       document.getElementById("progressStatus").innerHTML = 'Текущий статус: '+response.data[0];
       document.getElementById("progress").innerHTML = response.data[1]+'%';
       document.getElementById("progress").style = 'width:'+response.data[1]+'%';
             if (response.data[0] == 'SUCCES') {
               clearInterval(timerId);
               document.getElementById("progressStatus").innerHTML = 'Текущий статус: '+response.data[0];
                document.getElementById("progress").innerHTML = response.data[1]+'%';
             }
       console.log(response.data[0]);
   })
       }, 1000);
                   })




      </script>

</x-app-layout>
