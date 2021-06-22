<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Order;
use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input; 
use App\Imports\TransactionsImport;
use Carbon\Carbon;
use DB;

class FileUpload extends Controller
{

    public function index(Request $request)
    {           
        $orders = DB::table('orders')
                ->leftJoin('projects', 'orders.project_id', '=', 'projects.id')
                ->select('projects.customer_name', 'projects.project_name',
                 'orders.type', 'orders.deadline')->get(); 
 
        return view('order', compact('orders'));
    } 

    
    public function customerReport(Request $request)
    {                
        $orders = DB::table('projects') 
                ->select('projects.customer_name',  DB::raw("COUNT(distinct projects.id) as total_project")
               ,DB::raw("SUM(projects.project_qutstanding_debt) as total_debit"))
                 ->groupBy('projects.customer_name')->get(); 
 
        return view('customer', compact('orders'));
    } 


    public function fileUpload(Request $request){
        $validated = $request->validate([
            'file' => 'required|mimes:jpeg'
        ]);
        
        if($request->hasFile('file')){

            $path = $request->file('file')->getRealPath();
         //   $data = Excel::load($path, function($reader) {})->get();
         \Excel::import(new TransactionsImport,$request->file); 
         //get project if doesn't has order
         $projects = Project::where(['has_order' => 0])->get(); 
        $noticeType=0;//Notice
        $lienType=1;//Lien
        foreach ($projects as $project) { 
            // Notice 
            $date = Carbon::createFromFormat('Y-m-d', $project->project_commencement_date);
            $NoticeDeadline = $date->addDays(60);

             $order['project_id'] = $project->id; 
             $order['type'] =$noticeType; 
             $order['deadline'] = $NoticeDeadline;   
             Order::create($order); 
             // Lien 
             $date = Carbon::createFromFormat('Y-m-d', $project->project_start_date);
             $lienDeadline = $date->addDays(90);
 
              $order['project_id'] = $project->id; 
              $order['type'] =$lienType; 
              $order['deadline'] = $lienDeadline;   
              Order::create($order); 
         }


         \Session::put('success', 'Your file is imported successfully in database.');
            
         return back();
    
 
        }
   }


   public function fileUpload1(Request $req){
    $req->validate([
    'file' => 'required|mimes:csv|max:2048'
    ]);

    $fileModel = new File;

    if($req->file()) {
        $fileName = time().'_'.$req->file->getClientOriginalName();
        $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');

        $fileModel->name = time().'_'.$req->file->getClientOriginalName();
        $fileModel->file_path = '/storage/' . $filePath;
        $fileModel->save();

        return back()
        ->with('success', $fileModel->file_path )
        ->with('file', $fileName);
    }
}

} 
