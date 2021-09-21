<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Работа с БЗ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
              <form  action="/bz/submit" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="rezfile">Выберите файл с БЗ</label>
                  <input type="file" name="filenum[]" id = "filenum" class="form-control" multiple>
               </div>

               <div class="custom-control custom-radio custom-control-inline">
                 <input type="radio" id="rweb" name="customRadioInline1" class="custom-control-input" value="rweb">
                 <label class="custom-control-label" for="rweb" >Отобразить на экране</label>
               </div>
               <div class="custom-control custom-radio custom-control-inline">
                 <input type="radio" id="rfile" name="customRadioInline1" class="custom-control-input"  value="rfile" checked>
                 <label class="custom-control-label" for="rfile" >Сохранить в файл</label>
               </div>
               <div class="custom-control custom-radio custom-control-inline">
                 <input type="radio" id="rord" name="customRadioInline1" class="custom-control-input"  value="rord">
                 <label class="custom-control-label" for="rord" >Создать ОРД</label>
               </div>
               <div>
                 <br>
               </div>
                  <div class="form-group">
                  <button type="submit" class="btn btn-success">Распарсить</button>
                </div>
              </form>


            </div>
        </div>
    </div>
</x-app-layout>
