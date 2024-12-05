<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\DataSatuan;
use App\Models\Notification;
use DB;

class DataSatuanController extends Controller
{
    /** halaman data satuan */
    public function index()
    {
        // Mengambil semua data dari tabel data_satuan
        $dataSatuan = DB::table('data_satuan')->get();
        
        return view('employees.data_satuan', compact('dataSatuan'));
    }
    /** halaman data satuan */

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
            $resultDataSatuan = DataSatuan::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $resultDataSatuan = DataSatuan::where('kode_barang', 'like', "%{$search}%")
                ->orWhere('nama_barang', 'like', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        
            $totalFiltered = DataSatuan::where('kode_barang', 'like', "%{$search}%")
                ->orWhere('nama_barang', 'like', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($resultDataSatuan)) {
            foreach ($resultDataSatuan as $key => $value) {
                $nestedData['id'] = $counter++;
                $nestedData['kode_barang'] = $value->kode_barang;
                $nestedData['nama_barang'] = $value->nama_barang;
                $nestedData['action'] = "<div class='dropdown dropdown-action'>
                                            <a href='#' class='action-icon dropdown-toggle' data-toggle='dropdown' aria-expanded='false'><i class='material-icons'>more_vert</i></a>
                                        <div class='dropdown-menu dropdown-menu-right'>
                                            <a href='#' class='dropdown-item edit_databarang' data-toggle='modal' data-target='#edit_databarang' data-id='" . $value->id . "' data-kode_barang='" . $value->kode_barang . "' data-nama_barang='" . $value->nama_barang . "'><i class='fa fa-pencil m-r-5'></i> Edit</a>
                                            <a href='#' class='dropdown-item delete_databarang' data-toggle='modal' data-target='#delete_databarang' data-id='" . $value->id . "'><i class='fa fa-trash-o m-r-5'></i> Delete</a>
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

    /** pencarian data satuan */
    public function searchDataSatuan(Request $request)
    {
        $keyword_kode_barang = $request->input('keyword_kode_barang');
        $keyword_nama_barang = $request->input('keyword_nama_barang');

        $cariDataSatuan = DB::table('data_satuan')
            ->where(function ($query) use ($keyword_kode_barang, $keyword_nama_barang) {
                $query->where('kode_barang', 'like', '%' . $keyword_kode_barang . '%')
                    ->orWhere('nama_barang', 'like', '%' . $keyword_nama_barang . '%');
            })
            ->get();

        return view('employees.data_satuan', compact('cariDataSatuan'));
    }
    /** pencarian data satuan */

    /** tambah data satuan */
    public function addDataSatuan(Request $request)
    {
        $request->validate([
            'kode_barang'   => 'required|string|max:255',
            'nama_barang'   => 'required|string|max:255',
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
    /** tambah data satuan */

    /** perbaharui data satuan */
    public function updateDataSatuan(Request $request)
    {
        DB::beginTransaction();
        try {
            $sqlDataSatuan = [
                'id'            => $request->id,
                'kode_barang'   => $request->kode_barang,
                'nama_barang'   => $request->nama_barang,
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
    /** perbaharui data satuan */

    /** hapus data satuan */
    public function deleteDataSatuan(Request $request)
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
    /** hapus data satuan */
}
