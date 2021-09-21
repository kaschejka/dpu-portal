
<span style="color: red">Login и Password указываются от 126 env Porta.</span>
<br>
<form  action="/getFollowMe/markerKarusel" method="post" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
    <label for="Login2">Login</label>
    <input type="text" name="login2"  id="login2" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="pass2">Password</label>
    <input type="password" name="pass2"  id="pass2" class="form-control" required>
  </div>
<label >Вставьте номера через "пробел" или выберите файл</label>
@if(isset($err))
<br>
<span style="color: red">{{$err}}</span>
@endif
<div class="input-group">
<div class="input-group-text">
<input class="form-check-input" type="radio" name="rs2"  id="num2" value="num2">
<label for="num2">Номер(а)</label>
</div>
<input type="text" class="form-control" name="number2" id = "number2">
<div class="input-group-text">
<input class="form-check-input" type="radio" name="rs2"  id="sfile2" value="fnum2">
<label for="sfile2">Файл с номерами</label>
</div>
<input type="file" class="form-control" name="fl2" id="fl2">
</div>
<br>
    <div class="form-group" style="display:flex;justify-content:center;align-items:center;">

      <button type="submit" name="item_id" value="yes" class="btn btn-success">Промарикровать</button>
      <span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
      <button type="submit" name="item_id" value="no" class="btn btn-success">Снять маркер</button>
  </div>
</form>
