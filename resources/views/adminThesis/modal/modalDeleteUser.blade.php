<form id="deleteUser">
    {{ csrf_field() }} {{ method_field('POST') }}
    <div class="modal fade example-modal modal-3d-sign" id="modalDeleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-center modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" style="color: blue;">Penghapusan Pengguna</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-12">
                        <label class="col-4 control-label">Nama Pengguna : </label>
                        <div class="input-group col-12 user-name-div">
                            
                        </div>
                        <input type="hidden" name="id" id="user-id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" form="deleteUser" class="btn btn-primary">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>