<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'vender_id',
        'warehouse_id',
        'purchase_date',
        'purchase_number',
        'discount_apply',
        'category_id',
        'purchase_module',
        'workspace',
        'created_by',
    ];
    public static $statues = [
        'Draft',
        'Sent',
        'Unpaid',
        'Partialy Paid',
        'Paid',
    ];

    public function vender()
    {
        // if (module_is_active('Account')) {

            return $this->hasOne(\Modules\Account\Entities\Vender::class, 'user_id', 'vender_id');
        // }
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'vender_id');
    }
    public function tax()
    {
        if (module_is_active('ProductService')) {

            return $this->hasOne(\Modules\ProductService\Entities\Tax::class, 'id', 'tax_id');
        }
    }

    public function items()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id');
    }
    public function itemswithproduct()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id')->with('product');
    }
    public function debitNote()
    {
        return $this->hasMany(PurchaseDebitNote::class, 'purchase', 'id');
    }
    public function purchaseTotalDebitNote()
    {
        return $this->hasMany(PurchaseDebitNote::class, 'purchase', 'id')->sum('amount');
    }
    public function payments()
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }
    public function category()
    {
        if (module_is_active('ProductService')) {

            return $this->hasOne(\Modules\ProductService\Entities\Category::class, 'id', 'category_id');
        }
    }
    public function getSubTotal()
    {


        $subTotal = 0;
        // Check if items relationship is already loaded
        if (!$this->relationLoaded('items')) {
            // If not loaded, eager load the items
            $this->load('items');
        }
        foreach ($this->items as $product) {
            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }
    public function getTotal()
    {
        return ($this->getSubTotal() + $this->getTotalTax()) - $this->getTotalDiscount();
    }



    public function getTotalTax()
    {
        $totalTax = 0;

        // Retrieve all tax information for the items
        $taxIds = [];
        foreach ($this->items as $product) {
            $taxIds = array_merge($taxIds, explode(',', $product->tax));
        }

        $taxes = \Modules\ProductService\Entities\Tax::whereIn('id', $taxIds)->get();

        foreach ($this->items as $product) {
            $totalTax += $this->calculateTax($taxes, $product->tax, $product->price, $product->quantity, $product->discount);
        }

        return $totalTax;
    }

    protected function calculateTax($taxes, $productTax, $price, $quantity, $discount)
    {
        if (module_is_active('ProductService')) {
            $taxArr = explode(',', $productTax);
            $taxRate = 0;

            foreach ($taxArr as $taxId) {
                $tax = $taxes->firstWhere('id', $taxId);
                $taxRate += $tax ? $tax->rate : 0;
            }

            return ($taxRate / 100) * ($price * $quantity - $discount);
        } else {
            return 0;
        }
    }

    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach ($this->items as $product) {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }
    public function getDue()
    {
        $due = 0;
        foreach ($this->payments as $payment) {
            $due += $payment->amount;
        }

        return ($this->getTotal() - $due) - ($this->purchaseTotalDebitNote());
    }
    public static function purchaseNumberFormat($number, $company_id = null, $workspace = null)
    {
        if (!empty($company_id) && empty($workspace)) {
            $data = !empty(company_setting('purchase_prefix', $company_id)) ? company_setting('purchase_prefix', $company_id) : '#POS000';
        } elseif (!empty($company_id) && !empty($workspace)) {
            $data = !empty(company_setting('purchase_prefix', $company_id, $workspace)) ? company_setting('purchase_prefix', $company_id, $workspace) : '#POS000';
        } else {
            $data = !empty(company_setting('purchase_prefix')) ? company_setting('purchase_prefix') : '#POS000';
        }

        return $data . sprintf("%05d", $number);
    }
    public static function total_quantity($type, $quantity, $product_id)
    {
        if (module_is_active('ProductService')) {
            $product = \Modules\ProductService\Entities\ProductService::find($product_id);
            if (!empty($product)) {
                if (($product->type == 'product')) {
                    $pro_quantity = $product->quantity;

                    if ($type == 'minus') {
                        $product->quantity = $pro_quantity - $quantity;
                    } else {
                        $product->quantity = $pro_quantity + $quantity;
                    }
                    $product->save();
                }
            }
        }
    }
    public static function addWarehouseStock($product_id, $quantity, $warehouse_id)
    {

        $product = WarehouseProduct::where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();
        if ($product) {
            $pro_quantity = $product->quantity;
            $product_quantity = $pro_quantity + $quantity;
        } else {
            $product_quantity = $quantity;
        }


        $data = WarehouseProduct::updateOrCreate(
            ['warehouse_id' => $warehouse_id, 'product_id' => $product_id, 'created_by' => \Auth::user()->id, 'workspace' => getActiveWorkSpace()],
            ['warehouse_id' => $warehouse_id, 'product_id' => $product_id, 'quantity' => $product_quantity, 'created_by' => \Auth::user()->id, 'workspace' => getActiveWorkSpace()]
        );
    }
    public static function taxs($taxes)
    {
        if (module_is_active('ProductService')) {

            $taxArr = explode(',', $taxes);

            // Remove duplicate tax IDs
            $uniqueTaxArr = array_unique($taxArr);

            // Fetch all taxes in a single query
            $taxes = \Modules\ProductService\Entities\Tax::whereIn('id', $uniqueTaxArr)->get();

            return $taxes;
        } else {
            return [];
        }
    }

    public static function taxRate($taxRate, $price, $quantity, $discount = 0)
    {

        return (($price * $quantity) - $discount) * ($taxRate / 100);
    }
    public static function totalTaxRate($taxes)
    {
        if (module_is_active('ProductService')) {
            $taxArr = explode(',', $taxes);
            $taxRate = 0;
            foreach ($taxArr as $tax) {
                $tax = \Modules\ProductService\Entities\Tax::find($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }
            return $taxRate;
        } else {
            return 0;
        }
    }
    public static function userBalance($users, $id, $amount, $type)
    {
        $user = [];
        if (module_is_active('Account')) {
            if ($users == 'customer') {
                $user = \Modules\Account\Entities\Customer::find($id);
            } else {
                $user = \Modules\Account\Entities\Vender::find($id);
            }
        }

        if (!empty($user)) {
            if ($type == 'credit') {
                $oldBalance = $user->balance;
                $user->balance = $oldBalance + $amount;
                $user->save();
            } elseif ($type == 'debit') {
                $oldBalance = $user->balance;
                $user->balance = $oldBalance - $amount;
                $user->save();
            }
        }
    }
    public static function bankAccountBalance($id, $amount, $type)
    {
        if (module_is_active('Account')) {
            $bankAccount = \Modules\Account\Entities\BankAccount::find($id);
            if ($bankAccount) {
                if ($type == 'credit') {
                    $oldBalance = $bankAccount->opening_balance;
                    $bankAccount->opening_balance = $oldBalance + $amount;
                    $bankAccount->save();
                } elseif ($type == 'debit') {
                    $oldBalance = $bankAccount->opening_balance;
                    $bankAccount->opening_balance = $oldBalance - $amount;
                    $bankAccount->save();
                }
            }
        }
    }

    public function purchase_products()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id');
    }
    public static function totalPurchasedAmount($month = false)
    {


        $purchased = new Purchase();

        $purchased = $purchased->where('created_by', creatorId())->where('workspace', getActiveWorkSpace());

        if ($month) {
            $purchased = $purchased->whereRaw('MONTH(created_at) = ?', [date('m')]);
        }

        $purchasedAmount = 0;
        foreach ($purchased->get() as $key => $purchase) {
            $purchasedAmount += $purchase->getTotal();
        }

        return currency_format_with_sym($purchasedAmount);
    }

    public static function getPurchaseReportChart()
    {
        $purchases = Purchase::with('items')
            ->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(10))
            ->where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($val) {
                return \Carbon\Carbon::parse($val->created_at)->format('dm');
            });

        $total = [];

        if (!empty($purchases) && count($purchases) > 0) {
            foreach ($purchases as $day => $onepurchase) {
                $totals = 0;

                foreach ($onepurchase as $purchase) {
                    // Access the loaded items relationship
                    $items = $purchase->items;

                    $totals += $purchase->getTotal($items);
                }

                $total[$day] = $totals;
            }
        }
        $d = date("d");
        $m = date("m");
        $y = date("Y");

        for ($i = 0; $i <= 9; $i++) {
            $date = date('Y-m-d', mktime(0, 0, 0, $m, ($d - $i), $y));
            $purchasesArray['label'][] = $date;
            $date = date('dm', strtotime($date));
            $purchasesArray['value'][] = array_key_exists($date, $total) ? $total[$date] : 0;
            ;
        }

        return $purchasesArray;
    }

    public static function addProductStock($product_id, $quantity, $type, $description, $type_id)
    {
        $stocks = \Modules\Account\Entities\StockReport::where('product_id', $product_id)->where('type', $type)->where('type_id', $type_id)->first();
        if (empty($stocks)) {
            $stocks = new \Modules\Account\Entities\StockReport();
            $stocks->product_id = $product_id;
            $stocks->type = $type;
            $stocks->type_id = $type_id;
            $stocks->description = $description;
            $stocks->workspace = getActiveWorkSpace();
            $stocks->created_by = creatorId();
        }
        $stocks->quantity = $quantity;
        $stocks->save();
    }

    public static function vendorPurchase($vendorId)
    {
        $purchase = Purchase::where('vender_id', $vendorId)->orderBy('purchase_date', 'desc')->get();
        return $purchase;
    }
}
