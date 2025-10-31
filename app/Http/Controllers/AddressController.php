<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'pin_code' => 'required|string|max:20',
        ]);

        Address::create([
            'user_id' => auth()->id(),
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pin_code' => $request->pin_code,
        ]);

        return back()->with('success', 'Address added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'pin_code' => 'required|string|max:20',
        ]);

        $address = Address::where('user_id', auth()->id())->findOrFail($id);

        $address->update([
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pin_code' => $request->pin_code,
        ]);

        return back()->with('success', 'Address updated successfully');
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();

        return back()->with('success', 'Address deleted successfully');
    }
}
