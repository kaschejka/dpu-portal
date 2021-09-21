<form  action="/generatedoc/submitvbul" method="post" enctype="multipart/form-data">
  @csrf
  <div>
    <label for="tarif">Выберите тариф</label>
    <select name="tarif[]" class='form-control' multiple>
    <option value=shablon>Шаблонный разговор</option>
    <option value=personal>Персональное уведомление</option>
    <option value=interact>Интерактивный разговор</option>
    <option value=kdu>KDU-800</option>
    </select>
  </div>

<div class="form-group">
<label for="podpisantmtt">Подписант со стороны МТТ</label>
<?php $rpodp = DB::table('podpisantmtt')->get();
echo "<select name='podpisantmtt' class='form-control'>";
foreach ($rpodp as $rpodp) {
echo "<option value='".$rpodp->id."'>".$rpodp->FIO."</option>";
}
echo "</select>";
?>
</div>

<div class="form-group">
<label for="ndog">Номер договора</label>
<input type="text" class="form-control" name="ndog" placeholder="" id="ndog" required>
</div>

<div class="form-group">
<label for="datedog">Дата договора</label>
<input type="date" name="datedog" id="datedog" class="form-control" value="<?php echo date("Y-m-d", strtotime("Now"));?>">
</div>

<div class="form-group">
<label >Срок действия договора: </label>
<div class="custom-control custom-radio custom-control-inline">
  <input type="radio" id="ulnotEndDate" name="ulsdg" class="custom-control-input" value="ulnotEndDate" checked>
  <label class="custom-control-label" for="ulnotEndDate" >Бессрочный</label>
</div>
<div class="custom-control custom-radio custom-control-inline">
  <input type="radio" id="ulendDate" name="ulsdg" class="custom-control-input"  value="ulendDate">
  <label class="custom-control-label" for="ulendDate" >Срочный</label>
</div>
<input type="hidden" name="ulsrokdog" id="ulsrokdog" class="form-control" value="<?php echo date("Y-m-d", strtotime("+1 year"));?>">
</div>

<script>

var rbend = document.getElementById("ulendDate");
rbend.addEventListener('change', function() {
  document.getElementById("ulsrokdog").type = "date";
})

document.getElementById("ulnotEndDate").addEventListener('change', function() {
  document.getElementById("ulsrokdog").type = "hidden";
})
</script>

<div class="form-group">
<label for="nforg">Наименование организации полное</label>
<input type="text" name="nforg" placeholder="" id="nforg" class="form-control">
</div>

<div class="form-group">
<label for="nsorg">Наименование организации сокращенное</label>
<input type="text" name="nsorg" placeholder="" id="nsorg" class="form-control">
</div>

<div class="form-group">
<label for="ffiok">Должность и ФИО (полностью) подписанта клиента (в родительном падеже)</label>
<input type="text" name="ffiok" placeholder="" id="ffiok" class="form-control">
</div>

<div class="form-group">
<label for="dolp">Должность подписанта клиента</label>
<input type="text" name="dolp" placeholder="" id="dolp" class="form-control">
</div>

<div class="form-group">
<label for="sfiok">Сокращенное фИО подписанта со стороны клиента</label>
<input type="text" name="sfiok" placeholder="И.И. Иванов" id="sfiok" class="form-control">
</div>

<div class="form-group">
<label for="opk">Основание подписи подписанта со стороны клиента (в родительном падеже)</label>
<input type="text" name="opk" placeholder="" id="opk" class="form-control">
</div>

<div class="form-group">
<label for="mzh">Место нахождения клиента (юр адрес)</label>
<input type="text" name="mzh" placeholder="" id="mzh" class="form-control">
</div>

<div class="form-group">
<label for="padr">Почтовый адрес</label>
<input type="text" name="padr" placeholder="" id="padr" class="form-control">
</div>

<div class="form-group">
<label for="ogrnip">ОГРН клиента</label>
<input type="text" name="ogrnip" placeholder="" id="ogrnip" class="form-control">
</div>

<div class="form-group">
<label for="inn">ИНН клиента</label>
<input type="text" name="inn" placeholder="" id="inn" class="form-control">
</div>

<div class="form-group">
<label for="kpp">КПП клиента</label>
<input type="text" name="kpp" placeholder="" id="kpp" class="form-control">
</div>

<div class="form-group">
<label for="rs">№ р/с клиента</label>
<input type="text" name="rs" placeholder="" id="rs" class="form-control">
</div>

<div class="form-group">
<label for="ks">№ к/с клиента</label>
<input type="text" name="ks" placeholder="" id="ks" class="form-control">
</div>

<div class="form-group">
<label for="nb">Наименование банка клиента</label>
<input type="text" name="nb" placeholder="" id="nb" class="form-control">
</div>

<div class="form-group">
<label for="bb">БИК банка клиента</label>
<input type="text" name="bb" placeholder="" id="bb" class="form-control">
</div>

<div class="form-group">
<label for="emlteh">E-mail клиента </label>
<input type="text" name="emlteh" placeholder="" id="emlteh" class="form-control">
</div>

<div class="form-group">
<label for="teltech">Телефон клиента </label>
<input type="text" name="teltech" placeholder="" id="teltech" class="form-control">
</div>

<!-- <div class="form-group">
<label for="emsop">Е-mail клиента (Контакты по вопросам сопровождения Договора, тарифам)</label>
<input type="text" name="emsop" placeholder="" id="emsop" class="form-control">
</div>

<div class="form-group">
<label for="telsop">Телефон клиента (Контакты по вопросам сопровождения Договора, тарифам)</label>
<input type="text" name="telsop" placeholder="" id="telsop" class="form-control">
</div>

<div class="form-group">
<label for="embuh">Е-mail клиента (Адреса и контакты по вопросам предоставления счетов, актов и счетов-фактур по Договору)</label>
<input type="text" name="embuh" placeholder="" id="embuh" class="form-control">
</div>

<div class="form-group">
<label for="emmop">Е-mail мекеджера, работающиего по договору (отдел продаж)</label>
<input type="text" name="emmop" placeholder="" id="emmop" class="form-control">
</div>

<div class="form-group">
<label for="telmop">Телефон мекеджера, работающиего по договору (отдел продаж)</label>
<input type="text" name="telmop" placeholder="" id="telmop" class="form-control">
</div> -->

<div class="form-group">
<label for="rkl">Размере КЛ</label>
<input type="text" name="rkl" placeholder="" id="rkl" class="form-control">
</div>

<div class="form-group">
<label >Тариф: </label>
<div class="custom-control custom-radio custom-control-inline">
  <input type="radio" id="umin" name="utrp" class="custom-control-input" value="min" checked>
  <label class="custom-control-label" for="umin" >Поминутный</label>
</div>
<div class="custom-control custom-radio custom-control-inline">
  <input type="radio" id="usec" name="utrp" class="custom-control-input"  value="sec">
  <label class="custom-control-label" for="usec" >Посекундный</label>
</div>
</div>
<script>

document.getElementById("umin").addEventListener('change', function() {
  document.getElementById("urmgp").value = "0";
  document.getElementById("rmgp").value = "0";
})

document.getElementById("usec").addEventListener('change', function() {
  document.getElementById("urmgp").value = "10000";
})
</script>

<div class="form-group">
<label for="urmgp">Размере МГП</label>
<input type="text" name="urmgp" placeholder="" id="urmgp" class="form-control" value="10000">
</div>
<div class="input-group-text" style="margin-right:13px" >
  <input class="form-check-input" type="checkbox" name="sale_ul" id="sale_ul" aria-label="Checkbox for following text input">
  <label for="sale_ul">Акция: Возвратный инсталл</label>
  </div>
  <br>
<button type="submit" class="btn btn-success">Сформировать</button>

</form>

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
