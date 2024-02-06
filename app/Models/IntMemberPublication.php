<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IntMemberPublication extends Model
{
    protected $table = 't_int_publication_member';
    protected $perPage = 10;
	public $timestamps = true;

    protected $fillable = [
		'id_internal_publication', 'id_user', 'initial_period', 'final_period', 'id_level', 'id_role', 'score', 'role_description', 'approve', 'na', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];

    public static function baseQuery()
	{
		return DB::table('t_int_publication_member AS cp')
        ->join('m_level_publication AS l', 'cp.id_level', 'l.id')
        ->join('m_role_publication AS r', 'cp.id_role', 'r.id')
		->join('m_users AS u', 'u.nim_nik', 'cp.id_user')
		->select('cp.*', 'l.name AS level_name', 'r.name AS role_name', 'u.name AS student_name');
	}

    public static function searchFilter($query, $search)
	{
		return $query->where(function ($query) use ($search) {
			$query->where('u.name', 'like', '%' . $search . '%')->orWhere('cp.initial_period', 'like', '%'.$search.'%')->orWhere('cp.final_period', 'like', '%'.$search.'%')->orWhere('cp.role_description', 'like', '%'.$search.'%');
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

	public static function getStudent($nim_nik)
	{
		return DB::table('m_users AS u')->select('u.*')->where('u.id_user_role', '4')->where('u.na', 'N')->where('u.nim_nik', $nim_nik);
	}
}
