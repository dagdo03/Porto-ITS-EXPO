<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $baris = 5;
        if(strlen($katakunci)){
            $data = mahasiswa::where('NRP', 'like', "%$katakunci%")
            ->orWhere('Nama', 'like', "%$katakunci%")
            ->orWhere('Jurusan', 'like', "%$katakunci%")
            ->paginate($baris);
        }
        else{
            $data = mahasiswa::orderBy('NRP', 'asc')->paginate($baris);
        }
        return view('mahasiswa.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Session::flash('NRP', $request->NRP);
        Session::flash('nama', $request->Nama);
        Session::flash('jurusan', $request->Jurusan);



        $request->validate([
            'NRP' => 'required|unique:mahasiswa,NRP',
            'Nama' => 'required',
            'Jurusan' => 'required'
        ],[
            'NRP.required' => 'NRP wajib diisi',
            'NRP.unique' => 'NRP telah ada',
            'Nama.required' => 'Nama wajib diisi',
            'Jurusan.required' => 'Jurusan wajib diisi'
        ]);
        $data = [
            'NRP'=>$request->NRP,
            'Nama'=>$request->Nama,
            'Jurusan'=>$request->Jurusan
        ];
        mahasiswa::create($data);
        return redirect('mahasiswa')->with('status', 'Data Telah Berhasil Diinput!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = mahasiswa::where('NRP', $id)->first();
        return view('mahasiswa.edit')->with('data', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required',
            'Jurusan' => 'required'
        ],[
            'Nama.required' => 'Nama wajib diisi',
            'Jurusan.required' => 'Jurusan wajib diisi'
        ]);
        $data = [
            'Nama'=>$request->Nama,
            'Jurusan'=>$request->Jurusan
        ];
        mahasiswa::where('NRP', $id)->update($data);
        return redirect('mahasiswa')->with('status', 'Data Telah Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        mahasiswa::where('NRP', $id)->delete();
        return redirect('mahasiswa')->with('status', 'Data Telah Berhasil Dihapus!');
    }
}
