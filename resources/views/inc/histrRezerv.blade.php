<form  action="/historyrezerv" method="get" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
  <label for="hmanager">Выбрать менеджера</label>

<?php $rpodp = DB::table('manager')->get();
echo "<input list='character1' class='form-control' name='hmanager' autocomplete='off'>";
echo "<datalist id='character1' name='hmanager'>";
foreach ($rpodp as $rpodp) {
echo "<option value='".$rpodp->FIO."'</option>";
}
echo "</datalist>";
?>
</div>

 <div class="form-group">
   <label for="hreznum">Номера через ; без пробелов</label>
   <input type="text" name="hreznum" placeholder="79587001234;78005550345" id="hreznum" class="form-control">
 </div>

  <div class="form-group">
    <label for="hdescription">Введите номер задач через ;</label>
    <input type="text" name="hdescription" placeholder="DPUNUM-XXXXX;DPUNUM-123456" id="hdescription" class="form-control">
</div>

    <div class="form-group">
    <button type="submit" class="btn btn-success">Получить данные по истории</button>
  </div>
</form>
