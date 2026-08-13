<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->orderBy('sort_order')->get();
        return view('faqs.index', compact('faqs'));
    }
}
