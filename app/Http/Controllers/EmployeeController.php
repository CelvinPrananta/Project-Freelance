<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\DataSatuan;
use App\Models\Notification;
use DB;

class EmployeeController extends Controller
{
    /** halaman data barang */
    public function indexDataSatuan()
    {
        // Mengambil semua data dari tabel data_satuan
        $dataSatuan = DB::table('data_satuan')->get();

        return view('dashboard.satuan-data', compact('dataSatuan'));
    }
    /** halaman data barang */

    public function getDataSatuan(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'kode_barang',
            2 => 'nama_barang',
        );

        $totalData = DataSatuan::count();

        $totalFiltered = $totalData;

        $limit = $request->length;
        $start = $request->start;
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');
        $counter = $start + 1;

        if (empty($search)) {
            $sqlDataSatuan = DataSatuan::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $sqlDataSatuan =  DataSatuan::where('nama_barang', 'like', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DataSatuan::where('nama_barang', 'like', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($sqlDataSatuan)) {
            foreach ($sqlDataSatuan as $key => $item) {
                $nestedData['id'] = $counter++;
                $nestedData['kode_barang'] = $item->kode_barang;
                $nestedData['nama_barang'] = $item->nama_barang;
                


                $nestedData['action'] = "<div class='dropdown dropdown-action'>
                                            <a href='#' class='action-icon dropdown-toggle' data-toggle='dropdown' aria-expanded='false'><i class='material-icons'>more_vert</i></a>
                                        <div class='dropdown-menu dropdown-menu-right'>
                                            <a href='#' class='dropdown-item edit_barang' data-toggle='modal' data-target='#edit_barang' data-id='" . $value->id . "' data-nama_barang='" . $value->nama_barang . "' data-kode_barang='" . $value->kode_barang . "'><i class='fa fa-pencil m-r-5'></i> Edit</a>
                                            <a href='#' class='dropdown-item delete_barang' data-toggle='modal' data-target='#delete_barang' data-id='" . $value->id . "'><i class='fa fa-trash-o m-r-5'></i> Delete</a>
                                        </div>
                                     </div>";

                $nestedData['action'] = "<div class='dropdown dropdown-action'>
                                                <a href='#' class='action-icon dropdown-toggle' data-toggle='dropdown' aria-expanded='false'><i class='material-icons'>more_vert</i></a>
                                            <div class='dropdown-menu dropdown-menu-right'>


                                                <a href='#' class='dropdown-item edit_barang' data-toggle='modal' data-target='#edit_barang' data-id='" . $value->id . "' data-agama='" . $value->agama . "'><i class='fa fa-pencil m-r-5'></i> Edit</a>
                                                <a href='#' class='dropdown-item hapus_barang' data-toggle='modal' data-target='#hapus_barang' data-id='" . $value->id . "'><i class='fa fa-trash-o m-r-5'></i> Delete</a>
                                       
                                       
                                            </div>
                                        </div>";


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

    /** pencarian data barang */
    public function searchDataSatuan(Request $request)
    {
        $keywordkode = $request->input('keywordkode');
        $keywordbarang = $request->input('keywordbarang');

        // Periksa apakah keyword tidak kosong
        if (empty($keyword)) {
            // Jika tidak ada keyword, kembalikan semua data
            $sqlDataSatuan = DB::table('data_satuan')->get();
        } else {
            // Cari berdasarkan keyword di kode_barang atau nama_barang
            $sqlDataSatuan = DB::table('data_satuan')
                ->where('kode_barang', 'like', '%' . $keywordkode . '%')
                ->orWhere('nama_barang', 'like', '%' . $keywordbarang . '%')
                ->get();
        }

        return view('dashboard.satuan-data', compact('sqlDataSatuan'));
    }
    /** pencarian data barang */

    /** tambah data barang */
    public function tambahDataSatuan(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|string|max:255',
            'nama_barang'  => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $sqlDataSatuan = DataSatuan::query()
                ->when($request->kode_barang, function ($query, $kode_barang) {
                    $query->where('kode_barang', $kode_barang);
                })
                ->when($request->nama_barang, function ($query, $nama_barang) {
                    $query->orWhere('nama_barang', $nama_barang);
                })
                ->first();

            if ($sqlDataSatuan === null) {
                $sqlDataSatuan = new DataSatuan;
                $sqlDataSatuan->kode_barang = $request->kode_barang;
                $sqlDataSatuan->nama_barang = $request->nama_barang;
                $sqlDataSatuan->save();

                DB::commit();
                Toastr::success('Berhasil menambahkan data barang', 'Sukses');
                return redirect()->back();
            } else {
                DB::rollback();
                Toastr::error('Gagal menambahkan data, barang telah tersedia', 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Gagal menambahkan data barang', 'Error');
            return redirect()->back();
        }
    }
    /** tambah data barang */

    /** perbaharui data barang */
    public function editDataSatuan(Request $request)
    {
        DB::beginTransaction();
        try {
            $sqlDataSatuan = [
                'id'    => $request->id,
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
            ];
            DataSatuan::where('id', $request->id)->update($sqlDataSatuan);

            DB::commit();
            Toastr::success('Berhasil perbaharui data barang', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Gagal perbaharui data barang', 'Error');
            return redirect()->back();
        }
    }
    /** perbaharui data barang */

    /** hapus data barang */
    public function hapusDataSatuan(Request $request)
    {
        try {
            DataSatuan::destroy($request->id);
            Toastr::success('Berhasil menghapus data barang', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Gagal menghapus data barang', 'Error');
            return redirect()->back();
        }
    }
    /** hapus data barang */
}