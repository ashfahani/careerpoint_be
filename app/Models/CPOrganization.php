<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CPOrganization extends Model
{
    protected $table = 't_cp_organization';
    protected $perPage = 10;
	public $timestamps = true;

    protected $fillable = [
		'id_activity_type', 'id_activity_category', 'id_user', 'activity_name', 'initial_period', 'final_period', 'period', 'organizer_name', 'organizer_location', 'id_level', 'id_role', 'score', 'approve', 'na', 'file', 'file_type', 'reject_text', 'id_internal_organization', 'send_email', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];

    public static function baseQuery()
	{
		return DB::table('t_cp_organization AS cp')
        ->join('m_activity_type AS at', 'cp.id_activity_type', 'at.id')
        ->join('m_activity_category AS ac', 'cp.id_activity_category', 'ac.id')
        ->join('m_level_organization AS l', 'cp.id_level', 'l.id')
        ->join('m_role_organization AS r', 'cp.id_role', 'r.id')
		->select('cp.*', 'at.name AS type_name', 'ac.name AS category_name', 'l.name AS level_name', 'r.name AS role_name')
		->where('cp.na', 'N');
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

	public static function queryForMentor()
	{
		return DB::table('t_cp_organization AS cp')
        ->join('m_activity_type AS at', 'cp.id_activity_type', 'at.id')
        ->join('m_activity_category AS ac', 'cp.id_activity_category', 'ac.id')
        ->join('m_level_organization AS l', 'cp.id_level', 'l.id')
        ->join('m_role_organization AS r', 'cp.id_role', 'r.id')
		->join('m_users AS u', 'cp.id_user', 'u.nim_nik')
		->select('cp.*', 'at.name AS type_name', 'ac.name AS category_name', 'l.name AS level_name', 'r.name AS role_name', 'u.nim_nik', 'u.name AS mhs_name', 'u.email', 'u.email2');
	}
}
