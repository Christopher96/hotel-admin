<div class="container">
    <h1 class="title display-4 text-secondary">
        Nytt rum
    </h1>
    <div class="card">
        <form id="room_form">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label># Nummer</label>
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
                            <label>Beskrivning</label>
                            <textarea name="description" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            Bild
                            <div id="room_img">
                                <i class="fa fa-image"></i>
                                <img  src="" alt="">
                            </div>
                            <input name="image" id="room_img_input" class="btn btn-primary" type="file" value="Välj en bild">
                        </div>
                    </div>
                </div>
                <div id="alert" class="col-12 alert" role="alert">
                    <i class="fa fa-exclamation-circle error"></i>
                    <i class="fa fa-check-circle check"></i>
                    <span class="message"></span>
                </div>
            <button class="btn btn-primary" type="submit">Skapa hotellrum</button>
                
            </div>
            
        </form>
    </div>
    
</div>