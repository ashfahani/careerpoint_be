<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IntInternship extends Model
{
    protected $table = 't_internal_internship';
    protected $perPage = 10;
	public $timestamps = true;

    protected $fillable = [
		'id_activity_type', 'id_activity_category', 'activity_name', 'initial_period', 'final_period', 'activity_purpose', 'organizer_name', 'organizer_location', 'id_pic', 'id_supervisor', 'final', 'approve', 'file', 'file_type', 'reject_text', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];

    public static function baseQuery()
	{
		return DB::table('t_internal_internship AS cp')
        ->join('m_activity_type AS at', 'cp.id_activity_type', 'at.id')
        ->join('m_activity_category AS ac', 'cp.id_activity_category', 'ac.id')
		->select('cp.*', 'at.name AS type_name', 'ac.name AS category_name');
	}

    public static function searchFilter($query, $search)
	{
		return $query->where(function ($query) use ($search) {
			$query->where('cp.activity_name', 'like', '%' . $search . '%')->orWhere('cp.initial_period', 'like', '%'.$search.'%')->orWhere('cp.final_period', 'like', '%'.$search.'%')->orWhere('cp.organizer_name', 'like', '%'.$search.'%');
		});
	}

    public static function statusFilter($query, $status)
	{
		if ($status == 'all') return $query;
		return $query->where('cp.approve', $status);
	}

    public static function orderFilter($query, $orderBy, $order)
	{
		if ($orderBy == '') return $query;
		return $query->orderBy($orderBy, $order);
	}

	public static function getPIC($nim_nik)
	{
		return DB::table('m_users AS u')->select('u.*')->where('u.id_user_role', '3')->where('u.na', 'N')->where('u.nim_nik', $nim_nik);
	}

	public static function getCPDetail($id)
	{
		return DB::table('t_internal_internship')->select('*')->where('id', $id);
	}

	public static function getCPMember($id)
	{
		return DB::table('t_internal_internship AS cp')
		->join('t_int_internship_member AS m', 'm.id_internal_internship', 'cp.id')
		->select('cp.id_activity_type', 'cp.id_activity_category', 'm.id_user', 'cp.activity_name', 'cp.initial_period', 'cp.final_period', 'cp.organizer_name', 'cp.organizer_location', 'm.id_level', 'm.id_role', 'm.score', 'cp.file', 'cp.file_type', 'cp.id')
		->where('cp.id', $id)->where('cp.approve', 'H')->where('m.approve', 'H')->where('m.score', '<>', '0')->get();
	}
}
