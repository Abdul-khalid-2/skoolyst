<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\School;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query()->withCount('couponUsage');

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $coupons = $query->latest()->paginate(15)->withQueryString();

        return view('dashboard.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('dashboard.coupons.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($request, $data) {
            $coupon = Coupon::create([
                'uuid' => (string) Str::uuid(),
                'code' => strtoupper($data['code']),
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'minimum_order_amount' => $data['minimum_order_amount'] ?? null,
                'maximum_discount_amount' => $data['maximum_discount_amount'] ?? null,
                'usage_limit' => $data['usage_limit'] ?? null,
                'usage_per_customer' => $data['usage_per_customer'] ?? null,
                'valid_from' => $this->toUtc($data['valid_from'] ?? null),
                'valid_until' => $this->toUtc($data['valid_until'] ?? null),
                'scope' => $data['scope'],
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            $this->syncApplicables($coupon, $request);
        });

        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['applicables.applicable', 'couponUsage.user', 'couponUsage.order']);
        $coupon->loadCount('couponUsage');

        return view('dashboard.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $coupon->load('applicables');

        $selected = [
            'shops' => $coupon->shops()->pluck('shops.id')->all(),
            'schools' => $coupon->schools()->pluck('schools.id')->all(),
            'products' => $coupon->products()->pluck('products.id')->all(),
            'categories' => $coupon->categories()->pluck('product_categories.id')->all(),
        ];

        return view('dashboard.coupons.edit', array_merge($this->formData(), compact('coupon', 'selected')));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateData($request, $coupon->id);

        DB::transaction(function () use ($request, $data, $coupon) {
            $coupon->update([
                'code' => strtoupper($data['code']),
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'minimum_order_amount' => $data['minimum_order_amount'] ?? null,
                'maximum_discount_amount' => $data['maximum_discount_amount'] ?? null,
                'usage_limit' => $data['usage_limit'] ?? null,
                'usage_per_customer' => $data['usage_per_customer'] ?? null,
                'valid_from' => $this->toUtc($data['valid_from'] ?? null),
                'valid_until' => $this->toUtc($data['valid_until'] ?? null),
                'scope' => $data['scope'],
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);

            $this->syncApplicables($coupon, $request);
        });

        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        return back()->with('success', 'Coupon status updated.');
    }

    /**
     * Convert a datetime-local value (entered in the business timezone) to a
     * UTC Carbon instance for storage. The app stores timestamps in UTC.
     */
    private function toUtc(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        return Carbon::parse($value, config('shop.timezone'))
            ->setTimezone(config('app.timezone', 'UTC'));
    }

    private function validateData(Request $request, ?int $couponId = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($couponId)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'discount_type' => ['required', Rule::in(['percentage', 'fixed_amount', 'free_shipping'])],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'maximum_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_per_customer' => ['nullable', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'scope' => ['required', Rule::in(['global', 'shop_specific', 'school_specific', 'product_specific'])],
            'shop_ids' => ['nullable', 'array'],
            'shop_ids.*' => ['integer', 'exists:shops,id'],
            'school_ids' => ['nullable', 'array'],
            'school_ids.*' => ['integer', 'exists:schools,id'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:product_categories,id'],
        ]);
    }

    private function syncApplicables(Coupon $coupon, Request $request): void
    {
        // Reset all applicable targets, then attach only those relevant to the scope.
        $coupon->applicables()->delete();

        switch ($request->scope) {
            case 'shop_specific':
                $coupon->shops()->sync($request->input('shop_ids', []));
                break;
            case 'school_specific':
                $coupon->schools()->sync($request->input('school_ids', []));
                break;
            case 'product_specific':
                $coupon->products()->sync($request->input('product_ids', []));
                $coupon->categories()->sync($request->input('category_ids', []));
                break;
            case 'global':
            default:
                // Global coupons have no specific targets.
                break;
        }
    }

    private function formData(): array
    {
        return [
            'shops' => Shop::orderBy('name')->get(['id', 'name']),
            'schools' => School::orderBy('name')->get(['id', 'name']),
            'categories' => ProductCategory::orderBy('name')->get(['id', 'name']),
            'products' => Product::orderBy('name')->limit(500)->get(['id', 'name']),
        ];
    }
}
