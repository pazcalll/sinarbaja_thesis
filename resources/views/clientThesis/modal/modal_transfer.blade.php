<!-- Modal Upload Transfer -->
<div class="modal fade example-modal-md modal-3d-sign" id="uploadBukti" aria-hidden="true" aria-labelledby="uploadBukti" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" style="color: blue">Upload Bukti Bayar</h4>
            </div>
            <form action="#" method="post" enctype="multipart/form-data" id="formImg">
                @csrf
                <div class="modal-body">
                    <div class="example-wrap">
                        <div id="wrapper-hidden"></div>
                        <br>
                        <h4 class="example-title text-center">Upload File</h4>
                        <div class="example">
                            <input type="file" name="inputBukti" id="inputBukti" data-plugin="dropify" data-default-file="" />
                            {{-- <input type="file" name="inputBukti"> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#">Batal</a></button>
                    <button type="submit" form="formImg" class="btn btn-primary uploadInputBukti"><span style="color: beige;">Upload</span></button>
                </div>
            </form>
        </div>
    </div>
</div>