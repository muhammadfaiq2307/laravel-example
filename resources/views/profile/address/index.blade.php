<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Address Management') }}
        </h2>
    </x-slot>
    <x-slot name="slot">
        <div class="pt-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white sm:rounded-lg">
                    <button class="btn btn-sm btn-success" id="create"> Create </button>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white sm:rounded-lg">
                    <div class="table-responsive">
                        <table class="table datatable-pagination" id="tabel-data">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Urban</th>
                                    <th>District</th>
                                    <th>City</th>
                                    <th>Province</th>
                                    <th>Postal Code</th>
                                    <th>Address Detail</th>
                                    <th>Note</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    @push('scripts')
    <script>
        let tabelData;
	    let baseUrl = '{{ url("profile/address/") }}';
        $(document).ready(function(){

            tabelData = $('#tabel-data').DataTable({
                pagingType: "simple",
                language: {
                    paginate: {'next': $('html').attr('dir') == 'rtl' ? 'Next &larr;' : 'Next &rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr; Prev' : '&larr; Prev'}
                },
                processing	: true, 
                serverSide	: true, 
                order		: [], 
                ajax		: {
                    url: baseUrl + '/all-datatable',
                    
                    type: "POST",
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                },
                dom : 'tpi',
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'user_id', name: 'user_id' },
                    { data: 'urban', name: 'urban'},
                    { data: 'district', name: 'district' },
                    { data: 'city', name: 'city' },
                    { data: 'province', name: 'province' },
                    { data: 'postal_code', name: 'postal_code' },
                    { data: 'address_detail', name: 'address_detail' },
                    { data: 'note', name: 'note' },
                    { data: 'actions', name: 'actions' }
                ],
            });

            $(document).on('click', '#detail',function(){
                alert('aa');
            });

            $(document).on('click','#create', function(){
                location.href = baseUrl + '/create';
            });
        });
    </script>
    @endpush
</x-app-layout>