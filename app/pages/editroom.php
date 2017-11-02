<?php if(isset($_GET['room_id'])) { ?>
<script>
    room_id = <?= $_GET['room_id'] ?>;
</script>
<?php } ?>
<div class="container">
    <h1 class="title display-4">
        Nytt rum
    </h1>
    <div class="card">
        <form id="room_form">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label>Nummer</label>
                            <input name="number" type="number" class="form-control" placeholder="Skriv ett rumsnummer">
                        </div>
                        <div class="form-group">
                            <label>Våning</label>
                            <select name="level" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Avdelning</label>
                            <select name="department" class="form-control">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="0" selected>Ostädat</option>
                                <option value="1">Städat</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Beskrivning</label>
                            <textarea name="description" class="form-control" rows="5"></textarea>
                        </div>
                    
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Bild</label>
                            <div id="room_img">
                                <i class="fa fa-image"></i>
                                <a class="lightbox-img" href="" data-lightbox="images">
                                    <img src="" alt="">
                                </a>
                            </div>
                            <input name="image" id="room_img_input" class="btn btn-primary" type="file" value="Välj en bild">
                        </div>
                    </div>
                </div>
                <div id="edit_alert"></div>
                <button class="btn btn-primary" type="submit">Skapa hotellrum</button>
            </div>
        </form>
    </div>
    
</div>