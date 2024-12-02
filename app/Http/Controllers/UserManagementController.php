<?php

namespace App\Http\Controllers;

use App\Models\DaftarPegawai;
use App\Models\ModeAplikasi;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Rules\MatchOldPassword;
use App\Models\Notification;
use App\Models\userActivityLog;
use App\Models\activityLog;
use App\Models\User;
use App\Models\CardHistory;
use Carbon\Carbon;
use Session;
use Auth;
use Hash;
use DB;

class UserManagementController extends Controller
{
    // Tampilan Daftar Pengguna //
    public function index()
    {
        $result      = DB::table('users')->get();
        $role_name   = DB::table('role_type_users')->get();
        $status_user = DB::table('user_types')->get();
        $user = auth()->user();
        return view('admin.user_control', compact('result', 'role_name', 'status_user','user'));
    }
    // /Tampilan Daftar Pengguna //

    // Proses Data Pengguna //
    public function getPenggunaData(Request $request)
    {
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length");
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');
        $columnIndex     = $columnIndex_arr[0]['column'];
        $columnName      = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue     = $search_arr['value'];
        $users =  DB::table('users');
        $totalRecords = $users->count();
        $user_name   = $request->user_name;
        $type_role   = $request->type_role;
        $type_status = $request->type_status;

        $users->when($user_name, function ($query) use ($user_name) {
            $query->where('name', 'LIKE', "%{$user_name}%");
        })
            ->when($type_role, function ($query) use ($type_role) {
                $query->where('role_name', $type_role);
            })
            ->when($type_status, function ($query) use ($type_status) {
                $query->where('status', $type_status);
            });

        $totalRecordsWithFilter = $users->where(function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' . $searchValue . '%');
            $query->orWhere('user_id', 'like', '%' . $searchValue . '%');
            $query->orWhere('email', 'like', '%' . $searchValue . '%');
            $query->orWhere('username', 'like', '%' . $searchValue . '%');
            $query->orWhere('employee_id', 'like', '%' . $searchValue . '%');
            $query->orWhere('join_date', 'like', '%' . $searchValue . '%');
            $query->orWhere('role_name', 'like', '%' . $searchValue . '%');
            $query->orWhere('status', 'like', '%' . $searchValue . '%');
        })->count();

        $records = $users->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
                $query->orWhere('user_id', 'like', '%' . $searchValue . '%');
                $query->orWhere('email', 'like', '%' . $searchValue . '%');
                $query->orWhere('username', 'like', '%' . $searchValue . '%');
                $query->orWhere('employee_id', 'like', '%' . $searchValue . '%');
                $query->orWhere('join_date', 'like', '%' . $searchValue . '%');
                $query->orWhere('role_name', 'like', '%' . $searchValue . '%');
                $query->orWhere('status', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data_arr = [];
        foreach ($records as $key => $record) {
            if ($record->status_online === "Online") {
                $record->name = '<h2 class="table-avatar"><a href="' . route('showProfile', ['user_id' => $record->user_id]) . '" class="name">' . '<img class="avatar" data-avatar=' . $record->avatar . ' src="' . url('/assets/images/' . $record->avatar) . '" loading="lazy"><span class="status_online"></span>' . $record->name . '</a></h2>';
            } else {
                $record->name = '<h2 class="table-avatar"><a href="' . route('showProfile', ['user_id' => $record->user_id]) . '" class="name">' . '<img class="avatar" data-avatar=' . $record->avatar . ' src="' . url('/assets/images/' . $record->avatar) . '" loading="lazy"><span class="status_offline"></span>' . $record->name . '</a></h2>';
            }
            if ($record->role_name == 'Admin') {
                $role_name = '<span class="badge bg-inverse-danger role_name">' . $record->role_name . '</span>';
            } elseif ($record->role_name == 'User') {
                $role_name = '<span class="badge bg-inverse-info role_name">' . $record->role_name . '</span>';
            } else {
                $role_name = 'NULL';
            }

            /** status */
            $full_status = '
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item"><i class="fa fa-dot-circle-o text-success" style="color: #55ce63 !important;"></i> Aktif </a>
                    <a class="dropdown-item"><i class="fa fa-dot-circle-o text-warning" style="color: #ffbc34 !important;"></i> Tidak Aktif </a>
                    <a class="dropdown-item"><i class="fa fa-dot-circle-o text-danger" style="color: #f62d51 !important;"></i> Dibatasi </a>
                </div>
            ';

            if ($record->status == 'Active') {
                $status = '
                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-dot-circle-o text-success" style="color: #55ce63 !important;"></i>
                        <span class="status_s">' . $record->status . '</span>
                    </a>
                    ' . $full_status . '
                ';
            } elseif ($record->status == 'Inactive') {
                $status = '
                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-dot-circle-o text-info" style="color: #ffbc34 !important;"></i>
                        <span class="status_s">' . $record->status . '</span>
                    </a>
                    ' . $full_status . '
                ';
            } elseif ($record->status == 'Disable') {
                $status = '
                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-dot-circle-o text-danger" style="color: #f62d51 !important;"></i>
                        <span class="status_s">' . $record->status . '</span>
                    </a>
                    ' . $full_status . '
                ';
            } else {
                $status = '
                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-dot-circle-o text-dark"></i>
                        <span class="statuss">' . $record->status . '</span>
                    </a>
                    ' . $full_status . '
                ';
            }

            $joinDate = Carbon::parse($record->join_date)->translatedFormat('l, j F Y || h:i A');
            $data_arr[] = [
                "no"            => '<span class="id" data-id = ' . $record->id . '>' . $start + ($key + 1) . '</span>',
                "name"          => $record->name,
                "user_id"       => '<span class="user_id">' . $record->user_id . '</span>',
                "email"         => '<a href="mailto:' . $record->email . '"><span class="email">' . $record->email . '</span></a>',
                "username"      => '<span class="username">' . $record->username . '</span>',
                "employee_id"   => '<span class="employee_id">' . $record->employee_id . '</span>',
                "join_date"     => $joinDate,
                "role_name"     => $role_name,
                "status"        => $status,
                "action"        =>
                '
                <td>
                    <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item userUpdate" data-toggle="modal" data-id="' . $record->id . '" data-target="#edit_user">
                                <i class="fa fa-pencil m-r-5"></i> Edit
                            </a>
                        </div>
                    </div>
                </td>
                ',
            ];
        }
        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data_arr
        ];
        return response()->json($response);
    }
    // /Proses Data Pengguna //

    // Tampilan Pengguna Log Aktivitas //
    public function tampilanUserLogAktivitas()
    {
        $activityUserLog = DB::table('user_activity_logs')->get();
        return view('admin.user_activity_log', compact('activityUserLog'));
    }
    // /Tampilan Pengguna Log Aktivitas //

    // Tampilan Log Aktivitas //
    public function tampilanLogAktivitas()
    {
        $activityLog = DB::table('activity_logs')->get();
        return view('admin.activity_log', compact('activityLog'));
    }
    // /Tampilan Log Aktivitas //

    // Tampilan Profile User / Admin //
    public function profileUser($user_id = null)
    {
        if ($user_id == null) {
            $users = User::where('user_id', Session::get('user_id'))->firstOrFail();
        } else {
            $users = User::where('user_id', $user_id)->firstOrFail();
        }
        $user = auth()->user();
        return view('dashboard.profile_user', compact('users','user'));
    }
    // /Tampilan Profile User / Admin //

    // Perbaharui Data Pengguna Admin //
    public function perbaharuiDataPengguna(Request $request)
    {
        try {
            $updateDaftarPegawai = [
                'name'          => $request->name,
                'email'         => $request->email,
                'username'      => $request->username,
                'employee_id'   => $request->employee_id,
                'tgl_lahir'     => $request->birthDate,
                'avatar'        => $request->avatar,
            ];
            DB::table('daftar_pegawai')->where('user_id', $request->user_id)->update($updateDaftarPegawai);

            $information = User::updateOrCreate(['user_id' => $request->user_id]);
            $information->user_id       = $request->user_id;
            $information->name          = $request->name;
            $information->email         = $request->email;
            $information->username      = $request->username;
            $information->employee_id   = $request->employee_id;
            $information->tgl_lahir     = $request->birthDate;
            $information->avatar        = $request->avatar;
            $information->save();

            DB::commit();
            Toastr::success('Data pengguna berhasil diperbaharui', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Data pengguna gagal diperbaharui', 'Error');
            return redirect()->back();
        }
    }
    // /Perbaharui Data Pengguna Admin //


    // Perbaharui Foto Profil //
    public function perbaharuiFotoProfile(Request $request)
    {
        try {
            if (!empty($request->images)) {

                $image_name = $request->hidden_image;
                $image      = $request->file('images');

                if ($image_name == 'photo_defaults.jpg') {
                    if ($image != '') {
                        $image_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/assets/images/'), $image_name);
                    }
                } else {
                    if ($image != '') {
                        $image_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/assets/images/'), $image_name);
                        unlink('assets/images/' . Auth::user()->avatar);
                    }
                }

                $update = [
                    'user_id'   => $request->user_id,
                    'name'      => $request->name,
                    'avatar'    => $image_name,
                ];
                User::where('user_id', $request->user_id)->update($update);
            }

            $updateDaftarPegawai = [
                'avatar'    => $image_name
            ];
            DB::table('daftar_pegawai')->where('user_id', $request->user_id)->update($updateDaftarPegawai);


            DB::commit();
            Toastr::success('Foto profil berhasil diperbaharui', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Foto profil gagal diperbaharui', 'Error');
            return redirect()->back();
        }
    }
    // /Perbaharui Foto Profil //

    // Tambah Akun Pengguna //
    public function tambahAkunPengguna(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:255',
            'employee_id'           => 'nullable|string|max:255',
            'tema_aplikasi'         => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'role_name'             => 'required|string|max:255',
            'status'                => 'required|string|max:255',
            'status_online'         => 'required|string|max:255',
            'image'                 => 'required|string|max:255',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $authemployee_id    = $request->resultemployee_id;
            $authrole           = $request->resultrole;
            $dt                 = Carbon::now();
            $todayDate          = $dt->toDayDateTimeString();

            $activityLog = [
                'user_name'    => Session::get('name'),
                'email'        => Session::get('email'),
                'username'     => Session::get('username'),
                'employee_id'  => $authemployee_id,
                'status'       => Session::get('status'),
                'role_name'    => $authrole,
                'modify_user'  => 'Tambah Akun ' . $request->name,
                'date_time'    => $todayDate,
            ];

            DB::table('user_activity_logs')->insert($activityLog);

            $user = new User;
            $user->name             = $request->name;
            $user->username         = $request->username;
            $user->employee_id      = $request->employee_id ?? NULL;
            $user->tema_aplikasi    = $request->tema_aplikasi;
            $user->email            = $request->email;
            $user->join_date        = $todayDate;
            $user->role_name        = $request->role_name;
            $user->status           = $request->status;
            $user->status_online    = $request->status_online;
            $user->avatar           = $request->image;
            $user->password         = Hash::make($request->password);
            $user->save();

            DB::commit();
            Toastr::success('Akun pengguna berhasil ditambah', 'Success');
            return redirect()->route('manajemen-pengguna');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Akun pengguna gagal ditambah', 'Error');
            return redirect()->back();
        }
    }
    // /Tambah Akun Pengguna //

    // Perbaharui Akun Pengguna //
    public function perbaharuiAkunPengguna(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id        = $request->user_id;
            $name           = $request->name;
            $username       = $request->username;
            $employee_id    = $request->employee_id;
            $email          = $request->email;
            $role_name      = $request->role_name;
            $status         = $request->status;
            $avatar         = $request->images;

            $updateUsers = [
                'user_id'       => $user_id,
                'name'          => $name,
                'username'      => $username,
                'employee_id'   => $employee_id,
                'role_name'     => $role_name,
                'email'         => $email,
                'status'        => $status,
                'avatar'        => $avatar,
            ];
            User::where('user_id', $request->user_id)->update($updateUsers);

            $updateDaftarPegawai = [
                'user_id'       => $user_id,
                'name'          => $name,
                'username'      => $username,
                'employee_id'   => $employee_id,
                'role_name'     => $role_name,
                'email'         => $email,
                'avatar'        => $avatar,
            ];
            DB::table('daftar_pegawai')->where('user_id', $request->user_id)->update($updateDaftarPegawai);

            $authname           = $request->resultname;
            $authemail          = $request->resultemail;
            $authusername       = $request->resultusername;
            $authemployee_id    = $request->resultemployee_id;
            $authstatus         = $request->resultstatus;
            $authrole_name      = $request->resultrole_name;
            $dt       = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();

            $activityLog = [
                'user_name'     => $authname,
                'email'         => $authemail,
                'username'      => $authusername,
                'employee_id'   => $authemployee_id,
                'status'        => $authstatus,
                'role_name'     => $authrole_name,
                'modify_user'  => 'Perbaharui Akun ' . $request->name,
                'date_time'     => $todayDate,
            ];
            DB::table('user_activity_logs')->insert($activityLog);

            DB::commit();
            Toastr::success('Berhasil diperbaharui akun pengguna', 'Success');
            return redirect()->route('manajemen-pengguna');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Gagal diperbaharui akun pengguna', 'Error');
            return redirect()->back();
        }
    }
    // /Perbaharui Akun Pengguna //

    // Hapus Akun Pengguna //
    public function hapusAkunPengguna(Request $request)
    {
        DB::beginTransaction();
        try {
            $authEmployeeId = $request->employee_id;
            $resultName     = $request->name;
            $authRole       = $request->role_name;
            $dt             = Carbon::now();
            $todayDate      = $dt->toDayDateTimeString();
            $authUserId     = Auth::id();

            $activityLog = [
                'user_name'    => Session::get('name'),
                'email'        => Session::get('email'),
                'username'     => Session::get('username'),
                'employee_id'  => $authEmployeeId,
                'status'       => Session::get('status'),
                'role_name'    => $authRole,
                'modify_user'  => 'Hapus Akun ' . $resultName,
                'date_time'    => $todayDate,
            ];

            DB::table('user_activity_logs')->insert($activityLog);

            // Hapus data pengguna
            User::find($request->id)->delete();
            ModeAplikasi::find($request->id)->delete();
            DaftarPegawai::find($request->id)->delete();

            // Cek apakah akun yang dihapus adalah akun sendiri
            if ($request->id == $authUserId) {
                Auth::logout();
                DB::commit();
                Toastr::success('Akun anda berhasil dihapus', 'Success');
                return redirect()->route("login");
            } else {
                DB::commit();
                Toastr::success('Akun pengguna berhasil dihapus', 'Success');
                return redirect()->route("manajemen-pengguna");
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Akun pengguna gagal dihapus', 'Error');
            return redirect()->back();
        }
    }
    // /Hapus Akun Pengguna //

    // Tampilan Perbaharui Kata Sandi //
    public function tampilanPerbaharuiKataSandi()
    {
        return view('dashboard.changepassword');
    }
    // /Tampilan Perbaharui Kata Sandi //

    // Perbaharui Kata Sandi //
    public function perbaharuiKataSandi(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'string', 'min:8', new MatchOldPassword],
            'new_password'          => ['required', 'string', 'min:8'],
            'new_confirm_password'  => ['required', 'string', 'same:new_password', 'min:8'],
        ],
        [
            'current_password.required' => 'Bidang kata sandi lama wajib diisi.',
            'current_password.min' => 'Kata sandi lama harus minimal 8 karakter.',
            'new_password.required' => 'Bidang kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru harus minimal 8 karakter.',
            'new_confirm_password.required' => 'Bidang konfirmasi kata sandi baru wajib diisi.',
            'new_confirm_password.min' => 'Konfirmasi kata sandi baru harus minimal 8 karakter.',
            'new_confirm_password.same' => 'Kata sandi dan konfirmasi kata sandi baru tidak sesuai.',
        ]);

        DB::beginTransaction();
        try {
            // Perbarui kata sandi
            $user = User::find(auth()->user()->id);
            $user->update(['password' => Hash::make($request->new_password)]);

            // Tetap pertahankan sesi pengguna
            $request->session()->put('password_hash', $user->password);
            auth()->logoutOtherDevices($request->new_password);
            
            DB::commit();

            // Berikan notifikasi sukses
            Toastr::success('Kata sandi berhasil diperbaharui', 'Success');
            return redirect()->route("profile");
        } catch (\Exception $e) {
            DB::rollBack();
            // Berikan notifikasi error
            Toastr::error('Kata sandi gagal diperbaharui', 'Error');
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    // /Perbaharui Kata Sandi //

    // Proses Data Riwayat Aktivitas //
    public function getHistoryActivity(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'user_name',
            2 => 'email',
            3 => 'username',
            4 => 'employee_id',
            5 => 'status',
            6 => 'role_name',
            7 => 'modify_user',
            8 => 'date_time',
        );

        $totalData = userActivityLog::count();

        $totalFiltered = $totalData;

        $limit = $request->length;
        $start = $request->start;
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');
        $counter = $start + 1;

        if (empty($search)) {
            $activityLog = userActivityLog::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $activityLog =  userActivityLog::where('user_name', 'like', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = userActivityLog::where('user_name', 'like', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($activityLog)) {
            foreach ($activityLog as $key => $item) {
                $nestedData['id'] = $counter++;
                $nestedData['user_name'] = $item->user_name;
                $nestedData['email'] = $item->email;
                $nestedData['username'] = $item->username;
                $nestedData['employee_id'] = $item->employee_id;
                $nestedData['status'] = $item->status;
                $nestedData['role_name'] = $item->role_name;
                $nestedData['modify_user'] = $item->modify_user;
                $nestedData['date_time'] = $item->date_time;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return response()->json($json_data);
    }
    // /Proses Data Riwayat Aktivitas //

    // Proses Data Aktivitas Pengguna //
    public function getAktivitasPengguna(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'username',
            4 => 'employee_id',
            5 => 'description',
            6 => 'date_time',
        );

        $totalData = activityLog::count();
        $limit = $request->length;
        $start = $request->start;
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $counter = $start + 1;

        $query = activityLog::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
            $totalFiltered = $query->count();
        } else {
            $totalFiltered = activityLog::count();
        }
        $activityLog = $query->get();


        $data = array();
        if (!empty($activityLog)) {
            foreach ($activityLog as $key => $item) {
                $nestedData['id'] = $counter++;
                $nestedData['name'] = $item->name;
                $nestedData['email'] = $item->email;
                $nestedData['username'] = $item->username;
                $nestedData['employee_id'] = $item->employee_id;
                $nestedData['description'] = $item->description;
                $nestedData['date_time'] = $item->date_time;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return response()->json($json_data);
    }
    // /Proses Data Aktivitas Pengguna //

    public function deleteHistoryActivity(Request $request)
    {
        userActivityLog::truncate();
        session()->flash('success', 'Berhasil menghapus histori aktivitas');
        return response()->json([
            'redirect' =>  route('riwayat-aktivitas'),
        ]);
    }

    public function deleteHistoryOtentifikasi(Request $request)
    {
        activityLog::truncate();
        session()->flash('success', 'Berhasil menghapus histori otentikasi');
        return response()->json([
            'redirect' =>  route('riwayat-aktivitas-otentikasi'),
        ]);
    }
}