<?php
// app\Http\Controllers\Api\OrderApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->orders()->with('products')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:0'
        ]);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'total'   => $request->total,
            'status'  => 'pending'
        ]);

        return response()->json($order, 201);
    }
}
