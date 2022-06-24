<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class Tagihan
{
    protected $id;
    protected $po_id;
    protected $nominal_total;
    protected $status;
    protected $kirim;
    
    public function __construct($id, $po_id, $nominal_total, $status, $kirim)
    {
        $this->id = $id;
        $this->po_id = $po_id;
        $this->nominal_total = $nominal_total;
        $this->status = $status;
        $this->kirim = $kirim;
    }
    
    public function getThis()
    {
        $getThis = new stdClass();
        $getThis->id = $this->id;
        $getThis->po_id = $this->po_id;
        $getThis->nominal_total = $this->nominal_total;
        $getThis->status = $this->status;
        $getThis->kirim = $this->kirim;
        return $getThis;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
        return $this->status;
    }

    public function setKirim($kirim)
    {
        $this->kirim = $kirim;
        return $this->kirim;
    }
}
