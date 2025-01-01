<?php

namespace App\Http\Controllers;

use App\Http\Traits\ListingApiTrait;
use App\Http\Traits\ManageFiles;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ListingApiTrait, ManageFiles;

    public function list(Request $request)
    {
        $this->ListingValidation();

        $user = User::query();

        $user = $this->filterSortPagination($user);

        return ok(__('strings.user.list'), [
            'users' => $user['query']->get(),
            'count' => $user['count']
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $request->validate([
            'first_name'        =>  'required|string|min:3|max:50',
            'last_name'         =>  'required|string|min:3|max:50',
            'username'          =>  'required|string|min:3|max:50|unique:users,username,' . $user->id,
            'email'             =>  'required|email|unique:users,email,' . $user->id,
            'mobile'            =>  'nullable',
            'city'              =>  'nullable|string',
            'profile_image'     =>  'nullable|mimes:jpg,jpeg,png|max:10240',
            'cover_image'       =>  'nullable|mimes:jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('profile_image')) {

            $this->deleteFile($user->profile_image);
            $file = $request->profile_image;
            $directory = 'profile/' . auth()->user()->id;
            $profile_image = $this->uploadFile($file, $directory);
            $user->update([
                'profile_image'  =>  $profile_image,
            ]);
        }

        $user->update($request->only('first_name', 'last_name', 'username', 'email', 'mobile', 'city'));

        return ok(__('strings.user.update_profile'), [
            'user' => $user,
        ]);
    }

    public function imageUpload()
    {
        return ok("ok");
    }

    public function userPostList(Request $request)
    {
        // Validate the request (ensure this method handles validation properly)
        $this->ListingValidation();

        // Retrieve the authenticated user's posts as a query
        $postsQuery = Auth::user()->posts()->with('attachments');

        // Apply status filter based on the request
        if ($request->filled('post_status') && in_array($request->post_status, ['D', 'P'])) {
            $postsQuery->where('status', $request->post_status);
        }

        // Apply additional filters, sorting, and pagination
        $posts = $this->filterSortPagination($postsQuery);

        // Return the result with posts and count
        return ok(__('strings.post.user_post_list'), [
            'posts' => $posts['query']->get(),
            'count' => $posts['count']
        ]);
    }
}
