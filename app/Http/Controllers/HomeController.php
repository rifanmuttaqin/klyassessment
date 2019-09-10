<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\AssessmentLog\AssessmentLog;

use App\Http\Requests\Data\StoreDataRequest;
use App\Http\Requests\Data\UpdateDataRequest;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\File;

use Yajra\Datatables\Datatables;

use DB;

use Image;

class HomeController extends Controller
{
    const GENDER_MALE   = 10;
    const GENDER_FEMALE = 20; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $directory_list = Storage::files('public/data_file');
            $file_names = [];

            foreach ($directory_list as $key => $file_name) {
                
                $file_names[$key]['id'] = $key + 1;
                $file_names[$key]['name'] = basename($file_name);

            }

            return Datatables::of($file_names)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $name = "'".$row['name']."'";

                    $btn = '<button onclick="btnUbah('.$name.')" name="btnUbah" type="button" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></button>';
                    $delete = '<button onclick="btnDel('.$name.')" name="btnDel" type="button" class="btn btn-info"><span class="glyphicon glyphicon-trash"></span></button>';
                    return $btn .'&nbsp'. $delete; 
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('home.index', ['active'=>'home']);
    }


    /**
     *  Render Create
     * 
     */
    public function create()
    {
        return view('home.store', ['active'=>'home']);
    }

    /**
     *  Render Update
     * 
     */
    public function update($iduser=null)
    {
        if($iduser != null)
        {
            $content_data = Storage::get('public/data_file/'.$iduser);
            $content_data = explode(",",$content_data);

            $data = [];
            $count_content = 0;

            $data['profile_pic'] = null;

            while ( $count_content <= 6) 
            {
                if($count_content == 0)
                {
                    $data['name'] = $content_data[$count_content];
                }
                else if($count_content == 1)
                {
                    $data['email'] = $content_data[$count_content];
                }
                else if($count_content == 2)
                {
                    $data['date_of_birth'] = $content_data[$count_content];
                }
                else if($count_content == 3)
                {
                    $data['phone_number'] = $content_data[$count_content];
                }
                else if($count_content == 4)
                {
                    $data['gender'] = $content_data[$count_content];
                }
                else if($count_content == 5)
                {
                    if(count($content_data) == 6)
                    {
                        $data['profile_pic'] = $content_data[$count_content];
                    }
                    else
                    {
                        break;
                    }
                }

                $count_content++;
            }

            $data['iduser'] = $iduser;

            return view('home.update', [ 'active'=>'home','data'=>$data ]);
        }
    }

    /**
     *  Update Data (DO Update)
     * 
     */
    public function doUpdate(UpdateDataRequest $request)
    {
        $data_input = [
            $request->get('name'),$request->get('email'),
            $request->get('date_of_birth'),
            $request->get('phone_number'),
            $request->get('gender')
        ];

        if($request->hasFile('file'))
        {
            $image      = $request->file('file');
            $fileImage   = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream();
            Storage::disk('local')->put('public/data_picture/'.$fileImage, $img, 'public');

            array_push($data_input, $fileImage);
        }


        $data_input = implode(",",$data_input);
        $file_name = $request->get('iduser');

        // Remove Old Data
        if(Storage::disk('local')->delete('public/data_file/'.$file_name))
        {
            try {

               Storage::disk('local')->put('public/data_file/'.$file_name.'', $data_input);
               return redirect('/')->with('alert_success', 'Data '.$request->get('name').' Berhasil Diupdate');

            } catch (Exception $e) {
                report($e);
                return redirect('/')->with('alert_error', 'Gagal Disimpan');
            }  
        } 
    }


    /**
     *  Destroy Data (DO Delete)
     * 
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            if(Storage::disk('local')->delete('public/data_file/'.$request->get('iduser')))
            {
                return $this->getResponse(true,200,'','Data berhasil dihapus');
            } 
            else
            {
                return $this->getResponse(false,400,'','Data gagal dihapus');
            }       
        }
    }


    /**
     *  Store Data (DO Create)
     * 
     */
    public function store(StoreDataRequest $request)
    {
        $data_input = [
            $request->get('name'),
            $request->get('email'),
            $request->get('date_of_birth'),
            $request->get('phone_number'),
            $request->get('gender')
        ];

        if($request->hasFile('file'))
        {
            $image      = $request->file('file');
            $fileImage   = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream();
            Storage::disk('local')->put('public/data_picture/'.$fileImage, $img, 'public');

            array_push($data_input, $fileImage);
        }

        $data_input = implode(",",$data_input);

        try {

            $name = str_replace(' ', '', strtolower($request->get('name')));
            $file_name = $name.'-'.date('dHs').'.txt';
            
           Storage::disk('local')->put('public/data_file/'.$file_name.'', $data_input);
           return redirect('/')->with('alert_success', 'Data '.$request->get('name').' Berhasil Disimpan');

        } catch (Exception $e) {
            report($e);
            return redirect('/')->with('alert_error', 'Gagal Disimpan');
        }
    }
}
