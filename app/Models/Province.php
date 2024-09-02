<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'name'
    ];

    // Tên bảng tương ứng trong mysql
    protected $table = 'provinces';
    // Thông báo khóa chính là cột 'code'
    protected $primaryKey = 'code';
    // khóa chính không tự động tăng
    public $incrementing = false;

    public function districts()
    {
        // quan hệ 1 --- n: (class thiết lập mối quan hệ, khóa ngoại trong bảng districts, khóa chính trong bảng provinces)
        return $this->hasMany(District::class, 'province_code', 'code');
    }
}
