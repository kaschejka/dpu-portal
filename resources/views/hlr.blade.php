<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Выгрузка номеров из HLR') }}
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

               <input type="radio" class="btn-check" name="outrez" id="rweb"  value="rweb" checked>
               <label class="btn btn-outline-success" for="rweb">Отобразить на экране</label>

               <input type="radio" class="btn-check" name="outrez" id="rfile"  value="rfile">
               <label class="btn btn-outline-success" for="rfile">Сохранить результат в файл</label>

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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="viewResult" hidden>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
                      <button type="button" class="btn btn-success" onclick="fnExcelReport()">Скачать</button>
                      <div>

                        <br>
                      </div>

        <div>
                      <table class="table" id="myTable">
                        <thead class="table-dark">
                      <tr> <td>Номер</td> <td>IMSI</td><td>Forwarding</td><td>Проект</td><td>SMS</td></tr>
                      </thead>
                      <tbody>

                      </tbody>
                      </table>
                    </div>
                    </div>
                </div>


    </div>

    <script>
       document.getElementById("submit").addEventListener('click', function() {
         var id = Math.floor(Math.random() * 10000000);
         var numberString = document.getElementById("numberString").checked;
         var numberFile = document.getElementById("numberFile").checked;
         if (numberString == true && document.getElementById("rfile").checked) {
           var selectInputNum = 'numberString';
           var number = document.getElementById("number").value;

         axios({
       method: 'post',
       responseType: 'blob',
       url: 'api/gethlr',
       data: {
         id: id,
       selectInputNum: selectInputNum,
       number: number,
       resultOutput: 'file',
     }
       })
       .then(function (response) {
         document.getElementById("viewResult").hidden = true;
         const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'exportHLR.xlsx'); //or any other extension
    document.body.appendChild(link);
    link.click();
     })
         }

         if (numberFile == true && document.getElementById("rfile").checked) {
           var selectInputNum = 'numberFile';
           var number = document.getElementById("file").value;
           var formData = new FormData();
           var numfile = document.querySelector('#file');
           formData.append("number", numfile.files[0]);
           formData.append("id", id);
           formData.append("selectInputNum", selectInputNum);
           formData.append("resultOutput", 'file');
           axios.post('api/gethlr', formData, {
           responseType: 'blob',
           headers: {
           'Content-Type': 'multipart/form-data'
           }
           })
       .then(function (response) {
         document.getElementById("viewResult").hidden = true;
         const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'exportHLR.xlsx'); //or any other extension
      document.body.appendChild(link);
      link.click();
      })
         }

         if (numberString == true && document.getElementById("rweb").checked) {
           var selectInputNum = 'numberString';
           var number = document.getElementById("number").value;
         axios({
         method: 'post',
         url: 'api/gethlr',
         data: {
         id: id,
         selectInputNum: selectInputNum,
         number: number,
         resultOutput: 'web',
         }
         })
         .then(function (response) {
           console.log(response.data);
           var respdata = response.data;
           rendTable(respdata)
             document.getElementById("viewResult").hidden = false;
         })
         }

 if (numberFile == true && document.getElementById("rweb").checked) {
            var selectInputNum = 'numberFile';
            var number = document.getElementById("file").value;
            var formData = new FormData();
var numfile = document.querySelector('#file');
formData.append("number", numfile.files[0]);
formData.append("id", id);
formData.append("selectInputNum", selectInputNum);
formData.append("resultOutput", 'web');
axios.post('api/gethlr', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
})
          .then(function (response) {
            var respdata = response.data;
            rendTable(respdata)
              document.getElementById("viewResult").hidden = false;
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

      function rendTable(respdata) {
        var tableData = respdata;
        var getTableBody =  data => data.map(row => `<tr>${Object.values(row).map(td => `<td>${td}</td>`).join('')}</tr>`).join('');
        var renderTable = data => {
          document.querySelector('#myTable tbody').innerHTML = getTableBody(data);
        };
        renderTable(tableData);
      }


          function fnExcelReport() {
            TableToExcel.convert(document.getElementById("myTable"), {
    name: "exportHLR.xlsx",
    sheet: {
      name: "Sheet 1"
    }
  });
          }
      </script>

</x-app-layout>
