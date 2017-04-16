<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use App\Http\Requests;
use App\Crud;

class CrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Crud::orderBy('id','DESC')->paginate(3);
        return view('show',compact('datas'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

           
           $this->validate($request,[
                'judul' => 'required|max:225',
                'isi' => 'required',
                
            ]);
           $tambah = new Crud();
           $tambah->judul = $request['judul'];
           $tambah->isi = $request['isi'];
           $file = $request->file('gambar');
           $fileName = $file->getClientOriginalName();
           $request->file('gambar')->move('image/',$fileName);

           $tambah->gambar = $fileName; 
           $tambah->save();

           return redirect()->to('/');           
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // cara orang
        /*
        $tampilkan = Crud::find($id);
        return view('tampil')->with('tampilkan',$tampilkan);
        */
       
       // cara sendiri
       $tampilkan = Crud::where('id',$id)->first();
       return view('tampil',compact('tampilkan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tampilkanedit = Crud::where('id',$id)->first();
        return view('edit')->with('tampiledit',$tampilkanedit);


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
        $update = Crud::where('id',$id)->first();
        $update->judul = $request['judul'];
        $update->isi = $request['isi'];

        if($request->file('gambar')=="")
        {
            $update->gambar = $update->gambar;

        }
        else
        {
            $file = $request->file('gambar');
            $filename = $file->getClientOriginalName();
            $request->file('gambar')->move('image/',$filename);
            $update->gambar = $filename;
        }

        $update->update();
        return redirect()->to('/');
  
        /*
        $update = Crud::where('id',$id)->first();
        $update->judul = $request['judul'];
        $update->isi = $request['isi'];
        $update->update();
        return redirect()->to('/');
        */
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hapus = Crud::find($id);
        $hapus->delete();

        return redirect()->to('/');
    }

    public function search(Request $request){
        $query = $request->get('q');
        $hasil = Crud::where('judul','like','%'.$query.'%')->paginate(3);

        return view('result',compact('hasil','query'));



    }

    public function logout(){
        Auth::logout();
        return redirect()->to('login');
    }
}
