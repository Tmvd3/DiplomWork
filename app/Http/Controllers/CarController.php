<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;

class CarController extends Controller
{
    /**
     * Display a listing of the user's cars.
     */
    public function index()
    {
        $cars = Auth::user()->cars()->with(['parts' => function ($query) {
            $query->orderBy('status', 'desc')->orderBy('category');
        }])->get();

        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new car.
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created car in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'color' => 'nullable|string|max:50',
            'initial_mileage' => 'required|integer|min:0',
            'vin' => 'nullable|string|max:17|unique:cars',
            'license_plate' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обработка изображения
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cars', 'public');
        }

        $car = Car::create([
            'user_id' => Auth::id(),
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'color' => $validated['color'],
            'initial_mileage' => $validated['initial_mileage'],
            'current_mileage' => $validated['initial_mileage'],
            'vin' => $validated['vin'],
            'license_plate' => $validated['license_plate'],
            'image' => $imagePath ? basename($imagePath) : 'default-car.png',
        ]);

        // Создаем стандартные запчасти для нового автомобиля
        $this->createDefaultParts($car);

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно добавлен!');
    }

    /**
     * Display the specified car.
     */
    public function show(Car $car)
    {
        // Проверяем, что пользователь имеет доступ к этому автомобилю
        $this->authorize('view', $car);

        // Группируем запчасти по категориям
        $partsByCategory = $car->parts->groupBy('category');

        // Получаем статистику
        $stats = [
            'total' => $car->parts->count(),
            'danger' => $car->parts->where('status', 'danger')->count(),
            'warning' => $car->parts->where('status', 'warning')->count(),
            'good' => $car->parts->where('status', 'good')->count(),
        ];

        // Ключи категорий для выпадающего списка при добавлении запчасти
        $partCategories = ['engine', 'brakes', 'suspension', 'electrical', 'transmission', 'cooling', 'steering'];

        return view('cars.show', compact('car', 'partsByCategory', 'stats', 'partCategories'));
    }

    /**
     * Show the form for editing the specified car.
     */
    public function edit(Car $car)
    {
        $this->authorize('update', $car);

        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified car in storage.
     */
    public function update(Request $request, Car $car)
    {
        $this->authorize('update', $car);

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'color' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:17|unique:cars,vin,' . $car->id,
            'license_plate' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обработка изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если это не дефолтное
            if ($car->image !== 'default-car.png') {
                Storage::disk('public')->delete('cars/' . $car->image);
            }

            $imagePath = $request->file('image')->store('cars', 'public');
            $validated['image'] = basename($imagePath);
        }

        $car->update($validated);

        return redirect()->route('cars.show', $car)
            ->with('success', 'Информация об автомобиле обновлена!');
    }

    /**
     * Remove the specified car from storage.
     */
    public function destroy(Car $car)
    {
        $this->authorize('delete', $car);

        // Удаляем изображение, если это не дефолтное
        if ($car->image !== 'default-car.png') {
            Storage::disk('public')->delete('cars/' . $car->image);
        }

        $car->delete();

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль удален!');
    }

    /**
     * Update car mileage.
     */
    public function updateMileage(Request $request, Car $car)
    {
        $this->authorize('update', $car);

        $request->validate([
            'current_mileage' => 'required|integer|min:' . $car->current_mileage
        ]);

        $oldMileage = $car->current_mileage;
        $car->update(['current_mileage' => $request->current_mileage]);

        // Обновляем статусы всех запчастей
        foreach ($car->parts as $part) {
            $part->updateStatus();
        }

        return response()->json([
            'success' => true,
            'new_mileage' => $request->current_mileage,
            'parts' => $car->parts->map(function ($part) use ($car) {
                return [
                    'id' => $part->id,
                    'name' => $part->name,
                    'remaining' => $car->getRemainingMileageForPart($part),
                    'percentage' => $car->getPartWearPercentage($part),
                    'status' => $part->status,
                    'status_color' => $part->getStatusColor(),
                    'status_class' => $part->getStatusClass(),
                ];
            })
        ]);
    }

    /**
     * Create default parts for a new car.
     */
    private function createDefaultParts(Car $car): void
    {
        $defaultParts = [
            // Двигатель
            ['name' => 'Масло двигателя', 'category' => 'engine', 'interval' => 15000],
            ['name' => 'Фильтр масляный', 'category' => 'engine', 'interval' => 15000],
            ['name' => 'Фильтр воздушный', 'category' => 'engine', 'interval' => 30000],
            ['name' => 'Фильтр салона', 'category' => 'engine', 'interval' => 20000],
            ['name' => 'Ремень ГРМ', 'category' => 'engine', 'interval' => 100000],
            ['name' => 'Ролики ГРМ', 'category' => 'engine', 'interval' => 100000],
            ['name' => 'Помпа (водяной насос)', 'category' => 'engine', 'interval' => 100000],

            // Тормозная система
            ['name' => 'Тормозные колодки передние', 'category' => 'brakes', 'interval' => 50000],
            ['name' => 'Тормозные колодки задние', 'category' => 'brakes', 'interval' => 50000],
            ['name' => 'Тормозные диски передние', 'category' => 'brakes', 'interval' => 80000],
            ['name' => 'Тормозные диски задние', 'category' => 'brakes', 'interval' => 80000],
            ['name' => 'Тормозная жидкость', 'category' => 'brakes', 'interval' => 60000],

            // Ходовая часть
            ['name' => 'Стойки амортизаторов', 'category' => 'suspension', 'interval' => 80000],
            ['name' => 'Сайлентблоки', 'category' => 'suspension', 'interval' => 100000],
            ['name' => 'ШРУСы', 'category' => 'suspension', 'interval' => 120000],

            // Электрика
            ['name' => 'Аккумулятор', 'category' => 'electrical', 'interval' => 80000],
            ['name' => 'Свечи зажигания', 'category' => 'electrical', 'interval' => 60000],
            ['name' => 'Ремень генератора', 'category' => 'electrical', 'interval' => 80000],

            // Трансмиссия
            ['name' => 'Масло в коробке передач', 'category' => 'transmission', 'interval' => 60000],
            ['name' => 'Масло в редукторе', 'category' => 'transmission', 'interval' => 80000],

            // Охлаждение
            ['name' => 'Охлаждающая жидкость', 'category' => 'cooling', 'interval' => 40000],

            // Рулевое управление
            ['name' => 'Жидкость ГУР', 'category' => 'steering', 'interval' => 60000],
            ['name' => 'Ремень ГУР', 'category' => 'steering', 'interval' => 80000],
        ];

        foreach ($defaultParts as $partData) {
            Part::create([
                'car_id' => $car->id,
                'name' => $partData['name'],
                'category' => $partData['category'],
                'replacement_interval' => $partData['interval'],
                'last_replacement_mileage' => $car->current_mileage,
                'next_replacement_mileage' => $car->current_mileage + $partData['interval'],
                'status' => 'good',
            ]);
        }
    }

    /**
     * Get car statistics for dashboard.
     */
    public function getStatistics(Car $car)
    {
        $this->authorize('view', $car);

        $parts = $car->parts;

        $statistics = [
            'total_parts' => $parts->count(),
            'parts_by_status' => [
                'good' => $parts->where('status', 'good')->count(),
                'warning' => $parts->where('status', 'warning')->count(),
                'danger' => $parts->where('status', 'danger')->count(),
            ],
            'parts_by_category' => $parts->groupBy('category')->map->count(),
            'next_replacement' => $parts->where('status', 'danger')->first() ?
                $parts->where('status', 'danger')->first()->name : 'Нет срочных замен',
            'total_mileage' => $car->current_mileage,
            'miles_since_last_maintenance' => $car->current_mileage - $parts->min('last_replacement_mileage'),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }

    /**
     * Reset all parts for a car (mark all as replaced).
     */
    public function resetAllParts(Car $car)
    {
        $this->authorize('update', $car);

        foreach ($car->parts as $part) {
            $part->markAsReplaced();
        }

        return response()->json([
            'success' => true,
            'message' => 'Все запчасти отмечены как замененные'
        ]);
    }

    /**
     * Export car data to JSON.
     */
    public function export(Car $car)
    {
        $this->authorize('view', $car);

        $data = [
            'car' => $car->toArray(),
            'parts' => $car->parts->toArray(),
            'export_date' => now()->toDateTimeString(),
        ];

        $filename = "car-{$car->id}-{$car->brand}-{$car->model}-" . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import car data from JSON.
     */
    public function import(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json|max:2048',
        ]);

        $jsonContent = file_get_contents($request->file('json_file')->getRealPath());
        $data = json_decode($jsonContent, true);

        if (!$data || !isset($data['car'])) {
            return back()->with('error', 'Неверный формат файла JSON');
        }

        // Создаем автомобиль
        $car = Car::create([
            'user_id' => Auth::id(),
            'brand' => $data['car']['brand'] ?? 'Неизвестно',
            'model' => $data['car']['model'] ?? 'Неизвестно',
            'year' => $data['car']['year'] ?? now()->year,
            'color' => $data['car']['color'] ?? null,
            'initial_mileage' => $data['car']['initial_mileage'] ?? 0,
            'current_mileage' => $data['car']['current_mileage'] ?? 0,
            'vin' => $data['car']['vin'] ?? null,
            'license_plate' => $data['car']['license_plate'] ?? null,
            'image' => 'default-car.png',
        ]);

        // Импортируем запчасти
        if (isset($data['parts']) && is_array($data['parts'])) {
            foreach ($data['parts'] as $partData) {
                Part::create([
                    'car_id' => $car->id,
                    'name' => $partData['name'] ?? 'Неизвестная запчасть',
                    'category' => $partData['category'] ?? 'other',
                    'replacement_interval' => $partData['replacement_interval'] ?? 15000,
                    'last_replacement_mileage' => $partData['last_replacement_mileage'] ?? $car->current_mileage,
                    'next_replacement_mileage' => $partData['next_replacement_mileage'] ?? $car->current_mileage + ($partData['replacement_interval'] ?? 15000),
                    'status' => $partData['status'] ?? 'good',
                    'notes' => $partData['notes'] ?? null,
                ]);
            }
        } else {
            // Создаем стандартные запчасти, если их нет в импорте
            $this->createDefaultParts($car);
        }

        return redirect()->route('cars.show', $car)
            ->with('success', 'Автомобиль успешно импортирован!');
    }

    /**
     * Show import form.
     */
    public function showImportForm()
    {
        return view('cars.import');
    }

    /**
     * Get maintenance history for a car.
     */
    public function maintenanceHistory(Car $car)
    {
        $this->authorize('view', $car);

        $history = $car->parts()
            ->whereNotNull('last_replacement_mileage')
            ->where('last_replacement_mileage', '>', 0)
            ->orderBy('last_replacement_mileage', 'desc')
            ->get()
            ->map(function ($part) {
                return [
                    'date' => $part->updated_at->format('d.m.Y'),
                    'part_name' => $part->name,
                    'mileage' => $part->last_replacement_mileage,
                    'category' => $part->category,
                ];
            });

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Calculate maintenance cost estimation.
     */
    public function maintenanceCost(Car $car)
    {
        $this->authorize('view', $car);

        // Примерные цены на запчасти (можно вынести в базу данных или конфиг)
        $partPrices = [
            'Масло двигателя' => 3000,
            'Фильтр масляный' => 500,
            'Фильтр воздушный' => 800,
            'Фильтр салона' => 600,
            'Тормозные колодки передние' => 4000,
            'Тормозные колодки задние' => 3000,
            'Тормозные диски передние' => 8000,
            'Тормозные диски задние' => 6000,
            'Тормозная жидкость' => 1500,
            'Аккумулятор' => 5000,
            'Свечи зажигания' => 2000,
            'Ремень ГРМ' => 3000,
        ];

        $totalCost = 0;
        $partsNeedingReplacement = [];

        foreach ($car->parts as $part) {
            if ($part->status === 'danger') {
                $price = $partPrices[$part->name] ?? 1000; // Цена по умолчанию
                $totalCost += $price;
                $partsNeedingReplacement[] = [
                    'name' => $part->name,
                    'price' => $price,
                    'category' => $part->category,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'total_cost' => $totalCost,
            'parts' => $partsNeedingReplacement,
            'currency' => 'RUB'
        ]);
    }
}
