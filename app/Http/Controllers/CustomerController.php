<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = new Customer();

        $limit = $request->input('rows', 10); 
        $page = $request->input('page', 1);
        $sidx = $request->input('sidx'); 
        $sord = $request->input('sord', 'asc');

        $data = $customer->getIndex($page, $sidx, $sord, $limit);
        
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('customer.index');
    }
}
