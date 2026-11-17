<?php
namespace App\Http\Controllers;
use App\Models\Product;use App\Models\Order;use Illuminate\Http\Request;use Illuminate\Support\Str;
class AdminController extends Controller {
 public function index(){ $products=Product::latest()->paginate(25);$orders=Order::latest()->take(10)->get();return view('admin',compact('products','orders'));}
 public function products(){ $products=Product::latest()->paginate(25); return view('admin',compact('products') + ['orders'=>collect()]);}\n public function create(){return view('product-form');}
 public function store(Request $r){$d=$r->validate(['name'=>'required|string|max:180','sku'=>'nullable|string|max:80','description'=>'nullable|string','price'=>'required|numeric|min:0','stock_qty'=>'required|integer|min:0','supplier_name'=>'nullable|string|max:160','image'=>'nullable|image|max:2048','is_dropship'=>'nullable|boolean']);$d['slug']=Str::slug($d['name']).'-'.Str::lower(Str::random(4));$d['is_active']=true;$d['is_dropship']=$r->boolean('is_dropship');if($r->hasFile('image'))$d['image_path']=$r->file('image')->store('products','public');Product::create($d);return redirect()->route('admin.home');}
 public function edit(Product $product){return view('product-form',compact('product'));}
 public function update(Request $r,Product $product){$d=$r->validate(['name'=>'required|string|max:180','sku'=>'nullable|string|max:80','description'=>'nullable|string','price'=>'required|numeric|min:0','stock_qty'=>'required|integer|min:0','supplier_name'=>'nullable|string|max:160','image'=>'nullable|image|max:2048','is_dropship'=>'nullable|boolean']);$d['is_dropship']=$r->boolean('is_dropship');if($r->hasFile('image'))$d['image_path']=$r->file('image')->store('products','public');$product->update($d);return redirect()->route('admin.home');}
 public function destroy(Product $product){$product->update(['is_active'=>false]);return back();}
 public function orders(){ $orders=Order::latest()->paginate(30);return view('admin-orders',compact('orders'));}
 public function updateOrder(Request $r,Order $order){$d=$r->validate(['status'=>'required|in:pending,confirmed,processing,shipped,delivered,cancelled','payment_status'=>'required|in:unpaid,pending,paid,refunded','supplier_name'=>'nullable|string|max:160','supplier_ref'=>'nullable|string|max:120','tracking_no'=>'nullable|string|max:120']);$order->update($d);return back()->with('success','Order updated');}
}
