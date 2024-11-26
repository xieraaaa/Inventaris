var room = 1;

console.log('Loaded');

function education_fields() {
    room++;
    var objTo = document.getElementById('education_fields')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "form-group removeclass" + room);
    var rdiv = 'removeclass' + room;
    divtest.innerHTML =
        `<div class="row">
            <div class="col-sm-3 nopadding">
                <div class="form-group">
                    <select id="updateModal__selectBarang" class="form-control" name="barang">
                        <option>-- Nama Barang --</option>
                        {{-- Diisi secara dinamis --}}
                    </select>
                </div>
            </div>
            <div class="col-sm-3 nopadding">
                <div class="form-group">
                    <input type="number" class="form-control" id="Major" name="number[]" value="" placeholder="Jumlah" />
                </div>
            </div>
            <div class="col-sm-3 nopadding">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Keterangan" />
                </div>
            </div>
            <div class="col-sm-3 nopadding">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-danger text-white" type="button" onclick="remove_education_fields(${room})"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`

    objTo.appendChild(divtest)
}

function remove_education_fields(rid) {
    $('.removeclass' + rid).remove();
}
