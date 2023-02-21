<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PCRMembersImport;
use App\Imports\UsersImport;
use App\Imports\PCRLifetimeMemberImport;

class UsersImportController extends Controller
{
    public function show() {

    }

    public function store(Request $request) {
        $file = $request->file('file');

        Excel::import(new PCRMembersImport, $file);

        return back()->withStatus('Excel file imported successfully');
    }

    public function storeLifeMembers(Request $request) {
        $file = $request->file('file');

        Excel::import(new PCRLifetimeMemberImport, $file);

        return back()->withStatus('Excel file imported successfully');
    }

    public function store2(Request $request) {
        try {
            $file = $request->file('file');

            Excel::import(new UsersImport, $file);
    
            return response()->json([
                'message' => 'Success! Users Imported'
            ]);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => $e
            ]);
        }

    }

}
