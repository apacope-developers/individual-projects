<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\Availability;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Serves both the web route (/medicines → Blade view) and the API route
     * (/api/medicines → JSON).  The JSON branch is unconditional and does NOT
     * depend on wantsJson() so browser fetch() callers that omit an explicit
     * Accept header still get a proper JSON payload.
     */
    public function index(Request $request)
    {
        // API callers (route registered in api.php) always get JSON
        if ($request->is('api/*')) {
            $medicines = Medicine::query()
                ->orderBy('name')
                ->select(['id', 'name', 'price', 'image', 'category', 'manufacturer', 'strength'])
                ->paginate(50);

            return response()->json([
                'current_page'   => $medicines->currentPage(),
                'data'           => $medicines->items(),
                'first_page_url' => $request->url(),
                'from'           => $medicines->firstItem(),
                'last_page'      => $medicines->lastPage(),
                'last_page_url'  => $request->fullUrl() . '?page=' . $medicines->lastPage(),
                'links'          => $medicines->linkCollection()->toArray(),
                'next_page_url'  => $medicines->nextPageUrl(),
                'path'           => $request->url(),
                'per_page'       => $medicines->perPage(),
                'prev_page_url'  => $medicines->previousPageUrl(),
                'to'             => $medicines->lastItem(),
                'total'          => $medicines->total(),
            ]);
        }

        // Web callers (route registered in web.php) get the Blade view
        $medicines = Medicine::paginate(20);
        return view("medicines.index", compact("medicines"));
    }

    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view("medicines.show", compact("medicine"));
    }

    public function orders()
    {
        $orders = Order::where("user_id", auth()->id())->with(["medicine", "pharmacy"])->paginate(20);
        return view("medicines.orders", compact("orders"));
    }

    /**
     * GET /api/search/{name}
     * Return JSON: { found, medicineExists, results, searchName }
     */
    public function search($name)
    {
        $searchName = urldecode($name);

        // Match medicines whose name contains the search term
        $matched = Medicine::where('name', 'like', "%{$searchName}%")->get();

        if ($matched->isEmpty()) {
            return response()->json([
                'found'          => false,
                'medicineExists' => false,
                'searchName'     => $searchName,
                'results'        => [],
            ]);
        }

        // Collect pharmacy availability for the first matched medicine
        $medicine = $matched->first();
        $availabilities = Availability::with(['pharmacy', 'medicine'])
            ->where('medicine_id', $medicine->id)
            ->where('stock', '>', 0)
            ->get();

        if ($availabilities->isEmpty()) {
            return response()->json([
                'found'          => false,
                'medicineExists' => true,
                'searchName'     => $searchName,
                'results'        => [],
            ]);
        }

        $results = $availabilities->map(function (Availability $avail) use ($medicine, $searchName) {
            return [
                'medicine' => $searchName,
                'pharmacy' => $avail->pharmacy->name,
                'location' => $avail->pharmacy->location,
                'price'    => $medicine->price,
                'stock'    => $avail->stock,
            ];
        })->values()->all();

        return response()->json([
            'found'          => true,
            'medicineExists' => true,
            'searchName'     => $searchName,
            'results'        => $results,
        ]);
    }

    /**
     * GET /api/medicine/{name}/pharmacies
     * Return JSON: { found, medicine, medicine_price, pharmacies [] }
     */
    public function getPharmacies($name)
    {
        $searchName = urldecode($name);
        $medicine = Medicine::where('name', 'like', "%{$searchName}%")->first();

        if (! $medicine) {
            return response()->json(['found' => false]);
        }

        // Use the first matching medicine if the search hit multiple
        if ($medicine instanceof \Illuminate\Support\Collection) {
            $medicine = $medicine->first();
        }

        $availabilities = Availability::with('pharmacy')
            ->where('medicine_id', $medicine->id)
            ->where('stock', '>', 0)
            ->get();

        if ($availabilities->isEmpty()) {
            return response()->json(['found' => false]);
        }

        $pharmacies = $availabilities->map(function (Availability $avail) use ($medicine) {
            return [
                'pharmacy'   => $avail->pharmacy->name,
                'location'   => $avail->pharmacy->location,
                'stock'      => $avail->stock,
                'price'      => $medicine->price ?? 0,
            ];
        })->values()->all();

        return response()->json([
            'found'          => true,
            'medicine'       => $medicine->name,
            'medicine_price' => $medicine->price ?? 0,
            'pharmacies'     => $pharmacies,
        ]);
    }

    public function storeMedicine(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $medicine = Medicine::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'strength' => $request->strength,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'medicine' => $medicine]);
    }

    public function updateMedicine(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:medicines,name,' . $medicine->id,
            'price' => 'sometimes|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $medicine->update($request->only(['name', 'price', 'category', 'strength', 'description']));

        return response()->json(['success' => true, 'medicine' => $medicine]);
    }

    public function destroyMedicine(Medicine $medicine)
    {
        $medicine->delete();
        return response()->json(['success' => true]);
    }
}