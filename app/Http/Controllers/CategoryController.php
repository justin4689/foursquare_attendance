<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use Illuminate\Http\Request;
use Flasher\Toastr\Prime\ToastrInterface;

class CategoryController extends Controller
{
    protected $toastr;

    public function __construct(ToastrInterface $toastr)
    {
        $this->toastr = $toastr;
    }

    public function index()
    {
        $categories = Category::withCount('members')->paginate(20);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        $category = Category::create($validated);

        $this->toastr->success("La catégorie \"{$category->name}\" a été ajoutée avec succès !");

        return redirect()->route('categories.index');
    }

    public function show(Category $category)
    {
        $category->load('members');
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($validated);

        $this->toastr->success("La catégorie \"{$category->name}\" a été mise à jour avec succès !");
        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        if ($category->members()->exists()) {
            $this->toastr->error('Impossible de supprimer : des membres sont liés.');
            return redirect()->route('categories.index');
        }

        $category->delete();
        $this->toastr->success('Catégorie supprimée avec succès !');
        return redirect()->route('categories.index');
    }
}
