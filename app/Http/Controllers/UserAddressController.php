<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\UserAddress;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    /**
     * Show the list of address.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $provinces = Province::all();

        return view('address.index', compact('provinces'));
    }

    /**
     * Show the form to create a new address.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $provinces = Province::all();

        return view('address.create', compact('provinces'));
    }

    /**
     * Store the newly created address in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'full_address' => 'required',
            'postal_code' => 'required|numeric',
        ]);

        // Store the user address
        UserAddress::create([
            'user_id' => Auth::id(),
            'id_province' => $request->province_id,
            'id_regency' => $request->regency_id,
            'id_district' => $request->district_id,
            'id_village' => $request->village_id,
            'full_address' => $request->full_address,
            'postal_code' => $request->postal_code,
        ]);

        return redirect()->route('address.index')->with('success', 'Alamat berhasil disimpan.');
    }

    /**
     * Show the form to edit an existing address.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $address = UserAddress::find($id);
        $provinces = Province::all();
        $regencies = Regency::where('province_id', $address->id_province)->get();
        $districts = District::where('regency_id', $address->id_regency)->get();
        $villages = Village::where('district_id', $address->id_district)->get();

        // Return the edit view with address, provinces, and regencies
        return view('address.edit', compact('address', 'provinces', 'regencies', 'districts', 'villages'));
    }

    /**
     * Update an existing address in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserAddress  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, UserAddress $address)
    {
        // Validate input
        $request->validate([
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'full_address' => 'required',
            'postal_code' => 'required|numeric',
        ]);

        // Update the address with the same fields as store method
        $address->update([
            'id_province' => $request->province_id,
            'id_regency' => $request->regency_id,
            'id_district' => $request->district_id,
            'id_village' => $request->village_id,
            'full_address' => $request->full_address,
            'postal_code' => $request->postal_code,
        ]);

        return redirect()->route('address.index')->with('success', 'Alamat berhasil diperbarui');
    }

    /**
     * Delete an existing address from the database.
     *
     * @param  \App\Models\UserAddress  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UserAddress $address)
    {
        // Delete the address
        $address->delete();

        return redirect()->route('address.index')->with('success', 'Alamat berhasil dihapus');
    }
}
