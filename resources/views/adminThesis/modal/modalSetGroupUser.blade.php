<form id="setGroupUser">
    {{ csrf_field() }} {{ method_field('POST') }}
    <div class="modal fade example-modal modal-3d-sign" id="modalSetGroupUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-center modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" style="color: blue;">Set Group User</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-12">
                        <label class="col-4 control-label">User Name : </label>
                        <div class="input-group col-12 user-name-div">
                            <h4></h4>
                        </div>
                        <input type="hidden" name="user-id-group-edit" id="user-id-group-edit">
                    </div>
                    <div class="form-group col-12">
                        <label class="col-4 control-label">User Group : </label>
                        <select class="form-control" name="select-group-user" id="select-group-user">
                            <option>Text</option>
                        </select>
                        {{-- <input type="hidden" name="user-group" id="user-group"> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="setGroupUser" class="btn btn-primary">Change Group</button>
                </div>
            </div>
        </div>
    </div>
</form>