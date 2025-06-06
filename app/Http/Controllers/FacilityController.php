<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        // Call the stored procedure to fetch all facilities
        $facilities = DB::select('CALL get_facilities()');

        // Define the page size and current page
        $perPage = 10;
        $page = $request->get('page', 1); // Get the current page from the request, default to page 1

        // Convert to collection for pagination
        $facilities = collect($facilities);

        // Paginate the data manually (Laravel Pagination)
        $paginatedFacilities = new \Illuminate\Pagination\LengthAwarePaginator(
            $facilities->forPage($page, $perPage), // Get the current page items
            $facilities->count(), // Total count of all facilities
            $perPage, // Number of items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Maintain the query parameters
        );

        return view('admin.fasilitas', compact('paginatedFacilities'));
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'facility_name' => 'required|string|max:255|unique:facilities,facility_name',  // Ensure facility_name is unique
            'icon' => 'required|string|max:255',
        ]);

        // Start a transaction in case we have multiple queries in the future
        DB::beginTransaction();

        try {
            // Call the stored procedure to insert a new facility
            DB::select('CALL insert_facility(?, ?)', [
                $request->facility_name,
                $request->icon
            ]);

            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Error inserting facility: ' . $e->getMessage());

            return redirect()->route('admin.facilities.index')->with('error', 'Terjadi kesalahan saat menambahkan fasilitas.');
        }
    }

    public function edit($id)
    {
        // Call the stored procedure to get a specific facility by ID
        $facility = DB::select('CALL get_facility_by_id(?)', [$id]);

        return response()->json($facility ? $facility[0] : null);  // Return the first result
    }

    public function update(Request $request, $id)
    {
        // Validate incoming data
        $request->validate([
            'facility_name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
        ]);

        // Call the stored procedure to update the facility
        DB::select('CALL update_facility(?, ?, ?)', [
            $id,
            $request->facility_name,
            $request->icon
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Call the stored procedure to delete the facility
        DB::select('CALL delete_facility(?)', [$id]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus!');
    }
}
