<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\PaginatedResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (-1 === (int)$request->input('page')) {
            return User::all();
            return (object)User::all();
        }
        return PaginatedResource::collection(User::paginate());
    }

    public function show($id)
    {
        return User::find($id);
    }

    public function store(Request $request)
    {
        $data = $request->only('first_name', 'last_name', 'email', 'is_influencer') +
        ['password' => Hash::make($request->input('password'))];

        $user = User::create($data);

        return response($user, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
