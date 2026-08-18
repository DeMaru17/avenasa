<?php

namespace App\Models;

use Database\Factories\QuotationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    /** @use HasFactory<QuotationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'email',
        'phone',
        'company',
        'subject',
        'message',
        'status',
        'locale',
        'admin_notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
        ];
    }

    /**
     * Get the product that the quotation request is for.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Scope a query to only include new quotation requests.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'New');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
