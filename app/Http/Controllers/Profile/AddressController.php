<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Profile\AddressService;
use Yajra\DataTables\DataTables;

class AddressController extends Controller
{
    protected $service;
    function __construct(AddressService $addressService){
        $this->service = $addressService;
    }

    function index(){
        return view('profile.address.index');
    }

    function serviceGetAll(){
        return $this->service->getAll();
    }

    function getAll(){
        $result = [
            'data'  => $this->serviceGetAll()
        ];
        return response()->json($result,200);
    }

    function getAllDatatable(){
        return DataTables::of($this->serviceGetAll())
            ->addColumn('actions',function($data){
                $actions = '';
                $actions .= "<button type='button' id='detail' class='btn btn-info btn-flat btn-sm mr-2' title='Address Detail'>D</button>";
                $actions .= "<button type='button' id='de;ete' class='btn btn-danger btn-flat btn-sm' title='Delete Address'>D</button>";
                        
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    function create(){
        return view('profile.address.create');
    }

    function getOne($id){
        $result = [
            'data'  => $this->service->getOne($id)
        ];
        return response()->json($result,200);
    }

    function getUserAddressDetail($userId){
        $result = [
            'data'  => $this->service->getAddressDetailByUser($userId)
        ];
        return response()->json($result,200);
    }

    function store(Request $request){
        $result = [
            'success'   => true,
            'data'  => $this->service->store($request)
        ];
        return response()->json($result,200);
    }

    function update(Request $request, $id){
        $result = [
            'data'  => $this->service->update($request, $id)
        ];
        return response()->json($result,200);
    }

    function destroy($id){
        $result = [
            'data'  => $this->service->destroy($id)
        ];
        return response()->json($result,200);
    }
}
