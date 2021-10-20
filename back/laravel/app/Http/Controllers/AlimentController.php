<?php

namespace App\Http\Controllers;

use App\Models\Aliment;
use Illuminate\Http\Request;
/* use Illuminate\Support\Facades\Validator;*/
use Validator;
use Illuminate\Support\Facades\DB;


class AlimentController extends Controller
{
    public function add(Request $request)
    {
          $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|max:255',
            'image',
            'status',
            'expire_date' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'category_id',
        ]);

          if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        };

        $aliment = Aliment::create([
            'name' => $request->json()->get('name'),
            'image' => $request->json()->get('image'),
            'expire_date' => $request->json()->get('expire_date'),
            'status' => $request->json()->get('status'),
            'quantity' => $request->json()->get('quantity'),
            'price' => $request->json()->get('price'),
            'category_id' => $request->json()->get('category_id'),
            'user_id' => $request->json()->get('user_id')
        ]);

        return response()->json(compact('aliment'), 201);
        
    }

    public function index($category_id)
    {
        if ($category_id == 0 ){
            $aliment = DB::table('kitchen')
                        ->get();
        }
        else {
        $aliment = DB::table('kitchen')
                        ->where('category_id', '=', $category_id)
                        ->get();
        }
        return response()->json(compact('aliment'), 201);
    }

    public function show($id)
    {
        $aliment = Aliment::find($id);

        return response()->json(compact('aliment'), 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string|max:255',
            'image',
            'status',
            'expire_date' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'category_id',
        ]);

          if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        };

        $id = $request->json()->get('id');

        $aliment = Aliment::find($id);

        $aliment->name = $request->json()->get('name');
        $aliment->image = $request->json()->get('image');
        $aliment->expire_date = $request->json()->get('expire_date');
        $aliment->status = $request->json()->get('status');
        $aliment->quantity = $request->json()->get('quantity');
        $aliment->price = $request->json()->get('price');
        $aliment->category_id = $request->json()->get('category_id');
        $aliment->save();

        return response()->json(compact('aliment'), 201);
    }

    public function delete(Request $request)
    {
        $id = $request->json()->get('id');

        $aliment = Aliment::find($id);
        $aliment->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
