<div class="form-group">
  <label>Список регионов DEF</label>
  <br>
  <span>Можно указать несколько префиксов в регионе. Формат префикс%-количество(7958%-2) следующий префикс указать через пробел </span>
<br>
<br>
  <select name='selectRegionDef' id='selectRegionDEF' class='form-control'>
  <option selected disabled>Выберите город</option>
  <option value="vpd211">Москва (Московская область)</option>
  <?php $rpodp = DB::table('region')->where('def_activate','=', true)->orderBy('ru_name_def', 'asc')->get();

  foreach ($rpodp as $rpodp) {
    $col = DB::table('ostatok_num')
                ->where([['id_region','=', $rpodp->id],['project','=','Gnezdo']])
                ->first();
    if (isset($col)) {
      $col = $col->col_def;
    } else {
      $col = 0;
    }
  echo "<option value='vpd".$rpodp->id."'>".$rpodp->ru_name_def." - ".$col."</option>";
  }
  echo "</select>";
  ?>
<br>
  <fieldset>
    <div hidden id="vpd211">
    <div class="input-group mb-3">
    <div class="input-group-text">
    <input class="form-check-input def-sel" type="checkbox" name="region[]" id="d211" value="211" aria-label="Checkbox for following text input">
    <label style="display: block" for="a211">Москва (Московская область)</label>
    </div>
  <input type="text" class="form-control count-control" placeholder=''  name="d211" id="d211" aria-label="Text input with checkbox">
  </div>
  </div>
    <?php
          $reg = DB::table('region')->where('def_activate','=', true)->get();

    foreach ($reg as $reg) {

      $col = DB::table('ostatok_num')
                  ->where([['id_region','=', $reg->id],['project','=','Gnezdo']])
                  ->first();
      if (isset($col)) {
        $col = $col->col_def;
      } else {
        $col = 0;
      }

      echo "<div hidden id='vpd".$reg->id."'>";
                            echo "<div class='input-group mb-3'>";
                            echo "<div class='input-group-text'>";
                            echo "<input class='form-check-input def-sel' type='checkbox' name='region[]' id='d".$reg->id."' value='".$reg->id."' aria-label='Checkbox for following text input'>";
                            echo "<label style='display: block' for='d".$reg->id."'>".$reg->ru_name_def."</label>";
                            echo "</div>";
                            echo "<input type='text' class='form-control count-control' name='d".$reg->id."' id='d".$reg->id."'placeholder='Остаток ".$col."' aria-label='Text input with checkbox'>";
                            echo "</div>";
                            echo "</div>";
                                }
    ?>
  </fieldset>
</div>
