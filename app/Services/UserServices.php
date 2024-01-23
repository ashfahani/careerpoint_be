<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserServices
{
    public function fetchAll()
    {
        return User::get();
    }

    public function fetchSingle($id)
    {
        return DB::table('m_users AS u')
                ->select('u.id_user', 'u.name', 'ur.name AS role', 'p.prodi_name', 'u.tahun_id', 'u.email', 'u.email2', 'u.mentor')
                ->join('m_user_role AS ur', 'u.id_user_role', 'ur.id')
                ->join('m_prodi AS p', 'u.id_prodi', 'p.id')
                ->where('a.id_user', $id)
                ->get();
    }
}
?>