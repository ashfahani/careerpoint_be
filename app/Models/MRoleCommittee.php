<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MRoleCommittee extends Model
{
    protected $table = 'm_role_committee';
    protected $perPage = 10;
	public $timestamps = true;

    protected $fillable = [
        'name',
        'score',
        'na',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public static function baseQuery()
	{
		return MLevelCommittee::select(
			'id',
			'name',
			'score',
            'na'
		);
	}

    public static function searchFilter($query, $search)
	{
		return $query->where(function ($query) use ($search) {
			$query->where('name', 'like', '%' . $search . '%');
		});
	}

    public static function statusFilter($query, $status)
	{
		if ($status == 'all') return $query;
		return $query->where('na', $status);
	}

    public static function orderFilter($query, $orderBy, $order)
	{
		if ($orderBy == '') return $query;
		return $query->orderBy($orderBy, $order);
	}

	public static function getScore($id)
	{
		return MRoleCommittee::select('score')->where('id', $id)->where('na', 'n');
	}
}
