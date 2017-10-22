<div class="container jumbotron">
  <h1 class="title display-4">Användare</h1>
  <div class="card">
    <div class="card-header">
      Lägg till en ny användare
    </div>
    <div class="card-body">
      <form class="form-inline">
        <div class="form-group col-3">
          <input type="text" class="form-control" placeholder="Skriv hela namnet">
        </div>
        <div class="form-group col-3">
          <input type="text" class="form-control" placeholder="Skriv ett användarnamn">
        </div>
        <div class="form-group col-3">
          <select class="form-control" id="exampleFormControlSelect1">
            <option value="" disabled selected>Välj en roll</option>
            <option>Städare</option>
            <option>Administratör</option>
          </select>
        </div>
        <div class="form-group col-3">
          <button type="submit" class="btn btn-primary"><i class="fa fa-user-plus"></i> Skapa Användare</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      Ta bort användare
    </div>
    <table id="user_table" class="table table-secondary">
      <thead>
        <tr>
          <th>Namn</th>
          <th>Användarnamn</th>
          <th>Roll</th>
          <th>Registrerades</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <div class="row">
    
  </div>
</div>
<script>

window.onload = function(){
    getUserList();
}

</script>