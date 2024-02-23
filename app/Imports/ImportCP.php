<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportCP implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        return response($collection);
        exit();
        switch ($collection->get('Jenis CareerPoint')) {
            case 'Kepanitiaan':
                if($collection->get('Jenis Kepanitiaan') == '1'){   // Lomba                 

                    if($collection->get('Jenis Keanggotaan') == '1'){   // Panitia
                        $getLevel = DB::table('m_level_committee')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $getRole = DB::table('m_role_committee')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $score = $getLevel->score * $getRole->score;

                        $getID = DB::table('t_internal_committee')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                        if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                            $checkUser = DB::table('t_int_committee_member')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                            if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                                $data = array(
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'updated_at'   => date('Y-m-d H:i:s'),
                                    'updated_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_member')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                            }else{
                                $data = array(
                                    'id_internal_committee' => $getID->id,
                                    'id_user'       => $collection->get('NIM'),
                                    'initial_period' => $getID->initial_period,
                                    'final_period'  => $getID->final_period,
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'role_description' => $collection->get('Deskripsi Posisi'),
                                    'created_at'   => date('Y-m-d H:i:s'),
                                    'created_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_member')->insert($data);
                            }

                        }else{
                            $data = array(
                                'id_activity_category'  => '1',
                                'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                                'activity_name'         => $collection->get('Nama Kegiatan'),
                                'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                                'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                                'organizer_name'        => $collection->get('Penyelenggara'),
                                'organizer_location'    => $collection->get('Lokasi'),
                                'id_pic'                => $collection->get('Inisial PIC'),
                                'id_supervisor'         => $collection->get('Inisial Supervisor'),
                                'id_committee_type'     => '1',
                                'created_at'            => date('Y-m-d H:i:s'),
                                'created_by'            => auth()->user()->nim_nik,
                            );
                            $insertGetID = DB::table('t_internal_committee')->insertGetId($data);

                            $data = array(
                                'id_internal_committee' => $insertGetID,
                                'id_user'       => $collection->get('NIM'),
                                'initial_period' => $getID->initial_period,
                                'final_period'  => $getID->final_period,
                                'id_level'      => $getLevel->id,
                                'id_role'       => $getRole->id,
                                'score'         => $score,
                                'role_description' => $collection->get('Deskripsi Posisi'),
                                'created_at'   => date('Y-m-d H:i:s'),
                                'created_by'    => auth()->user()->nim_nik
                            );

                            DB::table('t_int_committee_member')->insert($data);
                        }                   
                        
                    }elseif($collection->get('Jenis Keanggotaan') == '2'){   // Anggota
                        $getLevel = DB::table('m_level_competition')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $getRole = DB::table('m_role_competition')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $score = $getLevel->score * $getRole->score;

                        $getID = DB::table('t_internal_committee')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                        if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                            $checkUser = DB::table('t_int_committee_participant')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                            if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                                $data = array(
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'updated_at'   => date('Y-m-d H:i:s'),
                                    'updated_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_participant')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                            }else{
                                $data = array(
                                    'id_internal_committee' => $getID->id,
                                    'id_user'       => $collection->get('NIM'),
                                    'initial_period' => $getID->initial_period,
                                    'final_period'  => $getID->final_period,
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'role_description' => $collection->get('Deskripsi Posisi'),
                                    'created_at'   => date('Y-m-d H:i:s'),
                                    'created_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_participant')->insert($data);
                            }

                        }else{
                            $data = array(
                                'id_activity_category'  => '1',
                                'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                                'activity_name'         => $collection->get('Nama Kegiatan'),
                                'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                                'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                                'organizer_name'        => $collection->get('Penyelenggara'),
                                'organizer_location'    => $collection->get('Lokasi'),
                                'id_pic'                => $collection->get('Inisial PIC'),
                                'id_supervisor'         => $collection->get('Inisial Supervisor'),
                                'id_committee_type'     => '1',
                                'created_at'            => date('Y-m-d H:i:s'),
                                'created_by'            => auth()->user()->nim_nik,
                            );
                            $insertGetID = DB::table('t_internal_committee')->insertGetId($data);

                            $data = array(
                                'id_internal_committee' => $insertGetID,
                                'id_user'       => $collection->get('NIM'),
                                'initial_period' => $getID->initial_period,
                                'final_period'  => $getID->final_period,
                                'id_level'      => $getLevel->id,
                                'id_role'       => $getRole->id,
                                'score'         => $score,
                                'role_description' => $collection->get('Deskripsi Posisi'),
                                'created_at'   => date('Y-m-d H:i:s'),
                                'created_by'    => auth()->user()->nim_nik
                            );

                            DB::table('t_int_committee_participant')->insert($data);
                        }
                    }
                }elseif($collection->get('Jenis Kepanitiaan') == '2'){  // Seminar
                    if($collection->get('Jenis Keanggotaan') == '1'){   // Panitia
                        $getLevel = DB::table('m_level_committee')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $getRole = DB::table('m_role_committee')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $score = $getLevel->score * $getRole->score;

                        $getID = DB::table('t_internal_committee')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                        if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                            $checkUser = DB::table('t_int_committee_member')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                            if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                                $data = array(
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'updated_at'   => date('Y-m-d H:i:s'),
                                    'updated_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_member')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                            }else{
                                $data = array(
                                    'id_internal_committee' => $getID->id,
                                    'id_user'       => $collection->get('NIM'),
                                    'initial_period' => $getID->initial_period,
                                    'final_period'  => $getID->final_period,
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'role_description' => $collection->get('Deskripsi Posisi'),
                                    'created_at'   => date('Y-m-d H:i:s'),
                                    'created_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_member')->insert($data);
                            }

                        }else{
                            $data = array(
                                'id_activity_category'  => '1',
                                'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                                'activity_name'         => $collection->get('Nama Kegiatan'),
                                'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                                'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                                'organizer_name'        => $collection->get('Penyelenggara'),
                                'organizer_location'    => $collection->get('Lokasi'),
                                'id_pic'                => $collection->get('Inisial PIC'),
                                'id_supervisor'         => $collection->get('Inisial Supervisor'),
                                'id_committee_type'     => '2',
                                'created_at'            => date('Y-m-d H:i:s'),
                                'created_by'            => auth()->user()->nim_nik,
                            );
                            $insertGetID = DB::table('t_internal_committee')->insertGetId($data);

                            $data = array(
                                'id_internal_committee' => $insertGetID,
                                'id_user'       => $collection->get('NIM'),
                                'initial_period' => $getID->initial_period,
                                'final_period'  => $getID->final_period,
                                'id_level'      => $getLevel->id,
                                'id_role'       => $getRole->id,
                                'score'         => $score,
                                'role_description' => $collection->get('Deskripsi Posisi'),
                                'created_at'   => date('Y-m-d H:i:s'),
                                'created_by'    => auth()->user()->nim_nik
                            );

                            DB::table('t_int_committee_member')->insert($data);
                        }
                    }elseif($collection->get('Jenis Keanggotaan') == '2'){   // Anggota
                        $getLevel = DB::table('m_level_seminar')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $getRole = DB::table('m_role_seminar')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                        $score = $getLevel->score * $getRole->score;

                        $getID = DB::table('t_internal_committee')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                        if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                            $checkUser = DB::table('t_int_committee_participant')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                            if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                                $data = array(
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'updated_at'   => date('Y-m-d H:i:s'),
                                    'updated_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_participant')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                            }else{
                                $data = array(
                                    'id_internal_committee' => $getID->id,
                                    'id_user'       => $collection->get('NIM'),
                                    'initial_period' => $getID->initial_period,
                                    'final_period'  => $getID->final_period,
                                    'id_level'      => $getLevel->id,
                                    'id_role'       => $getRole->id,
                                    'score'         => $score,
                                    'role_description' => $collection->get('Deskripsi Posisi'),
                                    'created_at'   => date('Y-m-d H:i:s'),
                                    'created_by'    => auth()->user()->nim_nik
                                );

                                DB::table('t_int_committee_participant')->insert($data);
                            }

                        }else{
                            $data = array(
                                'id_activity_category'  => '1',
                                'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                                'activity_name'         => $collection->get('Nama Kegiatan'),
                                'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                                'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                                'organizer_name'        => $collection->get('Penyelenggara'),
                                'organizer_location'    => $collection->get('Lokasi'),
                                'id_pic'                => $collection->get('Inisial PIC'),
                                'id_supervisor'         => $collection->get('Inisial Supervisor'),
                                'id_committee_type'     => '2',
                                'created_at'            => date('Y-m-d H:i:s'),
                                'created_by'            => auth()->user()->nim_nik,
                            );
                            $insertGetID = DB::table('t_internal_committee')->insertGetId($data);

                            $data = array(
                                'id_internal_committee' => $insertGetID,
                                'id_user'       => $collection->get('NIM'),
                                'initial_period' => $getID->initial_period,
                                'final_period'  => $getID->final_period,
                                'id_level'      => $getLevel->id,
                                'id_role'       => $getRole->id,
                                'score'         => $score,
                                'role_description' => $collection->get('Deskripsi Posisi'),
                                'created_at'   => date('Y-m-d H:i:s'),
                                'created_by'    => auth()->user()->nim_nik
                            );

                            DB::table('t_int_committee_participant')->insert($data);
                        }
                    }
                }elseif($collection->get('Jenis Kepanitiaan') == '3'){  // kepanitiaan
                    $getLevel = DB::table('m_level_committee')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                    $getRole = DB::table('m_role_committee')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                    $score = $getLevel->score * $getRole->score;

                    $getID = DB::table('t_internal_committee')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                    if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                        $checkUser = DB::table('t_int_committee_member')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                        if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                            $data = array(
                                'id_level'      => $getLevel->id,
                                'id_role'       => $getRole->id,
                                'score'         => $score,
                                'updated_at'   => date('Y-m-d H:i:s'),
                                'updated_by'    => auth()->user()->nim_nik
                            );

                            DB::table('t_int_committee_member')->where('id_internal_committe', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                        }else{
                            $data = array(
                                'id_internal_committee' => $getID->id,
                                'id_user'       => $collection->get('NIM'),
                                'initial_period' => $getID->initial_period,
                                'final_period'  => $getID->final_period,
                                'id_level'      => $getLevel->id,
                                'id_role'       => $getRole->id,
                                'score'         => $score,
                                'role_description' => $collection->get('Deskripsi Posisi'),
                                'created_at'   => date('Y-m-d H:i:s'),
                                'created_by'    => auth()->user()->nim_nik
                            );

                            DB::table('t_int_committee_member')->insert($data);
                        }

                    }else{
                        $data = array(
                            'id_activity_category'  => '1',
                            'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                            'activity_name'         => $collection->get('Nama Kegiatan'),
                            'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                            'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                            'organizer_name'        => $collection->get('Penyelenggara'),
                            'organizer_location'    => $collection->get('Lokasi'),
                            'id_pic'                => $collection->get('Inisial PIC'),
                            'id_supervisor'         => $collection->get('Inisial Supervisor'),
                            'id_committee_type'     => '3',
                            'created_at'            => date('Y-m-d H:i:s'),
                            'created_by'            => auth()->user()->nim_nik,
                        );
                        $insertGetID = DB::table('t_internal_committee')->insertGetId($data);

                        $data = array(
                            'id_internal_committee' => $insertGetID,
                            'id_user'       => $collection->get('NIM'),
                            'initial_period' => $getID->initial_period,
                            'final_period'  => $getID->final_period,
                            'id_level'      => $getLevel->id,
                            'id_role'       => $getRole->id,
                            'score'         => $score,
                            'role_description' => $collection->get('Deskripsi Posisi'),
                            'created_at'   => date('Y-m-d H:i:s'),
                            'created_by'    => auth()->user()->nim_nik
                        );

                        DB::table('t_int_committee_member')->insert($data);
                    }
                }
                break;
            case 'Kepengurusan':
                $getLevel = DB::table('m_level_organization')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                $getRole = DB::table('m_role_organization')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                $score = $getLevel->score * $getRole->score;

                $getID = DB::table('t_internal_organization')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                    $checkUser = DB::table('t_int_organization_member')->where('id_internal_organization', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                    if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                        $data = array(
                            'id_level'      => $getLevel->id,
                            'id_role'       => $getRole->id,
                            'score'         => $score,
                            'updated_at'   => date('Y-m-d H:i:s'),
                            'updated_by'    => auth()->user()->nim_nik
                        );

                        DB::table('t_int_organization_member')->where('id_internal_organization', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                    }else{
                        $data = array(
                            'id_internal_organization' => $getID->id,
                            'id_user'       => $collection->get('NIM'),
                            'initial_period' => $getID->initial_period,
                            'final_period'  => $getID->final_period,
                            'id_level'      => $getLevel->id,
                            'id_role'       => $getRole->id,
                            'score'         => $score,
                            'role_description' => $collection->get('Deskripsi Posisi'),
                            'created_at'   => date('Y-m-d H:i:s'),
                            'created_by'    => auth()->user()->nim_nik
                        );

                        DB::table('t_int_organization_member')->insert($data);
                    }

                }else{
                    $data = array(
                        'id_activity_category'  => '1',
                        'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                        'activity_name'         => $collection->get('Nama Kegiatan'),
                        'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                        'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                        'organizer_name'        => $collection->get('Penyelenggara'),
                        'organizer_location'    => $collection->get('Lokasi'),
                        'id_pic'                => $collection->get('Inisial PIC'),
                        'id_supervisor'         => $collection->get('Inisial Supervisor'),
                        'created_at'            => date('Y-m-d H:i:s'),
                        'created_by'            => auth()->user()->nim_nik,
                    );
                    $insertGetID = DB::table('t_internal_organization')->insertGetId($data);

                    $data = array(
                        'id_internal_organization' => $insertGetID,
                        'id_user'       => $collection->get('NIM'),
                        'initial_period' => $getID->initial_period,
                        'final_period'  => $getID->final_period,
                        'id_level'      => $getLevel->id,
                        'id_role'       => $getRole->id,
                        'score'         => $score,
                        'role_description' => $collection->get('Deskripsi Posisi'),
                        'created_at'   => date('Y-m-d H:i:s'),
                        'created_by'    => auth()->user()->nim_nik
                    );

                    DB::table('t_int_organization_member')->insert($data);
                }
                break;
            case 'Magang':
                $getLevel = DB::table('m_level_internship')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                $getRole = DB::table('m_role_internsip')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                $score = $getLevel->score * $getRole->score;

                $getID = DB::table('t_internal_internship')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                    $checkUser = DB::table('t_int_internship_member')->where('id_internal_internship', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                    if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                        $data = array(
                            'id_level'      => $getLevel->id,
                            'id_role'       => $getRole->id,
                            'score'         => $score,
                            'updated_at'   => date('Y-m-d H:i:s'),
                            'updated_by'    => auth()->user()->nim_nik
                        );

                        DB::table('t_int_internship_member')->where('id_internal_internship', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                    }else{
                        $data = array(
                            'id_internal_internship' => $getID->id,
                            'id_user'       => $collection->get('NIM'),
                            'initial_period' => $getID->initial_period,
                            'final_period'  => $getID->final_period,
                            'id_level'      => $getLevel->id,
                            'id_role'       => $getRole->id,
                            'score'         => $score,
                            'role_description' => $collection->get('Deskripsi Posisi'),
                            'created_at'   => date('Y-m-d H:i:s'),
                            'created_by'    => auth()->user()->nim_nik
                        );

                        DB::table('t_int_internship_member')->insert($data);
                    }

                }else{
                    $data = array(
                        'id_activity_category'  => '1',
                        'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                        'activity_name'         => $collection->get('Nama Kegiatan'),
                        'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                        'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                        'organizer_name'        => $collection->get('Penyelenggara'),
                        'organizer_location'    => $collection->get('Lokasi'),
                        'id_pic'                => $collection->get('Inisial PIC'),
                        'id_supervisor'         => $collection->get('Inisial Supervisor'),
                        'created_at'            => date('Y-m-d H:i:s'),
                        'created_by'            => auth()->user()->nim_nik,
                    );
                    $insertGetID = DB::table('t_internal_internship')->insertGetId($data);

                    $data = array(
                        'id_internal_internship' => $insertGetID,
                        'id_user'       => $collection->get('NIM'),
                        'initial_period' => $getID->initial_period,
                        'final_period'  => $getID->final_period,
                        'id_level'      => $getLevel->id,
                        'id_role'       => $getRole->id,
                        'score'         => $score,
                        'role_description' => $collection->get('Deskripsi Posisi'),
                        'created_at'   => date('Y-m-d H:i:s'),
                        'created_by'    => auth()->user()->nim_nik
                    );

                    DB::table('t_int_internship_member')->insert($data);
                }
                break;
            case 'Publikasi':
                $getLevel = DB::table('m_level_publication')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                $getRole = DB::table('m_role_publication')->select('id', 'score')->where('name', $collection->get('Tingkatan Kegiatan'))->first();
                $score = $getLevel->score * $getRole->score;

                $getID = DB::table('t_internal_publication')->where('na', 'N')->where('approve', 'H')->where('activity_name', $collection->get('Nama Kegiatan'))->where('initial_period', $collection->get('Tgl Mulai(YYYY-MM-DD)'))->where('final_period', $collection->get('Tgl Berakhir(YYYY-MM-DD)'))->first();

                if(isset($getID->id) || !empty($getID->id)){    // Cek CP sudah tersimpan
                    $checkUser = DB::table('t_int_publication_member')->where('id_internal_publication', $getID->id)->where('id_user', $collection->get('NIM'))->first();
                    if(isset($checkUser->id) || !empty($checkUser->id)){ // Cek member sudah tersimpan
                        $data = array(
                            'id_level'      => $getLevel->id,
                            'id_role'       => $getRole->id,
                            'score'         => $score,
                            'updated_at'   => date('Y-m-d H:i:s'),
                            'updated_by'    => auth()->user()->nim_nik
                        );

                        DB::table('t_int_publication_member')->where('id_internal_publication', $getID->id)->where('id_user', $collection->get('NIM'))->update($data);
                    }else{
                        $data = array(
                            'id_internal_publication' => $getID->id,
                            'id_user'       => $collection->get('NIM'),
                            'initial_period' => $getID->initial_period,
                            'final_period'  => $getID->final_period,
                            'id_level'      => $getLevel->id,
                            'id_role'       => $getRole->id,
                            'score'         => $score,
                            'role_description' => $collection->get('Deskripsi Posisi'),
                            'created_at'   => date('Y-m-d H:i:s'),
                            'created_by'    => auth()->user()->nim_nik
                        );

                        DB::table('t_int_publication_member')->insert($data);
                    }

                }else{
                    $data = array(
                        'id_activity_category'  => '1',
                        'id_activity_type'      => $collection->get('Akademik/Non Akademik'),
                        'activity_name'         => $collection->get('Nama Kegiatan'),
                        'initial_period'        => $collection->get('Tgl Mulai(YYYY-MM-DD)'),
                        'final_period'          => $collection->get('Tgl Berakhir(YYYY-MM-DD)'),
                        'organizer_name'        => $collection->get('Penyelenggara'),
                        'organizer_location'    => $collection->get('Lokasi'),
                        'id_pic'                => $collection->get('Inisial PIC'),
                        'id_supervisor'         => $collection->get('Inisial Supervisor'),
                        'created_at'            => date('Y-m-d H:i:s'),
                        'created_by'            => auth()->user()->nim_nik,
                    );
                    $insertGetID = DB::table('t_internal_publication')->insertGetId($data);

                    $data = array(
                        'id_internal_publication' => $insertGetID,
                        'id_user'       => $collection->get('NIM'),
                        'initial_period' => $getID->initial_period,
                        'final_period'  => $getID->final_period,
                        'id_level'      => $getLevel->id,
                        'id_role'       => $getRole->id,
                        'score'         => $score,
                        'role_description' => $collection->get('Deskripsi Posisi'),
                        'created_at'   => date('Y-m-d H:i:s'),
                        'created_by'    => auth()->user()->nim_nik
                    );

                    DB::table('t_int_publication_member')->insert($data);
                }
                break;
        }
    }
}
