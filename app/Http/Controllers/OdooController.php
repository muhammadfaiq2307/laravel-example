<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\OdooConnection;
use Illuminate\Support\Facades\Auth;

/**
 * Example class to demonstrate connecting, authenticating, and querying to Odoo with JsonRPC
 */
class OdooController extends Controller
{
    /**
     * Example of authenticate and execute json rpc
     */
    public function index(){
        $url = env('ODOO_URL');
        $database = env('ODOO_DATABASE');
        $credentials = array(
            'username' => Auth::user()->email,
            'password' => env('ODOO_PASSWORD')
        );
        $odoo = new OdooConnection($url, $database, $credentials);
        //example connection call to Odoo MRP for WMS RM/PM
        $table = 'stock.picking';
        $filters = [['id','=','7098']];
        $fields = ['id','name','origin','picking_type_id','location_dest_id','note','picked','state','location_dest_id'];
        $stocks = $odoo->searchRead($table, $filters, $fields);
        dd($stocks);
    }
}
