<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    
    protected $table = 'brands';
    protected $fillable = ['logo_brand', 'link_brand', 'status_brand'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Accessor untuk status brand
    public function getStatusLabelAttribute()
    {
        return $this->status_brand === 'active' ? 'Aktif' : 'Tidak Aktif';
    }
    
    public function getStatusBadgeAttribute()
    {
        $badgeClass = $this->status_brand === 'active' ? 'badge-success' : 'badge-danger';
        return "<span class='badge {$badgeClass}'>{$this->status_label}</span>";
    }
    
    // Scope untuk brand aktif
    public function scopeActive($query)
    {
        return $query->where('status_brand', 'active');
    }
    
    // Scope untuk brand tidak aktif
    public function scopeInactive($query)
    {
        return $query->where('status_brand', 'inactive');
    }
}
