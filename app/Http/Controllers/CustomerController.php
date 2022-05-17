<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Customer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use File;
use ZipArchive;
use Response;

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

    public function export_zip(Request $request)
    {
        $public_dir=public_path();
        $path = $public_dir.'/' . 'file_storage/';
        File::makeDirectory($path, $mode = 0777, true, true);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $result = Customer::all()->toArray();
        $chunckRes = array_chunk($result,100);
        $sheet->setCellValue('A1' , 'NAME');
        $sheet->setCellValue('E1', 'EMAIL ADDRESS');
        foreach ($chunckRes as $k =>$v) {
            $rand = rand(1111,9999); 
            $j = 0;
            $sd = 2;
            foreach($v as $z){
                $sheet->setCellValue('A'.$sd , $v[$j]['name']);
                $sheet->setCellValue('E'.$sd , $v[$j]['email']);
                $writer = new Xlsx($spreadsheet);
                $writer->save($path . 'Customer_'.$rand.'.xlsx');
                $j++;
                $sd++;
            }

            $k++;

        }

        $zip = new ZipArchive;
        $fileName = time().".zip";
       if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
       {
           $files = File::files($path);
           foreach ($files as  $value) {
               $relativeNameInZipFile = basename($value);
               $zip->addFile($value, $relativeNameInZipFile);
           }
           $zip->close();
       }

        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        
        $filetopath=$public_dir.'/'.$fileName;
        if(file_exists($filetopath)){
            File::deleteDirectory($path);
            return Response::download($filetopath,$fileName,$headers);
        }

        // unlink($filetopath);
        return view('welcome');
               
    }

}
