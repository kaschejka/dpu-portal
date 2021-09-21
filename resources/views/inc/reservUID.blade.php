<form  action="/getFollowMe/reservUID" method="post" enctype="multipart/form-data">
  @csrf
<label >Вставьте номера через "пробел" или выберите файл</label>
<div class="input-group">
<div class="input-group-text">
<input class="form-check-input" type="radio" name="rs1"  id="num1" value="num1">
<label for="num1">Номер(а)</label>
</div>
<input type="text" class="form-control" name="number1" id = "number1">
<div class="input-group-text">
<input class="form-check-input" type="radio" name="rs1"  id="sfile1" value="fnum1">
<label for="sfile1">Файл с номерами</label>
</div>
<input type="file" class="form-control" name="fl1" id="fl1">
</div>
<br>
    <div class="form-group" style="display:flex;justify-content:center;align-items:center;">

    <button type="submit" class="btn btn-success">Определить</button>
  </div>
</form>
