<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class fileController extends Controller
{
    //
    public function filePreview(Request $request){   
        if ($request->hasFile('studentfile')) {
            // $filesystem = Storage::disk('local');
            $file = $request->file('studentfile');
            $filename = $file->getClientOriginalName();
            // $path = $request->file('studentfile')->store('students', $filename);
            $path = Storage::putFile('students', $request->file('studentfile'));

            $file = Storage::disk('local')->get($path);
            $rows = explode("\n", $file);
            $response = array();
                        
            foreach ($rows as $row) {
                $data = str_getcsv($row);
                array_push($response, $data);
            }
            Storage::delete($path);
            return view('dataPreview', ['data' => json_encode($response)]);

        }
        else{
            echo "No file";
        }
    }


    public function uploadStudents(Request $request){   
        if ($request->hasFile('studentfile')) {
            // $filesystem = Storage::disk('local');
            $file = $request->file('studentfile');
            $filename = $file->getClientOriginalName();
            // $path = $request->file('studentfile')->store('students', $filename);
            $path = Storage::putFile('students', $request->file('studentfile'));

            $file = Storage::disk('local')->get($path);
            $rows = explode("\n", $file);
            $response = array();

            $counter = 0;
            
                        
            foreach ($rows as $row) {
                $data = str_getcsv($row);
                $counter++;
                if((($counter - 1) == 0) || (sizeof($data) != 3)){ //skip the first and last row and any other which doesn't have the required student data
                    continue;
                }
                var_dump($data);
                $name = $data[0];
                $mark = $data[1];
                $grade = $data[2];

                
                // save to the databasae
            }
            Storage::delete($path);
        }
        else{
            echo "No file";
        }
    }
}
