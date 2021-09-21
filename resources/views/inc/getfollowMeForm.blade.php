
<span style="color: red">Нагрузка за раз 100 номеров! Время обработки ~ 30 номеров в минуту. Необходимо указывать Login и Password от агента.</span>
<br>
<form  action="/getFollowMe/submit" method="post" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
    <label for="Login">Login</label>
    <input type="text" name="login"  id="login" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="pass">Password</label>
    <input type="password" name="pass"  id="pass" class="form-control" required>
  </div>
<label >Вставьте номера через "пробел" или выберите файл</label>
@if(isset($err))
<br>
<span style="color: red">{{$err}}</span>
@endif
<div class="input-group">
<div class="input-group-text">
<input class="form-check-input" type="radio" name="rs"  id="num" value="num">
<label for="num">Номер(а)</label>
</div>
<input type="text" class="form-control" name="number" id = "number">
<div class="input-group-text">
<input class="form-check-input" type="radio" name="rs"  id="sfile" value="fnum">
<label for="sfile">Файл с номерами</label>
</div>
<input type="file" class="form-control" name="fl" id="fl">
</div>
<br>
    <div class="form-group" style="display:flex;justify-content:center;align-items:center;">

    <button type="submit" class="btn btn-success">Определить</button>
  </div>
</form>
@if($errors->any())
<span style="color: red">{{$errors->first()}}</span>
@endif
