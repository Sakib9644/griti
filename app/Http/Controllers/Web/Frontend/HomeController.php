<?php

namespace App\Http\Controllers\Web\Frontend;

use App\Enums\CacheEnum;
use App\Enums\PageEnum;
use App\Helpers\Caching;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\Post;
use App\Models\Product;
use App\Models\SocialLink;
use App\Models\User;
use Modules\Portfolio\Models\Project;
use Modules\Portfolio\Models\Type;
use App\Traits\CMSData;

class HomeController extends Controller
{
    use CMSData;
    public function index()
    {
        return view('auth.login');
    }

    public function post($slug)
    {
        $cms = [
            'home' => CMS::where('page', PageEnum::HOME)->where('status', 'active')->get(),
            'common' => CMS::where('page', PageEnum::COMMON)->where('status', 'active')->get(),
        ];
        $post = Post::where('slug', base64_decode($slug))->where('status', 'active')->firstOrFail();
        return view('frontend.layouts.post', compact('cms', 'post'));
    }

    public function user()
    {

        $users = User::latest()->paginate(10);

        return view('backend.layouts.UserDetails.index', compact('users'));
    }
}
