<?php
namespace App\Http\Controllers;
use App\Models\Product; use Illuminate\Http\Request;
class StoreController extends Controller {
 public function index(Request $r){$products=Product::where('is_active',1)->when($r->q,fn($q)=>$q->where('name','like','%'.$r->q.'%'))->latest()->paginate(12);return view('store',compact('products'));}
 public function show(Product $product){abort_unless($product->is_active,404);return view('product',compact('product'));}
}
