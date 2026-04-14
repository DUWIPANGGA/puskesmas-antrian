<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthTip;
use Illuminate\Http\Request;

class HealthTipController extends Controller
{
    public function index()
    {
        $tips = HealthTip::orderBy('order')->orderByDesc('created_at')->get();
        return view('admin.health_tips.index', compact('tips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'tip' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        HealthTip::create($request->all());

        return back()->with('success', 'Health tip created successfully');
    }

    public function update(Request $request, HealthTip $healthTip)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'icon' => 'required|string|max:50',
            'tip' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $healthTip->update($request->all());

        return back()->with('success', 'Health tip updated successfully');
    }

    public function destroy(HealthTip $healthTip)
    {
        $healthTip->delete();
        return back()->with('success', 'Health tip deleted successfully');
    }

    public function toggleStatus(HealthTip $healthTip)
    {
        $healthTip->update(['is_active' => !$healthTip->is_active]);
        return back()->with('success', 'Status updated');
    }
}
