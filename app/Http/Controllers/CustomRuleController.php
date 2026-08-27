<?php

namespace App\Http\Controllers;

use App\Models\CustomRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CustomRuleController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-rules');

        $rules = CustomRule::with('creator')->latest()->paginate(15);

        return view('custom-rules.index', compact('rules'));
    }

    public function create()
    {
        Gate::authorize('manage-rules');

        return view('custom-rules.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-rules');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'prompt_instruction' => 'required|string|max:2000',
        ]);

        CustomRule::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('custom-rules.index')
            ->with('success', 'Custom rule created successfully.');
    }

    public function edit(CustomRule $customRule)
    {
        Gate::authorize('manage-rules');

        return view('custom-rules.edit', ['rule' => $customRule]);
    }

    public function update(Request $request, CustomRule $customRule)
    {
        Gate::authorize('manage-rules');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'prompt_instruction' => 'required|string|max:2000',
            'is_active' => 'boolean',
        ]);

        $customRule->update($validated);

        return redirect()->route('custom-rules.index')
            ->with('success', 'Custom rule updated successfully.');
    }

    public function destroy(CustomRule $customRule)
    {
        Gate::authorize('manage-rules');

        $customRule->delete();

        return redirect()->route('custom-rules.index')
            ->with('success', 'Custom rule deleted successfully.');
    }
}
