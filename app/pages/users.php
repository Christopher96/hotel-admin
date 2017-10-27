<div class="container">
  <h1 class="title display-4">Användare</h1>
  <div id="alert" class="alert" role="alert">
    <i class="fa fa-exclamation-circle error"></i>
    <i class="fa fa-check-circle check"></i>
    <span class="message"></span>
  </div>
  <div class="card">
    <div class="card-header">
      Lägg till en ny användare
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-3">
          <label for="form_name">Riktigt namn:</label>
        </div>
        <div class="col-3">
          <label for="form_name">Användarnamn:</label>
        </div>
        <div class="col-3">
          <label for="form_name">Lösenord:</label>
        </div>
        <div class="col-2">
          <label for="form_name">Roll:</label>
        </div>
        <div class="col-1">
        </div>
      </div>
      <form id="user_form" class="row form-inline">
        <div class="form-group col-3">
          <input id="form_name" name="name" type="text" class="form-control" placeholder="Skriv hela namnet">
        </div>
        <div class="form-group col-3">
          <input id="form_username" name="username" type="text" class="form-control" placeholder="Skriv ett användarnamn">
        </div>
        <div class="form-group col-3">
          <input id="form_password" name="password" type="password" class="form-control" placeholder="Skriv ett lösenord">
        </div>
        <div class="form-group col-2">
          <select id="form_role" name="role" class="form-control">
            <option value="" disabled selected>Välj en roll</option>
            <option value="0">Städare</option>
            <option value="1">Administratör</option>
          </select>
        </div>
        <div class="form-group col-1">
          <button id="user_form_submit" type="submit" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="top"><i class="fa fa-user-plus"></i></button>
        </div>
      </form>
    </div>
  </div>
  <div id="user_table" class="card">
    <div class="card-header">
      Ta bort användare
    </div>
    <div class="card-body">
      <span class="no-list-text">Inga användare förutom du existerar.</span>
      <table class="table table-secondary">
        <thead>
          <tr>
            <th>Namn</th>
            <th>Användarnamn</th>
            <th>Roll</th>
            <th>Registrerades</th>
            <th><span>Ta bort</span></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>