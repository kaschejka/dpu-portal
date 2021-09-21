<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Формирование договорных документов') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- <span style="color: red">БУДЬТЕ ВНИМАТЕЛЬНЫ! СЕЙЧАС ЗДЕСЬ ИСПОЛЬЗУЕТСЯ СТАРАЯ ФОРМА ДОГОВОРА. ОБНОВЛЕНИЕ ДОГОВОРА В БЛИЖАЙШЕЕ ВРЕМЯ</span> -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">

              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="voicebox-tab" data-toggle="pill" href="#voicebox" role="tab" aria-controls="voicebox" >Voice BOX</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="gnezdo-tab" data-toggle="pill" href="#Gnezdo" role="tab" aria-controls="Gnezdo" >Gnezdo</a>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="voicebox" role="tabpanel" aria-labelledby="voicebox-tab">

<!-- Вложенные кнопки -->
                  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="vip-tab" data-toggle="pill" href="#vip" role="tab" aria-controls="vip" >ИП</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="vul-tab" data-toggle="pill" href="#vul" role="tab" aria-controls="vul" >ЮЛ</a>
                    </li>
                  </ul>

                  <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="vip" role="tabpanel" aria-labelledby="vip-tab">
                      <!-- Форма VoiceBOX ИП -->
                      @include('inc\vbipform')
                            </div>
                    <div class="tab-pane fade" id="vul" role="tabpanel" aria-labelledby="vul-tab">
                      @include('inc\vbulform')
                    </div>
                  </div>


                </div>
                <div class="tab-pane fade" id="Gnezdo" role="tabpanel" aria-labelledby="gnezdo-tab">Этот раздел в стадии разработки!
                </div>
              </div>
            </div>
          </div>
        </div>
</x-app-layout>
