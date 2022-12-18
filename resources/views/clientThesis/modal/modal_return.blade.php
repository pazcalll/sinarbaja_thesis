<!-- Modal Return -->
<div class="modal fade example-modal-lg modal-3d-sign"  data-toggle="modal" id="modalReturn" aria-hidden="true" aria-labelledby="modalReturn" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleMPembayaran" style="color: blue;">Return</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Return Produk</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="rereturn" method="post" action="">
                                @csrf
                                <input type="hidden" name="return">

                                <table class="table table-bordered table-hover table-striped" id="tb_return">
                                    <thead id="thead">
                                        <tr style="text-align: center;">
                                            <th>No.</th>
                                            <th>No.Tagihan</th>
                                            <th>Tanggal Return</th>
                                            <th>Qty Diterima</th>
                                            <th>Alasan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning" id="returns">Submit</button>
                <button type="button" from="rereturn" class="btn btn-secondary" data-toggle="modal" id="back1" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>