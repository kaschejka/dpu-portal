@if (auth()->user()->email == 'dkozlov@mtt.ru' or auth()->user()->email == 'YKonoplya@mtt.ru')
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Загрузка в NMS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
              <form  action="/upnms/submit" method="post" enctype="multipart/form-data">
                @csrf
<div>

<label for="fl">Выберите файл с номерами</label>
  <input type="file" class="form-control" name="fl" id="fl">
</div>
<br>
<div class="form-group">
<input type="radio" class="btn-check" name="outrez" id="success-outlined"  value="upl">
<label class="btn btn-outline-success" for="success-outlined">Загрузить номера</label>
<input type="radio" class="btn-check" name="outrez" id="success-outlined1"  value="upd">
<label class="btn btn-outline-success" for="success-outlined1">Выполнить update IMSI</label>
</div>

                  <div class="form-group">
                  <button type="submit" class="btn btn-success">START</button>
                </div>

              </form>
              <button onclick="document.location='storage/upnms.xlsx'" class="btn btn-success">Пример файла на загрузку</button>
              <button onclick="document.location='storage/updimsi.xlsx'" class="btn btn-success">Пример файла на update</button>
            </div>

        </div>
    </div>

</x-app-layout>
@else
<script>window.location = "/dashboard";</script>
@endif
