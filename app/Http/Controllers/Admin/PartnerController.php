<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::withCount('purchasePartners')
                           ->withSum('purchasePartners', 'share_amount')
                           ->latest()
                           ->get();
        return view('partners.index', compact('partners'));
    }

    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
        ]);

        Partner::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'notes'     => $request->notes,
            'is_active' => true,
        ]);

        return redirect()->route('partners.index')->with('success', 'Partner kamiyabi se add ho gaya!');
    }

    public function show(Partner $partner)
    {
        $partner->load(['purchasePartners.purchase.customer', 'purchasePartners.purchase.product']);
        return view('partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'notes'     => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $partner->update([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'notes'     => $request->notes,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('partners.index')->with('success', 'Partner update ho gaya!');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('partners.index')->with('success', 'Partner delete ho gaya.');
    }
}
