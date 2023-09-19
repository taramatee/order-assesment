<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function registration() {
        return view('auth.registration');
    }

    public function postRegistration(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
          ]);

        return redirect("orders")->withSuccess('Successfully loggedin');
    }

    public function index() {
        return view('auth.login');
    }


    public function validateLogin(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('orders')
                        ->withSuccess('Successfully loggedin');
        }
        return redirect("login")->withSuccess('invalid credentials');
    }

    public function dashboard() {
        if(Auth::check()){
            return view('orders');
        }
        return redirect("login")->withSuccess('Opps! plwase login first');
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

    public function getProduct(Request $request) {

        Log::info('AuthController::getProduct()');
        Log::info($request->all());
        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // // Page Order
        // $orderColumnIndex = $request->order[0]['column'] ?? '0';
        // $orderBy = $request->order[0]['dir'] ?? 'desc';

        // // Search
        $search = $request->search;

        $query = Order::select(
            'orders.OrderID',
            'orders.OrderDate',
            'customers.CustomerName',
            'suppliers.SupplierName',
            'categories.CategoryName',
            'products.ProductName',
            'employees.FirstName',
            DB::raw('SUM(orderdetails.Quantity) as total_quantity'),
            DB::raw('SUM(products.Price) as total_amount'),
            DB::raw('CONCAT(employees.FirstName, " ", employees.LastName) as FullName'),
            DB::raw('CONCAT(customers.CustomerName, " ", customers.ContactName) as CustomerNameContact')
        )
        ->join('customers', 'orders.CustomerID', '=', 'customers.CustomerID')
        ->join('employees', 'orders.EmployeeID', '=', 'employees.EmployeeID')
        ->join('orderdetails', 'orders.OrderID', '=', 'orderdetails.OrderID')
        ->join('products', 'orderdetails.ProductID', '=', 'products.ProductID')
        ->join('categories', 'products.CategoryID', '=', 'categories.CategoryID')
        ->join('suppliers', 'products.SupplierID', '=', 'suppliers.SupplierID')
        ->where(function ($query) use ($search) {
            // Adjust the columns and search logic as needed
            $query->orWhere('orders.OrderID', 'like', "%$search%")
                ->orWhere('orders.OrderDate', 'like', "%$search%")
                ->orWhere('customers.CustomerName', 'like', "%$search%")
                ->orWhere('customers.ContactName', 'like', "%$search%")
                ->orWhere('suppliers.SupplierName', 'like', "%$search%")
                ->orWhere('categories.CategoryName', 'like', "%$search%")
                ->orWhere('products.ProductName', 'like', "%$search%")
                ->orWhere('employees.FirstName', 'like', "%$search%")
                ->orWhere('employees.LastName', 'like', "%$search%");
        })
        ->groupBy('orders.OrderID', 'orders.OrderDate', 'customers.CustomerName', 'customers.ContactName', 'suppliers.SupplierName', 'categories.CategoryName', 'products.ProductName', 'employees.FirstName', 'employees.LastName')
        ->orderBy('orders.OrderID', 'asc');

        Log::info('$query->count(): '.$query->get()->count());

        $recordsFiltered = $recordsTotal = $query->get()->count();
        $order_details = $query->skip($skip)->take($pageLength)->get();

        Log::info('order_details');
        Log::info(json_encode($order_details));

        return response()->json([
            "draw" => $request->draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $order_details
            ], 200);

    }
}
