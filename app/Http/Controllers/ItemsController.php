<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class ItemsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('content.customs.items')->with([
      'isNavbar' => false,
      'items' => Item::orderBy('id', 'DESC')->get()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Request $request)
  {
    $item = Item::getProductDetail($request->all()["url"]);
    if ($item->result == "error") {
      return view('content.customs.items')->with([
        'isNavbar' => false,
        'message_error' => $item->errors[0]->message . $item->errors[0]->code,
        'items' => Item::orderBy('id', 'DESC')->get()
      ]);
    }


    if ($item->result == "OK") {
      $itemObject = Item::create([
        'mer_id' => $item->data->id,
        'status' => $item->data->status,
        'name' => $item->data->name,
        'price' => $item->data->price,
        'thumbnail' => $item->data->thumbnails[0],
        'shipping_duration' => $item->data->shipping_duration->name,
      ]);

      $photos = [];

      foreach ($item->data->photos as $photo) {
        $photos[] = ['url' => $photo];
      }

      $itemObject->photos()->createMany($photos);
    }

    return redirect('/items');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Item $item)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Item $item)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Item $item)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $item = Item::findOrFail($id);
    $item->photos()->delete();
    $item->delete();
    return redirect('/items');
  }

  public function debug()
  {
    return Item::updateStatus();
  }
}
