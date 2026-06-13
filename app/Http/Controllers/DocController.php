<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class DocController extends Controller
{
    public function test(){
    $template = new TemplateProcessor(storage_path('app/templates/attestation_travail.docx'));

    $template->setValue('title', 'Madame');
    $template->setValue('name', 'Test User');
    $template->setValue('employe_id', 'Test User');
    $template->setValue('grade', 'adfs');
    $template->setValue('position', 'Senior');
    $template->setValue('recruitment_date', 'Developer');

    $fileName = 'output.docx';
    $path = storage_path('app/generated/' . $fileName);

    $template->saveAs($path);

    return response()->download($path); 
    }

    public function index(){
        return view('test');
    }
}
