<div class="container">
    <h1 class="title display-4">
        Rum
        <a href="?editroom" class="btn btn-primary float-right"><i class="fa fa-plus"></i><span> Skapa nytt hotellrum</span></a>
    </h1>
    <div id="alert" class="col-12 alert" role="alert">
        <i class="fa fa-exclamation-circle error"></i>
        <i class="fa fa-check-circle check"></i>
        <span class="message"></span>
    </div>
    <div id="uncleaned_rooms" class="card">
        <div class="card-header">
            Ej städade rum
        </div>
        <div class="card-body">
        <span class="no-list-text">Alla rum är städade!</span>
        <table class="table table-secondary room-table">
            <thead>
                <th>Bild</th>
                <th>Rumskod</th>
                <th>Beskrivning</th>
                <th>Ändra</th>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>
        
    </div>

    <div id="cleaned_rooms" class="card">
        <div class="card-header">
            Städade rum
        </div>
        <div class="card-body">
        <span class="no-list-text">Inga rum har städats.</span>
        <table class="table table-secondary room-table">
            <thead>
                <th>Bild</th>
                <th>Rumskod</th>
                <th>Beskrivning</th>
                <th>Ändra</th>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>
    </div>
</div>