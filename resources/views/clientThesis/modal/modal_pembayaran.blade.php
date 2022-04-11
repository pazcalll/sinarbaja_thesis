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
                {{-- <div class="example-wrap"> --}}
                    <h4 class="example-title text-center">Form Pembayaran</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="form-list-pickup" enctype="multipart/form-data" action="#" method="post">
                                @csrf
                                <input type="hidden" name="pembayaran">
                                <table class="table table-bordered table-hover table-striped" id="tb_pembayaran">
                                    <thead id="thead">
                                        <tr style="text-align: center;">
                                            <th>No.</th>
                                            <th width="20%">Jumlah Bayar</th>
                                            <th>Metode Pembayaran</th>
                                            <th>Upload Bukti Pembayaran</th>
                                        </tr>
                                    </thead>
                                </table>
                            </form>
                            <form action="#" id="form-bayar" enctype="multipart/form-data" method="post">
                                @csrf
                                <span class=" form_pembayaran">
                                    <div class="form-group">
                                        <label for="accumulation">Belum Dibayar (Rp/IDR) </label>
                                        <p id="accumulation" style="color: red;">0</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="noTagihanText">No. Tagihan</label>
                                        {{-- <input type="text" class="form-control" id="noTagihan"> --}}
                                        <p id="noTagihanText"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="metodeBayar">Metode Pembayaran</label>
                                        <select class="form-control" name="metodeBayar" id="metodeBayar">
                                            <option value="1">OVO</option>
                                            <option value="2">GoPay</option>
                                            <option value="3">BRI</option>
                                            <option value="4">BCA</option>
                                            <option value="5">BNI</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlahBayarInput">Jumlah Bayar</label>
                                        <div class="divJumlahBayar">
                                            <input type="number" name="jumlahBayarInput" value="000" class="form-control" id="jumlahBayarInput">
                                        </div>
                                        <div class="inpHidden">

                                        </div>
                                    </div>
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
                {{-- </div> --}}
            </div>
            {{-- <div class="modal-footer"> --}}
                {{-- <button type="submit" class="btn btn-warning">Bayar</button> --}}
                {{-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalTagihan" id="back2">Back</button> --}}
                {{-- <button type="button" class="btn btn-secondary" data-toggle="modal" id="back2">Back</button> --}}
            {{-- </div> --}}
        </div>
    </div>
</div>