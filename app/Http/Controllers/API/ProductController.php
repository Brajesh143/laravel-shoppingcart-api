<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return response()->json([
            'status'=> 200,
            'products'=>$products,
        ]);
    }

    public function singleProduct($id)
    {
        $product = Product::find($id);
        return response()->json([
            'status' => 200,
            'product' => $product,
        ]);
    }

    public function addToCart(Request $request)
    {
        $cart = Cart::firstOrNew(['product_id' => $request->product_id, 'user_id' => $request->user_id]);
        $cart->product_id = $request->product_id;
        $cart->qty = $cart->qty + $request->qty;
        $cart->user_id = $request->user_id;
        $cart->save();

        return response()->json([
            'status' => 200,
            'message' => 'Product added to cart',
            'cart_count' => Cart::where('user_id', $request->user_id)->count(),
            'cart' => Cart::where('user_id', $request->user_id)->with(['product'])->get(),
        ]);
    }

    public function cartList(Request $request)
    {
        $input = $request->all();
        $carts = Cart::where('user_id', $input['user_id'])->with(['product'])->get();
        $cartCount = Cart::where('user_id', $input['user_id'])->count();

        return response()->json([
            'status'=> 200,
            'cart_count' => $cartCount,
            'carts' => $carts,
        ]);
    }

    public function cartUpdate(Request $request, $id)
    {
        $inputData = $request->all();
        $cart = Cart::find($id);
        $cart->qty = $inputData['qty'];
        $cart->save();

        return response()->json([
            'status'=> 200,
            'message' => "Cart updated successfully",
            'carts' => $cart,
        ]);
    }

    public function cartDelete($id)
    {
        $cart = Cart::destroy($id);

        return response()->json([
            'status'=> 200,
            'message' => "Cart has deleted",
        ]);
    }

    public function getCategory()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 200,
            'categories' => $categories,
        ]);
    }

    public function productSearch(Request $request)
    {
        $key = $request->all()['keyword'];

        $products = Product::where('name', 'like', '%'.$key.'%')->paginate(10);

        return response()->json([
            'status'=> 200,
            'products'=>$products,
        ]);
    }
}