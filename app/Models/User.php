<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'm_users';
    // protected $primaryKey = 'id_user';
    protected $perPage = 10;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nim_nik',
        'name',
        'email',
        'email2',
        'password',
        'id_user_role',
        'id_prodi',
        'mentor',
        'na',
        'has_password',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function baseQuery()
	{
		return User::select('m_users.nim_nik', 'm_users.name', 'm_users.password', 'ur.name AS role', 'p.prodi_name', 'm_users.tahun_id', 'm_users.email', 'm_users.email2', 'm_users.mentor')
        ->join('m_user_role AS ur', 'm_users.id_user_role', 'ur.id')
        ->join('m_prodi AS p', 'm_users.id_prodi', 'p.id');
	}

    public static function searchFilter($query, $search)
	{
		return $query->where(function ($query) use ($search) {
			$query->where('m_users.name', 'like', '%' . $search . '%')->orWhere('m_users.nim_nik', 'like', '%'. $search .'%');
		});
	}

    public static function statusFilter($query, $status)
	{
		if ($status == 'all') return $query;
		return $query->where('m_users.na', $status);
	}

    public static function orderFilter($query, $orderBy, $order)
	{
		if ($orderBy == '') return $query;
		return $query->orderBy($orderBy, $order);
	}
}
