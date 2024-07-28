<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
  use HasFactory;

  protected $guarded = [];

  public static function updateStatus()
  {
    $status = 'on_sale';

    foreach (Item::where('status', '=', $status)->get() as $item) {
      $detail = self::getProductDetail($item->mer_id);
      if ($detail->data->status != $item->status) {
        $item->status = $detail->data->status;
        $item->save();
      }
      sleep(3);
    }
  }

  public static function getProductDetail($url)
  {
    $array = explode("/", $url);
    $itemId = end($array);

    $output = shell_exec(
      "curl --location 'https://api.mercari.jp/items/get?id=$itemId"
      . "&include_item_attributes=true&include_product_page_component=true&include_non_ui_item_attributes=true&include_donation=true&include_offer_like_coupon_display=false&include_offer_coupon_display=true' "
      . " --header 'dpop: eyJ0eXAiOiJkcG9wK2p3dCIsImFsZyI6IkVTMjU2IiwiandrIjp7ImNydiI6IlAtMjU2Iiwia3R5IjoiRUMiLCJ4Ijoic1g3Mi1McjJ0SFhQRWQwYmdqRWE0NFZOaGQ2WlpPZUZ6MkxsREQ4eU1pTSIsInkiOiJEVnVuSWZ6MXNIX0x2Wmkwc3MxTHp4VVc1NEN3cVk3YkRyM3M5Wlh3ZnBFIn19.eyJpYXQiOjE3MjIxMzgwOTIsImp0aSI6Ijg3NDU0ZmRiLTJkNWEtNDVkOC04ZGViLTZmMTU0YWRlYjg1YSIsImh0dSI6Imh0dHBzOi8vYXBpLm1lcmNhcmkuanAvaXRlbXMvZ2V0IiwiaHRtIjoiR0VUIiwidXVpZCI6IjI3YTA0OGYwLTVhMjMtNDc5Zi05MWZmLTZkN2FkYjE3OTYyZSJ9.8RU_y5F0r5tSrdGmtrLQ7lWoEpnfkV3Rp6rpowL06mm7wJcz0OGO6fPGV3iMAnBNdl2ZHxqVeRnPM-_-h4UlIQ'"
      . " --header 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36' "
      . " --header 'x-platform: web'"
    );

    return json_decode($output);
  }

  public function photos(): HasMany
  {
    return $this->hasMany(ItemPhoto::class);
  }
}
