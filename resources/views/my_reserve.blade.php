<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Мои резервы прошедшие через заявки на DPUNUMBERS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">

              <table class="table" id="FTable" >
                <thead class="table-dark">

              @if (auth()->user()->role == 'manager')
              <?php $rpodp = DB::table('napominalka')->where('mail','=', auth()->user()->email)->get();
            echo "  <tr> <td>Номер задачи</td> <td>Конец резерва</td><td>Компания</td><td></td></tr>";
            echo "  </thead>";
            echo "  <tbody>";
                   foreach ($rpodp as $rpodp) {
                   echo "<tr>";
                   echo "<td><form action='my_reserved' method='get'><input type='hidden' id='descr' name='description'  value='".$rpodp->description."'><input type='hidden' id='enddate' name='enddate'  value='".$rpodp->end_date."'><button type='submit' class='btn btn-success'>".$rpodp->description."</button></form></td>";
                   echo "<td>".$rpodp->end_date."</td>";
                   echo "<td>".$rpodp->company."</td>";
                   echo "<td><form action='editcompany' method='get'><input type='hidden' id='descr' name='description'  value='".$rpodp->description."'><input type='hidden' id='comp' name='company'  value='".$rpodp->company."'><button type='submit' class='btn btn-success'>Редактировать</button></form></td>";
                   echo "</tr>";
                   }

                    ?>
              @endif

              @if (auth()->user()->role == 'admin')
              <?php $rpodp = DB::table('napominalka')->get();
              echo "  <tr> <td>Номер задачи</td> <td>Конец резерва</td><td>Менеджер</td><td></td></tr>";
              echo "  </thead>";
              echo "  <tbody>";
                   foreach ($rpodp as $rpodp) {
                   echo "<tr>";
                   echo "<td><form action='my_reserved' method='get'><input type='hidden' id='descr' name='description'  value='".$rpodp->description."'><input type='hidden' id='enddate' name='enddate'  value='".$rpodp->end_date."'><button type='submit' class='btn btn-success'>".$rpodp->description."</button></form></td>";
                   echo "<td>".$rpodp->end_date."</td>";
                   echo "<td>".DB::table('manager')->where('mail','=', $rpodp->mail)->value('FIO')."</td>";
                   echo "</tr>";
                   }

                    ?>
              @endif

            </tbody>
            </table>


            </div>
          </div>
        </div>

</x-app-layout>
