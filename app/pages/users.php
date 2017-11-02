<div class="container">
  <h1 class="title display-4">Användare</h1>
  
  <div class="card">
    <div class="card-header">
      Lägg till en ny användare
    </div>
    <div class="card-body">
      <form id="user_form" class="row">
          <div class="col-lg-3">
            <label for="form_name">Riktigt namn:</label>
            <input id="form_name" name="name" type="text" class="form-control" placeholder="Skriv hela namnet">
            
          </div>
          <div class="col-lg-3">
            <label for="form_username">Användarnamn:</label>
            <input id="form_username" name="username" type="text" class="form-control" placeholder="Skriv ett användarnamn">
            
          </div>
          <div class="col-lg-3">
            <label for="form_password">Lösenord:</label>
            <input id="form_password" name="password" type="password" class="form-control" placeholder="Skriv ett lösenord">
          </div>
          <div class="col-lg-2">
            <label for="form_role">Roll:</label>
            <select id="form_role" name="role" class="form-control">
              <option value="" disabled selected>Välj en roll</option>
              <option value="0">Städare</option>
              <option value="1">Administratör</option>
            </select>
          </div>
          <div class="col-lg-1">
            <button id="user_form_submit" type="submit" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="top"><i class="fa fa-user-plus"></i></button>
          </div>
      </form>
      <div id="user_form_alert"></div>
    </div>
  </div>
  <div id="user_table" class="card">
    <div class="card-header">
      Ta bort användare
    </div>
    <div class="card-body">
      <span class="no-list-text">Inga användare förutom du existerar.</span>
      <table class="table table-secondary user-table">
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
      <div id="user_list_alert"></div>
    </div>
  </div>
</div>