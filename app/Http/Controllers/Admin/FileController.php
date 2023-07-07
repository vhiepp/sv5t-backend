<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class FileController extends Controller
{
    public function upload(Request $request) {

        $folder = "uploads/" . date('Y/m/d');
        $fileName = date('H-i') . '-' . $request->file('upload')->getClientOriginalName();
        $url = $request->file('upload')->move($folder, $fileName);
        
        $path = env('APP_URL') . '/' . $folder . '/' . $fileName;

        if ($request->input('res_filename')) {
            return response(["fileUrl" => $folder . '/' . $fileName]);
        }

        return response([
                    "fileName" => $fileName,
                    "uploaded" => 1,
                    "url" => $path
                ]);
    }

    public function delete(Request $request) {
        unlink($request->input('fileUrl'));
        return response('OK');
    }
}