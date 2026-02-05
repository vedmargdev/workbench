<?php

namespace Workbench\OfflineTest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class OfflineTestMarksController extends Controller
{
     public function index(Request $request){
        $query=getOfflineTest();
        if(!empty($request->classes)){
           $query=$query->filter(function($item) use ($request){
                return $item->classes_id==$request->classes;
            });
           // dd("all request is ",$request->all());
           dd("Query is ",$query);
        }
        $title="Bulk marks";
        return view(_template('user.test.marks.index'),compact('title'));
    }
}
