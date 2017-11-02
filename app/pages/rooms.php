<div class="container">
    <h1 class="title display-4">
        <span>Rum</span>
        <?php if($priv) { ?>
            <a href="?editroom" class="btn btn-primary float-right new-room"><i class="fa fa-plus"></i><span> Skapa nytt hotellrum</span></a>
        <?php } ?>
    </h1>
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
                    <th><span>Ändra</span></th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="unclean_alert"></div>
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
                    <th><span>Ändra</span></th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="clean_alert"></div>
        </div>
    </div>
</div>