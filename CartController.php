<?php
namespace App\Http\Controllers;
use App\Models\Product; use Illuminate\Http\Request;
class CartController extends Controller {
 public function index(){ $cart=session('cart',[]);$products=Product::whereIn('id',array_keys($cart))->get()->keyBy('id');$total=collect($cart)->sum(fn($q,$id)=>($products[$id]->price??0)*$q);return view('cart',compact('cart','products','total'));}
 public function add(Request $r,Product $product){$d=$r->validate(['quantity'=>'nullable|integer|min:1|max:10']);$c=session('cart',[]);$c[$product->id]=min(10,($c[$product->id]??0)+($d['quantity']??1));session(['cart'=>$c]);return back()->with('success','Added to cart');}
 public function remove(Product $product){$c=session('cart',[]);unset($c[$product->id]);session(['cart'=>$c]);return back();}
}
