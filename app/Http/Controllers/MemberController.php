<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Category;
use Illuminate\Http\Request;
use Flasher\Toastr\Prime\ToastrInterface;

class MemberController extends Controller
{
    protected $toastr;

    public function __construct(ToastrInterface $toastr)
    {
        $this->toastr = $toastr;
    }

    public function index()
    {
        $members = Member::with('category')->paginate(20);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('members.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $member = Member::create($validated);

        $this->toastr->success("Le membre {$member->first_name} {$member->last_name} a été ajouté avec succès !");

        return redirect()->route('members.index');
    }

    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $member = Member::create($validated);

        $this->toastr->success("Bienvenue {$member->first_name} {$member->last_name} ! Inscription réussie.");

        return redirect()->route('attendance.index');
    }

    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $categories = Category::all();
        return view('members.edit', compact('member', 'categories'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $member->update($validated);

        $this->toastr->success("Le membre {$member->first_name} {$member->last_name} a été mis à jour avec succès !");

        return redirect()->route('members.index');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        $this->toastr->success('Membre supprimé avec succès !');
        return redirect()->route('members.index');
    }
}
