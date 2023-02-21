<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PCRMember extends Model
{
    use HasFactory;
    public $table = "pcr_members";

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'email',
        'prc_number',
        'address',
        'username',
        'is_trainee',
        'reg_type_id',
        'mem_type_id',
        'non_type_id',
        'chapter_id',
        'prc_id',
        'memberships',
        'training_ins',
        'prc_upload',
        'is_declined',
        'balance',
        'is_approved',
        'deposit_slip'
    ];
    
    public function getAllPending() {
        $data = self::where('is_approved', 0)
        ->orderBy('last_name', 'asc')
        ->get();

        return $data;
    }

    public function getAllApproved() {
        $data = self::where('is_approved', 1)
        ->orderBy('last_name', 'asc')
        ->latest()
        ->paginate(20);

        return $data;
    }
    public function user() {
        return $this->morphOne(User::class, 'userable');
    }
    public function pcr_chapter() {
        return $this->belongsTo(PCRChapter::class,'chapter_id');
    }

}
