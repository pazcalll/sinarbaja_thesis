<!-- Modal Confirm Pesanan -->
<div class="modal fade" id="confirmPesanan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pesanan Diterima</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="text-align: center;">
          Apakah anda telah menerima pesanan ?
        </div>
        <div class="modal-footer">
        <form id="formEdit" name="formEdit" class="form-horizontal" novalidate="" method="post" action="#">
            @csrf
            <input type="hidden" name="confirm" id="confirm" value=""/>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
          <button type="button" class="btn btn-primary">Ya</button>
        </form>
        </div>
      </div>
    </div>
  </div>