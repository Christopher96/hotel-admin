<div class="container">
    <h1 class="title display-4 text-secondary">
        Nytt rum
    </h1>
    <div class="card">
        <form id="room_form">
            <div class="card-body row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="exampleFormControlInput1"># Nummer</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Skriv ett rumsnummer">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Våning</label>
                        <select class="form-control" id="exampleFormControlSelect1">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Avdelning</label>
                        <select class="form-control" id="exampleFormControlSelect2">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Beskrivning</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        Bild
                        <div class="no-img">
                            <i class="fa fa-image"></i>
                        </div>
                        <img class="room-img" src="" alt="">
                        <input class="btn btn-primary" type="file" value="Välj en bild">
                    </div>
                </div>
            </div>
                <button class="card-footer btn btn-primary" type="submit">Skapa hotellrum</button>
        </form>
    </div>
    
</div>