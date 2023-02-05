<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;


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
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(strcasecmp($ext, "csv") != 0){
                return ("File must be a CSV file");
            }
            $file = Storage::disk('local')->get($path);
            $rows = explode("\n", $file);
            $response = array();
            $counter = 0;
            foreach ($rows as $row) {
                $data = str_getcsv($row);
                $counter++;
                if((($counter - 1) == 0) || (sizeof($data) != 3)){ //skip the first and last row and any other which doesn't have the required student data
                    
                }
                else{
                    $data[2] = $this->gradeStudent($data[1]);   
                }
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
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(strcasecmp($ext, "csv") != 0){  //make sure uploaded file is of correct extension
                return ("File must be a CSV file");
            }

            $file = Storage::disk('local')->get($path);
            $rows = explode("\n", $file);
            $response = array();

            $gradecount = array(0, 0, 0, 0);  //I will use this array to count how many students are in whick grade

            $counter = 0;
            
                        
            foreach ($rows as $row) {
                $data = str_getcsv($row);
                $counter++;
                if((($counter - 1) == 0) || (sizeof($data) != 3)){ //skip the first and last row and any other which doesn't have the required student data
                    continue;
                }
                // var_dump($data);
                $name = $data[0];
                $mark = $data[1];
                $grade = $data[2];

                $grade = $this->gradeStudent($mark);

                //update the grade count
                if(strcasecmp($grade, "Grade 1")){
                    $gradecount[0]++;
                }
                else if(strcasecmp($grade, "Grade 2")){
                    $gradecount[1]++;
                }
                else if(strcasecmp($grade, "Grade 3")){
                    $gradecount[2]++;
                }
                else if(strcasecmp($grade, "Grade 4")){
                    $gradecount[3]++;
                }

                // save to the databasae
                DB::insert("insert into student_marks values(DEFAULT, '{$name}', '{$mark}', '{$grade}')");
                echo "Student Data Uploaded Successfully ";


                // Send an information email
                // $this->sendEmail($gradecount);     //uncomment this after configuring smtp
            }
            Storage::delete($path);
        }
        else{
            echo "No file";
        }
    }


                                        // student grading
    public function gradeStudent($mark){
        $mark = (int) $mark;

        if(($mark >= 0) && ($mark <= 39)){
            $grade = "Grade 4";
        }
        else if(($mark >= 40) && ($mark <= 59)){
            $grade = "Grade 3";
        }
        else if(($mark >= 60) && ($mark <= 79)){
            $grade = "Grade 2";
        }
        else if(($mark >= 80) && ($mark <= 100)){
            $grade = "Grade 1";
        }
        else{
            $grade = "Grade undefined";
        }
        
        return $grade;
    }


    public function sendEmail($gradecount){
        $text = "Dear Ivan, A file upload of student marks has been made. The summary is below <br /> 
                 Grade 1: {$gradecount[0]} <br />
                 Grade 2: {$gradecount[1]} <br />
                 Grade 3: {$gradecount[2]} <br />
                 Grade 4: {$gradecount[3]} <br />
                <br />
                Thank you
                }";

                $to = "hanningtonkizza@gmail.com";
                $subject = "Test";
                
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                
                // More headers
                $headers .= 'From: <webmaster@example.com>' . "\r\n";
                $headers .= 'Cc: myboss@example.com' . "\r\n";
                
                mail($to,$subject,$text,$headers);
    }

}
