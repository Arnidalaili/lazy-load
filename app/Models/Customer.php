<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \stdClass;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';

    protected $fillable = [
        'id',
        'invoice',
        'nama', 
        'tanggal',
        'jeniskelamin',
        'saldo',
    ];
    protected $hidden = [
        'id', 
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d';

    public function getIndex($page,  $sidx, $sord, $limit)
    {
        $query = DB::table('customers')->select(
            'customers.id',
            'customers.invoice',
            'customers.nama',
            'customers.tanggal',
            'customers.jeniskelamin',
            'customers.saldo',
            'customers.created_at',
            'customers.updated_at',
        );

        $count = $query->count();
        $totalPages = ($count > 0 && $limit > 0) ? ceil($count/$limit) : 0;

        $start = ($page - 1) * $limit;

        $data = $query->orderBy($sidx, $sord)->offset($start)->limit($limit)->get(); 

        $response = new stdClass();
        $response->page = $page;
        $response->total = $totalPages;
        $response->records = $count;
        $response->data = $data;

        $data = $response;
        return $data;
    }
}
