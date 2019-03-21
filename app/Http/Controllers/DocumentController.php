<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Document
};
class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Document::orderBy('id','desc')->get();
    }

     /**
     * Download upload files/attachments of ncn 
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment($fileId)
    {
        $documentFile = Document::findOrfail($fileId);

        ob_end_clean();
        return response()->download(storage_path("app/public/".$documentFile->path), $documentFile->file_name);
    }
}
