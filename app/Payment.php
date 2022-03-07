<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    // use Columns;
    protected $fillable = array('po_id', 'tagihan_id', 'nominal_bayar', 'valid', 'bukti_tf');
    
    public function tagihan() {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function po() {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
}
