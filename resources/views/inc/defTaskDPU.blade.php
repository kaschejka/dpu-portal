<div class="form-group">

  <div class="form-group">
  <label>DEF с СМС: </label>
  <input type="radio" class="btn-check" name="sms" id="smsOn" value="ДА">
  <label class="btn btn-outline-success" for="smsOn">Да</label>
  <input type="radio" class="btn-check" name="sms" id="smsOff" value="НЕТ" checked>
  <label class="btn btn-outline-success" for="smsOff">Нет</label>
  </div>
  <button type="button" id="seeAllDEF" name="seeAllDEF" class="btn btn-success">Отобразить все регионы DEF</button>
  <button type="button" id="hiddenAllDEF" name="seeAllDEF" class="btn btn-success">Скрыть все регионы DEF</button>
  <button type="button" id="ckeckAllDEF" name="seeAllDEF" class="btn btn-success">Выбрать все регионы DEF</button>
  <br>
  <br>
  <label>Список регионов DEF</label>
  <br>
  <select name='selectRegionDef' id='selectRegionDEF' class='form-control'>
  <option selected disabled>Выберите город</option>
  <option value="vpd211">Москва (Московская область)</option>
  <?php $rpodp = DB::table('region')->where('def_activate','=', true)->orderBy('ru_name_def', 'asc')->get();

  foreach ($rpodp as $rpodp) {

  echo "<option value='vpd".$rpodp->id."'>".$rpodp->ru_name_def."</option>";
  }
  echo "</select>";
  ?>
<br>
  <fieldset id="fieldsetDef">
    <div hidden id="vpd211">
    <div class="input-group mb-3">
    <div class="input-group-text">
    <input class="form-check-input" type="checkbox" name="regionDef[]" id="d211" value="211" aria-label="Checkbox for following text input">
    <label style="display: block" for="a211">Москва (Московская область)</label>
    </div>
  <input type="text" class="form-control" placeholder=''  name="d211" id="d211" aria-label="Text input with checkbox">
  </div>
  </div>
    <?php
          $reg = DB::table('region')->where('def_activate','=', true)->get();

    foreach ($reg as $reg) {



      echo "<div hidden id='vpd".$reg->id."'>";
                            echo "<div class='input-group mb-3'>";
                            echo "<div class='input-group-text'>";
                            echo "<input class='form-check-input' type='checkbox' name='regionDef[]' id='d".$reg->id."' value='".$reg->id."' aria-label='Checkbox for following text input'>";
                            echo "<label style='display: block' for='d".$reg->id."'>".$reg->ru_name_def."</label>";
                            echo "</div>";
                            echo "<input type='text' class='form-control count-control' name='d".$reg->id."' id='d".$reg->id."'placeholder='' aria-label='Text input with checkbox'>";
                            echo "</div>";
                            echo "</div>";
                                }
    ?>
  </fieldset>
</div>
<script>

document.getElementById('seeAllDEF').addEventListener('click', function() {
 var op = document.getElementById('selectRegionDEF');
 for(let i = 1; i < op.length; i++){
   document.getElementById(op[i].value).hidden = false;
 }
});

document.getElementById('hiddenAllDEF').addEventListener('click', function() {
 var op = document.getElementById('selectRegionDEF');
 for(let i = 1; i < op.length; i++){
   document.getElementById(op[i].value).hidden = true;
 }
 var minputElements = document.getElementById('fieldsetDef').querySelectorAll('input[type="checkbox"]');
 for(let i = 0; i < minputElements.length; i++){
   minputElements[i].checked = false;
 }
});

document.getElementById('ckeckAllDEF').addEventListener('click', function() {
 var op = document.getElementById('selectRegionDEF');
 var minputElements = document.getElementById('fieldsetDef').querySelectorAll('input[type="checkbox"]');
 for(let i = 0; i < minputElements.length; i++){
   minputElements[i].checked = true;
 }
 for(let i = 1; i < op.length; i++){
   document.getElementById(op[i].value).hidden = false;
 }
});


var selectElemDef = document.getElementById('selectRegionDEF');

selectElemDef.addEventListener('change', function() {

  var selindDef = selectElemDef.options.selectedIndex
     var valDef= selectElemDef.options[selindDef].value
     var pElemDef = document.getElementById(valDef)

pElemDef.hidden = false;
var minputElementsDEF = pElemDef.querySelectorAll('input[type="checkbox"]');
for(let i = 0; i < minputElementsDEF.length; i++){
  minputElementsDEF[i].checked = true;
}

})
</script>
