<form id="truncateStock">
    {{ csrf_field() }} {{ method_field('POST') }}
    <div class="modal fade example-modal modal-3d-sign" id="modalTruncateStock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-center modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" style="color: blue;">truncate Stock</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-12">
                        Are you sure to delete all records in the stock table?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="truncateStock" class="btn btn-primary">Yes</button>
                </div>
            </div>
        </div>
    </div>
</form>