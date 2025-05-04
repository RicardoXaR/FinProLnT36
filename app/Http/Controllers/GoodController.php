<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoodController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index()
    {
        $goods = Good::with('category')->latest()->paginate(12);
        return view('goods.index', compact('goods'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('goods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:5|max:80',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'photo' => 'required|image|max:2048',
        ]);

        $photoPath = $request->file('photo')->store('goods_photos', 'public');

        Good::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('goods.index')->with('success', 'Good created successfully.');
    }

    public function edit(Good $good)
    {
        $categories = Category::all();
        return view('goods.edit', compact('good', 'categories'));
    }

    public function update(Request $request, Good $good)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:5|max:80',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ];

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($good->photo_path);
            $data['photo_path'] = $request->file('photo')->store('goods_photos', 'public');
        }

        $good->update($data);

        return redirect()->route('goods.index')->with('success', 'Good updated successfully.');
    }

    public function destroy(Good $good)
    {
        Storage::disk('public')->delete($good->photo_path);
        $good->delete();

        return redirect()->route('goods.index')->with('success', 'Good deleted successfully.');
    }
}
