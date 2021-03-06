<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class GoodUsageRollingDoorDetail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'good_usage_rolling_door_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public $timestamps = false;

    //fungsi untuk mencari quota item_code yang sudah pernah digunakan
    public static function getDataUsage($item_code)
    {
        $item_code = strtoupper($item_code);
        $query = self::selectRaw('SUM(good_usage_rolling_door_details.length)')
                     ->join('good_usage_rolling_doors', 'good_usage_rolling_door_details.good_usage_rolling_door_id','=','good_usage_rolling_doors.id')
                     ->where('good_usage_rolling_door_details.item_code', $item_code)
                     ->where('good_usage_rolling_doors.delete_flag', 0)
                     ->groupBy('item_code')
                     ->first();
        return $query;
    }

    //Fungsi untuk mendapatkan anggota dari id good_usage_rolling_doors
    public static function getData($good_usage_rolling_door_id)
    {
        $query = self::where('good_usage_rolling_door_id', $good_usage_rolling_door_id)
                     ->get();
        return $query;
    }
}