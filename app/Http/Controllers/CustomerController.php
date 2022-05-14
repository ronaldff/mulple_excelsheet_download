<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function export() 
    {
       
        $results_per_page = 100;
        $records  = Customer::count();
        $page = ceil($records/$results_per_page);
        $start = 0;
      
        for($i = $start; $i<= $page; $i++) {  
            if($start != $records){
                $result = Customer::offset($start)->limit($results_per_page)->get()->toArray();

                
                $splitHTML[$i] = '<table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>';
                                
                                foreach ($result as $k => $v) {
                                    $splitHTML[$i] .= '<tr>
                                        <td width="10%">' . $result[$k]['id'] . '</td>
                                        <td width="40%">' . $result[$k]['name'] . '</td>
                                        <td width="30%">' . $result[$k]['email'] . '</td>
                                    </tr>';
                                }
                                $splitHTML[$i] .= '</table>';
                // $start = $i;
                // $start++;
                // $start = $start * $results_per_page;
                $start = $start + $results_per_page;
                // echo 'start : ' . $start . ' ' . 'limit : ' . $results_per_page;
            }
            // $results_per_page = $results_per_page;
        } 
        print json_encode($splitHTML);
        // die();
        // return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}
