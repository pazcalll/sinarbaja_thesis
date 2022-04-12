<!-- Modal Pembayaran -->
<div class="modal fade example-modal-lg modal-3d-sign"  data-toggle="modal" id="modalPembayaran" aria-hidden="true" aria-labelledby="modalPembayaran" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg" style="max-width: 500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{-- <h4 class="modal-title" id="titleMPembayaran" style="color: blue;">
                    Pembayaran
                    <br>
                </h4> --}}
            </div>
            <div class="modal-body">
                <h4 class="example-title text-center">Form Pembayaran</h4>
                <div class="example">
                    <div class="table-responsive">
                        <form action="#" id="form-bayar" enctype="multipart/form-data" method="post">
                            @csrf
                            <span class=" form_pembayaran">
                                <div class="form-group">
                                    <label for="accumulation">Belum Dibayar (Rp/IDR) </label>
                                    <p id="accumulation" style="color: red;">0</p>
                                </div>
                                <div class="form-group">
                                    <label for="noNotaText">No. Nota</label>
                                    <p id="noNotaText"></p>
                                </div>
                                <input type="hidden" name="jumlahBayarInput" value="0" class="form-control" id="jumlahBayarInput">
                                <div class="inpHidden">

                                </div>
                                {{-- <div class="form-group">
                                    <label for="jumlahBayarInput">Jumlah Bayar</label>
                                    <input type="number" name="jumlahBayarInput" value="0" class="form-control" id="jumlahBayarInput">
                                    <p id="moneyFormat">0</p>
                                            
                                    <div class="inpHidden">

                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <label>Bukti Transfer</label>
                                    <div class="inpBukti">
                                        <input type="file" name="inputBukti" id="inputBukti" data-plugin="dropify" data-default-file="" />
                                    </div>
                                </div>
                                <button form="form-bayar" type="submit" id="submitFormBayar" class="btn btn-primary">Submit</button>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>