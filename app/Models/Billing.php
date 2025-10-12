<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = ['appointment_id', 'amount', 'discount', 'tax', 'payment_method', 'status', 'invoice_no'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function generateInvoiceNo()
    {
        return 'INV-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
