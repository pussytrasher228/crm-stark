@extends('test.layouts')

@section('content')
  <section class="content head">
      
      <div class="container">
          <div class="row">
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="name">Номер варианта: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="name">Статус объекта:</label>
                      <input type="text" class="form-control" name="status" id="status" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="category">Регион:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
          </div>

              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="category">Тип сделки:</label>
                          <select name="region" id="region" class="form-control select2">
                              <option  value="">Новосибирская область</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="category">Нас. пункт:</label>
                          <select name="region" id="region" class="form-control select2">
                              <option  value="">Новосибирская область</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="category">Район:</label>
                          <select name="region" id="region" class="form-control select2">
                              <option  value="">Новосибирская область</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="name">Улица: </label>
                          <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="name">Номер дома: </label>
                          <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="name">Номер квартиры: </label>
                          <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="name">Этаж: </label>
                          <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="name">Кол-во балконов: </label>
                          <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                      </div>
                  </div>
              </div>

              <div class="row">

              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Кол-во лоджий: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Этажность: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Материал дома:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Комнат:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
          </div>

          <div class="row">

              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Кол-во лоджий: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Этажность: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Материал дома:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Комнат:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="row">

              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Кол-во лоджий: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Этажность: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Материал дома:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Комнат:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="row">

              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Кол-во лоджий: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="name">Этажность: </label>
                      <input type="text" class="form-control" name="number" id="number" value="{{ old('number', '') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Материал дома:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="category">Комнат:</label>
                      <select name="region" id="region" class="form-control select2">
                          <option  value="">Новосибирская область</option>
                      </select>
                  </div>
              </div>
          </div>

          <div class="form-group">
              <button class="btn btn-success" type="submit">
                  Сохранить
              </button>
              <a href="#" onclick="history.back();" class="btn btn-danger">Отмена</a>
          </div>
          </div>

  </section>
@endsection