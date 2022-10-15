<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User Address Management') }}
        </h2>
    </x-slot>
    <x-slot name="slot">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white sm:rounded-lg">
                    <form id="create-form" action="#">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> User ID </label>
                                    <input type="number" name="user_id" id="user_id" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> Urban </label>
                                    <input type="text" name="urban" id="urban" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> District </label>
                                    <input type="text" name="district" id="district" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> City </label>
                                    <input type="text" name="city" id="city" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> Province </label>
                                    <input type="text" name="province" id="province" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> Postal Code </label>
                                    <input type="number" name="postal_code" id="postal_code" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> Address Detail </label>
                                    <input type="text" name="address_detail" id="address_detail" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-float">
                                    <label> Note </label>
                                    <input type="text" name="note" id="note" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary"> Simpan </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>
    @push('scripts')
    <script>
        let tabelData;
	    let baseUrl = '{{ url("profile/address/") }}';
        $(document).ready(function(){
            $('#create-form').submit(function(e){
                if(!e.isDefaultPrevented()){
                    e.preventDefault();
                    let record = $('#create-form').serialize();

                    $.ajax({
                        'type': 'POST',
                        'url' : baseUrl + '/store',
                        'data': record,
                        'dataType': 'JSON',
                        'success': function(response){
                            if(response["success"]) {     
                                location.href = baseUrl;
                            }else{
                                alert('Failed')
                            }
                        },
                        'error': function(response){ 
                            alert('Something went wrong');
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>