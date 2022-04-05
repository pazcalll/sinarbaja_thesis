<form id="uploadExcel">
    {{ csrf_field() }} {{ method_field('POST') }}
    <div class="modal fade example-modal modal-3d-sign" id="modalUploadExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-center modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" style="color: blue;">Upload Excel</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-12">
                        <label class="col-4 control-label">Insert Excel File</label>
                        <div class="input-group col-12">
                            <input type="file" class="form-control" required name="file_excel" id="file_excel">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="uploadExcel" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>