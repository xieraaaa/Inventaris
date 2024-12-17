var room = 1;

console.log('Loaded');

function education_fields() {
    if (updateModalLength === updateModalMaxLength) {
        return;
    }
    
    room++;
    var objTo = document.getElementById('education_fields')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "form-group removeclass" + room);
    var rdiv = 'removeclass' + room;
    divtest.innerHTML =
        `<div class="row education_fields" id="education_fields_${updateModalLength}">
            <div class="col-sm-3 nopadding">
                <div class="form-group">
                    <select class="updateModal__selectBarang form-control" name="barang">
                        <option>-- Nama Barang --</option>
                        {{-- Diisi secara dinamis --}}
                    </select>
                </div>
            </div>
            <div class="col-sm-3 nopadding">
                <div class="form-group">
                    <input id="unavailable-amount" type="number" class="form-control" name="number[]" value="1" placeholder="Jumlah" />
                </div>
            </div>
            <div class="col-sm-3 nopadding">
                <div class="form-group">
                    <select  class="form-control" style="cursor: pointer;" placeholder="Keterangan">
                        <option value="">--- KETERANGAN ---</option>
                        <option value="kosong">Barang tidak tersedia</option>
                    </select>
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
    populateUpdateModal(updateModalLength)

    updateModalLength += 1
}

function remove_education_fields(rid) {
    $('#education_fields_' + rid).remove();
    updateModalLength -= 1;
}
