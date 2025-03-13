<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|max:255',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|confirmed',
    //     ]);

    //     $validatedData['password'] = bcrypt($request->password);

    //     $user = User::create($validatedData);

    //     $accessToken = $user->createToken('authToken')->accessToken;

    //     return response()->json(['user' => $user, 'access_token' => $accessToken]);
    // }

    // public function login(Request $request)
    // {
    //     $loginData = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (!auth()->attempt($loginData)) {
    //         return response(['message' => 'Invalid credentials']);
    //     }

    //     $accessToken = auth()->user()->createToken('authToken')->accessToken;

    //     return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    // }

    public function register(Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register sucessfully.');

    }

    public function Login(Request $request):JsonResponse
    {
        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])){
            $user = auth()->user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success,'User Login successfully.');
        }
        else{
            return $this->sendError('Unauthorized.' , ['error' => 'Unauthorized']);
        }
    }

    public function index():JsonResponse
    {
        $products = Product::all();

        return $this->sendResponse(productresource::collection($products), 'products retrieved successfully');
    }

    public function store(Request $request):JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('validation Error.', $validator->errors());
        }

        $product = Product::create($input);
        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    public function show($id):JsonResponse
    {
        $product = Product::find($id);

        if(is_null($product)){
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function update(Request $request, Product $product):JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    public function destroy(Product $product):JsonResponse
    {
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }

}
