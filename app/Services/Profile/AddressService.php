<?php

namespace App\Services\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileAddress;
use App\Models\User;
use Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\CustomInvalidException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddressService {
    protected $model;

    function __construct(ProfileAddress $profileAddress){
        $this->model = $profileAddress;
    }

    function getAll(){
        return $this->model::all();
    }

    function getOne($id){
        return $this->model::find($id);
    }

    function getAddressDetail($id){
        $address = $this->model::find($id);
        return $address->urban . ', ' . $address->district . ', ' . $address->city . ', ' . $address->province . ', ' . $address->postal_code . '. (' . $address->address_detail . ', ' . $address->note . ').';
    }

    function getAddressDetailByUser($userId){
        $user   = User::find($userId);
        $userAddress = $user->userAddress;
        $addressDetail = self::getAddressDetail($userAddress->id);
        return $user->name . ': ' . $addressDetail;
    }

    function store($data){
        try{
            $validator = Validator::make($data->all(),[
                'user_id'   => 'required|exists:pgsql.public.users,id|unique:pgsql.profile.address,user_id',
                'urban'     => 'required',
                'district'  => 'required',
                'city'      => 'required',
                'province'  => 'required',
                'postal_code'   => 'required',
                'address_detail'    => 'required',
                'note'      => 'required',
            ]);
            if($validator->fails()){
                throw new HttpResponseException(response()->json([
                    'success'   => false,
                    'message'   => 'Validation errors',
                    'data'      => $validator->errors()
                ],400));
            }
            $data['created_by'] = Auth::user()->name;
            $created = new $this->model;
            $created = $created->create($data->all());
            return $created->fresh();
        } catch (HttpResponseException $e){
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'data'      => $validator->errors()
            ],400));
        }
    }

    function update($request, $id){
        try{
            $validator = Validator::make($request->all(),[
                'user_id'   => 'required|exists:pgsql.public.users,id',
                'urban'     => 'required',
                'district'  => 'required',
                'city'      => 'required',
                'province'  => 'required',
                'postal_code'   => 'required',
                'address_detail'    => 'required',
                'note'      => 'required',
            ]);
            if($validator->fails()){
                throw new HttpResponseException(response()->json([
                    'success'   => false,
                    'message'   => 'Validation errors',
                    'data'      => $validator->errors()
                ],400));
            }
            $data   = $request->all();
            $data['updated_by'] = Auth::user()->name;
            return $this->model::findOrFail($id)->update($data);
        } catch (HttpResponseException $e){
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'data'      => $validator->errors()
            ],400));
        } catch (ModelNotFoundException $exception) {
            throw new CustomInvalidException('ID Not Found');
        }
    }

    function destroy($id){
        return $this->model::destroy($id);
    }
}