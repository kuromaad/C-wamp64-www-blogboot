<?php

namespace App\Http\Controllers;

use App\asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->middleware('permission:asset-list|asset-create|asset-edit|asset-delete', ['only' => ['index','show']]);
         $this->middleware('permission:asset-create', ['only' => ['create','store']]);
         $this->middleware('permission:asset-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:asset-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $user = Auth()->user();
        $assets = \App\asset::orderBy('id','DESC')->paginate(5);
        return view('asset.index',compact('assets'))
            ->with('i', ($request->input('page', 1) - 1) * 5)
            ->with('user',$user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('asset.create');
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
            'name' => 'required',
            'description' => 'required',
            'prix' => 'required',
            'category' => 'required',
            'dateService' => 'required',
            'duree_vie' => 'required',
        ]);

        $asset = new \App\asset;
        $asset->name = $request->name;
        $asset->description=$request->description;
        $asset->prix = $request->prix;
        $asset->category = $request->category;
        $asset->dateService = $request->dateservice;
        $asset->duree_vie = $request->duree;

        $asset->save();
        return redirect()->back()->with('success','you added asset successfuly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\asset  $asset
     * @return \Illuminate\Http\Response
     */ 
    public function show(asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=Auth()->user();
        $asset = \App\asset::find($id);
        return view('asset.edit')->with('asset',$asset)->with('user',$user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',
            'prix' => 'required',
            'category' => 'required',
            'dateService' => 'required',
            'duree_vie' => 'required',
        ]);

        $asset = \App\asset::find($id);
        $asset->name = $request->name;
        $asset->description=$request->description;
        $asset->prix = $request->prix;
        $asset->category = $request->category;
        $asset->dateService = $request->dateservice;
        $asset->duree_vie = $request->duree;
        $asset->selected = 1;

        $asset->save();
        return redirect()->back()->with('success','you edited asset successfuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset = \App\asset::find($id);

        DB::table("asset_bureau")->where('asset_id',$id)->delete();
        $asset->delete();
        return redirect()->back()->with('success','you deleted asset');
    }

    public function selected($id){
        $asset = \App\asset::find($id);
        $asset->selected = 1;
        $asset->save();
        return redirect()->back();
    }
    public function notselected($id){
        $asset = \App\asset::find($id);
        $asset->selected = 0;
        $asset->save();
        return redirect()->back();
    }

    public function selectall(){
        foreach(\App\asset::all() as $asset)
        {
        $asset->selected = 1;
        $asset->save();
    }
    return redirect()->route('assetList');
    }
}
