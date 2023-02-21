<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ElectionPeriod extends Model
{
    public $table = "election_period";
    protected $fillable = [
        'year',
        'date_started',
        'date_end',
        'is_active'
    ];

    public function getElectionPeriod($status,$user) {
        $type = ($status == 'active') ? 1 : 0;
        $mytime = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');
        if($status == 'active'){
            if($user == 'admin'){
                return self::where('is_active', $type)
                ->where('date_end','>',$mytime )
                ->orderBy('id', 'asc')
                ->get();
            }else {
                return self::where('is_active', $type)
                ->where('date_started','<',$mytime )
                ->where('date_end','>',$mytime )
                ->orderBy('id', 'asc')
                ->get();
            }
           
        }else{
            return self::where('is_active', $type)
            ->orderBy('id', 'asc')
            ->get();
        }
       
    }
    public function getElectionPeriodDetails($id) {
        return self::where('id', $id)
        ->get();
    }

    public function nominees(){
        return $this->hasMany(ElectionNominee::class, 'election_period_id');
    }

}
