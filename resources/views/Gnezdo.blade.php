<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">

              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="getFollowMe-tab" data-toggle="pill" href="#getFollowMe" role="tab" aria-controls="getFollowMe" >GET FOLLOW ME</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="karusel-tab" data-toggle="pill" href="#karusel" role="tab" aria-controls="karusel" >Маркировка номеров Карусель</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="reserve_uid-tab" data-toggle="pill" href="#reserve_uid" role="tab" aria-controls="reserve_uid" >Reserve UID</a>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="getFollowMe" role="tabpanel" aria-labelledby="getFollowMe-tab">
                  @include('inc/getfollowMeForm')

                </div>
                <div class="tab-pane fade" id="karusel" role="tabpanel" aria-labelledby="karusel-tab">
                    <div class="form-group">
                  @include('inc/markerKarusel')
                  </div>
                </div>
                <div class="tab-pane fade" id="reserve_uid" role="tabpanel" aria-labelledby="reserve_uid-tab">
                    <div class="form-group">
                  @include('inc/reservUID')
                  </div>
                </div>
              </div>
            </div>
            <br>
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
  @if($errors->any())
  <span style="color: red">{{$errors->first()}}</span>
  @endif
</div>

          </div>
        </div>


</x-app-layout>
