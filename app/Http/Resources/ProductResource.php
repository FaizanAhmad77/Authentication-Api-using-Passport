<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

class ProductResource extends JsonResource
{
   public function toArray($request)
   {
       return [
           'id' => $this->id,
           'name' => $this->name,
           'detail' => $this->detail,
           'created_at' => $this->created_at->format('d/m/Y'),
           'updated_at' => $this->updated_at->format('d/m/Y'),
       ];
   }
}
