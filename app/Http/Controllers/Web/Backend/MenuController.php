<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Subcategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menus = Menu::query()->latest()->get();
        $groupedMenus = $menus->groupBy('parent_id');
        return view("backend.layouts.menu.index", compact('menus', 'groupedMenus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('backend.layouts.menu.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'             => 'required|max:250',
            'content'           => 'required|string',
            'thumbnail'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'category_id'       => 'required|exists:categories,id',
            'subcategory_id'    => 'required|exists:subcategories,id',
            'images'            => 'nullable|array|max:3',
            'images.*'          => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();

            $post = new Menu();

            $post->user_id = auth('web')->user()->id;

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'post', time() . '_' . getFileName($request->file('thumbnail')));
            }

            $post->slug = Helper::makeSlug(Menu::class, $data['title']);

            $post->title = $data['title'];
            $post->thumbnail = $data['thumbnail'];
            $post->content = $data['content'];
            $post->category_id = $data['category_id'];
            $post->subcategory_id = $data['subcategory_id'];
            $post->save();

            if (isset($request['images']) && count($request['images']) > 0 && count($request['images']) <= 3) {
                foreach ($request['images'] as $image) {
                    $imageName = 'images_' . Str::random(10);
                    $image = Helper::fileUpload($image, 'post', $imageName);
                    Image::create(['post_id' => $post->id, 'path' => $image]);
                }
            } else {
                session()->put('t-error', 'Please select at least one image and maximum 3 images');
            }

            session()->put('t-success', 'post created successfully');
        } catch (Exception $e) {

            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route('admin.menu.index')->with('t-success', 'post created successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|max:50',
            'parent_id'    => 'nullable|exists:menus,id|not_in:' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();

            $post = Menu::findOrFail($id);

            if ($request->hasFile('thumbnail')) {
                $validate['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'post', time() . '_' . getFileName($request->file('thumbnail')));
            }

            $post->name = $data['name'];
            $post->parent_id = $data['parent_id'] ?? null;
            $post->save();

            session()->put('t-success', 'updated successfully');

        } catch (Exception $e) {

            session()->put('t-error', $e->getMessage());
        }

        return redirect()->back()->with('t-success', 'updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            session()->put('t-success', 'Menu deleted successfully');
        } catch (Exception $e) {
            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route('admin.menu.index');
    }

    public function status(int $id): JsonResponse
    {
        $data = Menu::findOrFail($id);
        if (!$data) {
            return response()->json([
                'status' => 't-error',
                'message' => 'Item not found.',
            ]);
        }
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json([
            'status' => 't-success',
            'message' => 'Your action was successful!',
        ]);
    }

}
