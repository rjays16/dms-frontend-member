<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationType;
use App\Models\MembershipType;
use App\Models\PRCNumber;
use App\Models\PCRChapter;
use App\Models\NonMemberType;

class FieldController extends Controller
{
    public function getAllRegistrationType()
    {
        return RegistrationType::all();
    }

    public function getMembershipTypes()
    {
        return MembershipType::all();
    }

    public function getAllNonMemberType()
    {
        return NonMemberType::all();
    }

    public function getAllChapters()
    {
        return PCRChapter::all();
    }

}
