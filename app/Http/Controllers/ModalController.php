<?php

namespace App\Http\Controllers;

use App\Support\ViewGuard;
use Illuminate\Http\Request;

class ModalController extends Controller
{

    public function common_view_function(Request $request, $view_path = "")
    {
        ViewGuard::authorize((string) $view_path);

        $page_data = array();
        foreach ($request->all() as $key => $value) :
            $page_data[$key] = $value;
        endforeach;

        return view($view_path, $page_data);
    }
}
