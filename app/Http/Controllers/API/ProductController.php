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
        try {
            $products = Product::paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Products fetch successfully',
                'products' => $products,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function singleProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Product found successfully',
                'product' => $product,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Product not found',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {
            $input = $request->all();
            $carts = Cart::where('user_id', $input['user_id'])->with(['product'])->get();
            $cartCount = Cart::where('user_id', $input['user_id'])->count();

            return response()->json([
                'success'=> true,
                'message' => 'Cart data fetch successfully',
                'cart_count' => $cartCount,
                'carts' => $carts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Product not found',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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

    // return response()->json([
    //     'error' => 'Name is required'
    // ], Response::HTTP_BAD_REQUEST);
    // HTTP_OK = 200;
    // HTTP_CREATED = 201;
    // HTTP_BAD_REQUEST = 400;
    // HTTP_UNAUTHORIZED = 401;
    // HTTP_FORBIDDEN = 403;
    // HTTP_NOT_FOUND = 404;
    // HTTP_REQUEST_TIMEOUT = 408;
}