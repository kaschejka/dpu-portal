<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Осуществляем ностройки') }}
        </h2>
    </x-slot>
    <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
    </script>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">

              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="voicebox-tab" data-toggle="pill" href="#voicebox" role="tab" aria-controls="voicebox" >Резерв номеров</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="gnezdo-tab" data-toggle="pill" href="#Gnezdo" role="tab" aria-controls="Gnezdo" >Предоставление доступа</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="podpisant-tab" data-toggle="pill" href="#podpisant" role="tab" aria-controls="podpisant" >Подписанты</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="region-tab" data-toggle="pill" href="#region" role="tab" aria-controls="region" >Управление регионами ABC/DEF</a>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="voicebox" role="tabpanel" aria-labelledby="voicebox-tab">
                  @include('inc/confReserveManager')

                </div>
                <div class="tab-pane fade" id="Gnezdo" role="tabpanel" aria-labelledby="gnezdo-tab">
                  @include('inc/confUsermanager')
                </div>
                <div class="tab-pane fade" id="podpisant" role="tabpanel" aria-labelledby="podpisant-tab">
                  @include('inc/confPodpisant')
                </div>
                <div class="tab-pane fade" id="region" role="tabpanel" aria-labelledby="region-tab">
                  @include('inc/confregionmanager')
                </div>
              </div>
            </div>
          </div>
        </div>


</x-app-layout>
