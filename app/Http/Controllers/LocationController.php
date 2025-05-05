<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    // ✅ Menyimpan data provinsi
    public function storeProvinces(Request $request)
    {
        $request->validate([
            'prov_name' => 'required|string|max:100'
        ]);

        try {
            DB::statement("CALL insert_province(?)", [$request->prov_name]);

            return response()->json(['message' => 'Provinsi berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    // ✅ Menyimpan data kota/kabupaten
    public function storeCities(Request $request)
    {
        $request->validate([
            'city_name' => 'required|string|max:100',
            'province_id' => 'required|exists:provinces,id'
        ]);

        try {
            DB::statement("CALL insert_city(?, ?)", [
                $request->city_name,
                $request->province_id
            ]);

            return response()->json(['message' => 'Kota berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    // ✅ Menyimpan data kecamatan
    public function storeDistricts(Request $request)
    {
        $request->validate([
            'district_name' => 'required|string|max:100',
            'city_id' => 'required|exists:cities,id'
        ]);

        try {
            DB::statement("CALL insert_district(?, ?)", [
                $request->district_name,
                $request->city_id
            ]);

            return response()->json(['message' => 'Kecamatan berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    // ✅ Menyimpan data kelurahan
    public function storeSubdistricts(Request $request)
    {
        $request->validate([
            'subdistrict_name' => 'required|string|max:100',
            'district_id' => 'required|exists:districts,id'
        ]);

        try {
            DB::statement("CALL insert_subdistrict(?, ?)", [
                $request->subdistrict_name,
                $request->district_id
            ]);

            return response()->json(['message' => 'Kelurahan berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    // ✅ Mengambil daftar provinsi
    public function getProvinces()
    {
        $provinces = DB::select("CALL get_provinces()");
        return response()->json($provinces);
    }

    // ✅ Mengambil daftar kota/kabupaten berdasarkan provinsi
    public function getCities($province_id)
    {
        // Cek apakah provinsi ada sebelum mengambil data
        if (!DB::table('provinces')->where('id', $province_id)->exists()) {
            return response()->json(['message' => 'Provinsi tidak ditemukan'], 404);
        }

        $cities = DB::select("CALL get_cities(?)", [$province_id]);
        return response()->json($cities);
    }

    // ✅ Mengambil daftar kecamatan berdasarkan kota/kabupaten
    public function getDistricts($city_id)
    {
        // Cek apakah kota ada sebelum mengambil data
        if (!DB::table('cities')->where('id', $city_id)->exists()) {
            return response()->json(['message' => 'Kota tidak ditemukan'], 404);
        }

        $districts = DB::select("CALL get_districts(?)", [$city_id]);
        return response()->json($districts);
    }

    // ✅ Mengambil daftar kelurahan berdasarkan kecamatan
    public function getSubdistricts($district_id)
    {
        // Cek apakah kecamatan ada sebelum mengambil data
        if (!DB::table('districts')->where('id', $district_id)->exists()) {
            return response()->json(['message' => 'Kecamatan tidak ditemukan'], 404);
        }

        $subdistricts = DB::select("CALL getSubdistricts(?)", [$district_id]);
        return response()->json($subdistricts);
    }
}
