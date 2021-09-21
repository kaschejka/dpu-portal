
@if(isset($notsel))
<table  id="errTable1">
<tr> <td><img src="/attention.jpg" alt="Ошибки в номерах!"></td> <td><p>Не выбраны номера!</p></td></tr>
</table>
@endif
@if(isset($rezerv))
<table  id="errTable">
<tr> <td><img src="/attention.jpg" alt="Ошибки в номерах!"></td> <td><p>Некоторые номера не зарезервировались!</p></td></tr>
</table>

<br>
<table class="table" id="myTable" style="display:none">
  <thead class="table-dark">
<tr> <td>Номер</td> <td>Оператор</td><td>Регион</td><td>Конец резерва</td><td>Reserv_uid/Ошибка</td></tr>

</thead>
<tbody>
@foreach ($rezerv as $rezerv)
<tr> <td>{{$rezerv['number']}}</td> <td>{{$rezerv['operator']}}</td><td>{{$rezerv['region']}}</td><td>{{$rezerv['end_date']}}</td><td>{{$rezerv['rez_uid']}}</td></tr>
@endforeach
</tbody>
</table>
<script>
var table= document.getElementById("myTable");
var html = table.outerHTML;
window.open('data:application/vnd.ms-excel,' + '\uFEFF' + encodeURIComponent(html));

</script>
@endif


<form  action="/rezervnum/submit" method="post" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
  <label for="manager">Указать для какого менеджера осуществляется резерв</label>

<?php $rpodp = DB::table('manager')->get();
echo "<input list='character' class='form-control' name='manager' autocomplete='off' required>";
echo "<datalist id='character' name='manager'>";
foreach ($rpodp as $rpodp) {
echo "<option value='".$rpodp->FIO."'</option>";
}
echo "</datalist>";
?>
</div>

  <div class="form-group">
    <label for="rezfile">Выберите файл с номерами</label>
    <input type="file" name="rezfile" id = "rezfile" class="form-control">
 </div>

 <div class="form-group">
   <label for="reznum">Номера через "пробел" </label>
   <input type="text" name="reznum" placeholder="79587001234 78005550345" id="reznum" class="form-control">
 </div>

  <div class="form-group">
    <label for="description">Введите номер задачи</label>
    <input type="text" name="description" placeholder="DPUNUM-XXXXX" id="description" class="form-control" required>
</div>

<div class="form-group">
  <label for="company">Название компании/ЛС для которой осуществляется резерв</label>
  <input type="text" name="company"  id="company" class="form-control">
</div>
 <div class="form-group">
    <label for="description">Выберите дату резервирования номеров</label>
    <input type="date" name="daterezerv" class="form-control" value="<?php echo date("Y-m-d", strtotime("+1 month"));?>">
    </div>
    <div class="form-group">
    <button type="submit" class="btn btn-success">Зарезервировать</button>
  </div>
</form>
