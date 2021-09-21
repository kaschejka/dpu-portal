<div class="max-w-7xl mx-auto sm:px-6 lg:px-8" id="rabc">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
        <button type="button" id="seeAllABC" name="seeAllABC" class="btn btn-success">Отобразить все регионы ABC</button>
        <button type="button" id="hiddenAllABC" name="seeAllABC" class="btn btn-success">Скрыть все регионы ABC</button>
        <button type="button" id="ckeckAllABC" name="seeAllABC" class="btn btn-success">Выбрать все регионы ABC</button>
      <div class="form-group">
        <br>
      <label>Список городов ABC</label>
      <select name='selectRegion' id='selectRegion' class='form-control'>
      <option selected disabled>Выберите город</option>
      <option value="vp495">Москва 495 </option>
      <option value="vp499">Москва 499 </option>
      <?php $rpodp = DB::table('region')->where('abc_activate','=', true)->orderBy('ru_name_abc', 'asc')->get();

      foreach ($rpodp as $rpodp) {
      echo "<option value='vp".$rpodp->id."'>".$rpodp->ru_name_abc."</option>";
      }
      echo "</select>";
      ?>
      </div>
      <div>

        <fieldset id="fieldsetAbc">
          <div hidden id="vp495">
          <div class="input-group mb-3">
          <div class="input-group-text">
          <input class="form-check-input" type="checkbox" name="regionAbc[]" id="a495" value="495" aria-label="Checkbox for following text input">
          <label style="display: block" for="a495">Москва 495</label>
          </div>
        <input type="text" class="form-control" placeholder=''  name="a495" id="a495" aria-label="Text input with checkbox">
        </div>
        </div>
        <div hidden id="vp499">
        <div class="input-group mb-3">
        <div class="input-group-text">
        <input class="form-check-input" type="checkbox" name="regionAbc[]" id="a499" value="499" aria-label="Checkbox for following text input">
        <label style="display: block" for="a499">Москва 499</label>
        </div>
      <input type="text" class="form-control" name="a499" id="a499" aria-label="Text input with checkbox">
      </div>
      </div>
          <?php
                $reg = DB::table('region')->where('abc_activate','=', true)->get();

          foreach ($reg as $reg) {

            echo "<div hidden id='vp".$reg->id."'>";
                                  echo "<div class='input-group mb-3'>";
                                  echo "<div class='input-group-text'>";
                                  echo "<input class='form-check-input' type='checkbox' name='regionAbc[]' id='a".$reg->id."' value='".$reg->id."' aria-label='Checkbox for following text input'>";
                                  echo "<label style='display: block' for='a".$reg->id."'>".$reg->ru_name_abc."</label>";
                                  echo "</div>";
                                  echo "<input type='text' class='form-control' name='a".$reg->id."' id='a".$reg->id."'placeholder='' aria-label='Text input with checkbox'>";
                                  echo "</div>";
                                  echo "</div>";
                                      }
          ?>
        </fieldset>
      </div>
    </div>
    </div>


    <script>

document.getElementById('seeAllABC').addEventListener('click', function() {
 var op = document.getElementById('selectRegion');
 for(let i = 1; i < op.length; i++){
   document.getElementById(op[i].value).hidden = false;
 }
});

document.getElementById('hiddenAllABC').addEventListener('click', function() {
 var op = document.getElementById('selectRegion');
 for(let i = 1; i < op.length; i++){
   document.getElementById(op[i].value).hidden = true;
 }
 var minputElements = document.getElementById('fieldsetAbc').querySelectorAll('input[type="checkbox"]');
 for(let i = 0; i < minputElements.length; i++){
   minputElements[i].checked = false;
 }
});

document.getElementById('ckeckAllABC').addEventListener('click', function() {
 var op = document.getElementById('selectRegion');
 var minputElements = document.getElementById('fieldsetAbc').querySelectorAll('input[type="checkbox"]');
 for(let i = 0; i < minputElements.length; i++){
   minputElements[i].checked = true;
 }
 for(let i = 1; i < op.length; i++){
   document.getElementById(op[i].value).hidden = false;
 }
});
    var selectElem = document.getElementById('selectRegion')

    // Когда выбран новый элемент <option>
    selectElem.addEventListener('change', function() {

      var selind = selectElem.options.selectedIndex
         var val= selectElem.options[selind].value
         var pElem = document.getElementById(val)

    pElem.hidden = false;
    var minputElements = pElem.querySelectorAll('input[type="checkbox"]');
    for(let i = 0; i < minputElements.length; i++){
      minputElements[i].checked = true;
    }



  })
    </script>
