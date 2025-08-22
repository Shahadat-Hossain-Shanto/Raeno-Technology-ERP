<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Observers\PosObserver;
use App\Models\Pos;

use App\Observers\CategoryObserver;
use App\Models\Category;

use App\Observers\SubcategoryObserver;
use App\Models\Subcategory;

use App\Observers\SupplierObserver;
use App\Models\Supplier;

use App\Observers\BrandObserver;
use App\Models\Brand;

use App\Observers\ProductObserver;
use App\Models\Product;

use App\Observers\InventoryObserver;
use App\Models\Inventory;

use App\Observers\StoreInventoryObserver;
use App\Models\StoreInventory;

use App\Observers\StoreProductObserver;
use App\Models\StoreProduct;

use App\Observers\OrderObserver;
use App\Models\Order;

use App\Observers\OrderedProductObserver;
use App\Models\OrderedProduct;

use App\Observers\DiscountObserver;
use App\Models\Discount;

use App\Observers\VatObserver;
use App\Models\Vat;

use App\Observers\StoreDiscountObserver;
use App\Models\StoreDiscount;

use App\Observers\StoreVatObserver;
use App\Models\StoreVat;

use App\Observers\OrderedProductDiscountObserver;
use App\Models\OrderedProductDiscount;

use App\Observers\OrderedProductTaxObserver;
use App\Models\OrderedProductTax;

use App\Observers\EmployeeObserver;
use App\Models\Employee;

// use App\Observers\RoleObserver;
// use App\Models\Role;

use App\Observers\PurchaseProductObserver;
use App\Models\PurchaseProduct;

use App\Observers\PurchaseProductListObserver;
use App\Models\PurchaseProductList;

use App\Observers\PaymentObserver;
use App\Models\Payment;

use App\Observers\ClientObserver;
use App\Models\Client;

use App\Observers\MenuObserver;
use App\Models\Menu;

use App\Observers\DuePaymentObserver;
use App\Models\DuePayment;

use App\Observers\DepositObserver;
use App\Models\Deposit;

use App\Observers\ExpenseObserver;
use App\Models\Expense;

use App\Observers\PriceObserver;
use App\Models\Price;

use App\Observers\ProductTransferHistoryObserver;
use App\Models\ProductTransferHistory;

use App\Observers\ProductInHistoryObserver;
use App\Models\ProductInHistory;

use App\Observers\BatchObserver;
use App\Models\Batch;

use App\Observers\LeafObserver;
use App\Models\Leaf;

use App\Observers\ProductUnitObserver;
use App\Models\ProductUnit;

use App\Observers\SalesReturnObserver;
use App\Models\SalesReturn;

use App\Observers\ExpenseCategoryObserver;
use App\Models\ExpenseCategory;

use App\Observers\HoldSaleObserver;
use App\Models\HoldSale;

use App\Observers\EmployeeLeaveObserver;
use App\Models\EmployeeLeave;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Pos::observe(PosObserver::class);
        Category::observe(CategoryObserver::class);
        Subcategory::observe(SubcategoryObserver::class);
        Supplier::observe(SupplierObserver::class);
        Brand::observe(BrandObserver::class);
        Product::observe(ProductObserver::class);
        Inventory::observe(InventoryObserver::class);
        StoreInventory::observe(StoreInventoryObserver::class);
        StoreProduct::observe(StoreProductObserver::class);

        Order::observe(OrderObserver::class);

        OrderedProduct::observe(OrderedProductObserver::class);
        Discount::observe(DiscountObserver::class);
        Vat::observe(VatObserver::class);
        StoreDiscount::observe(StoreDiscountObserver::class);
        StoreVat::observe(StoreVatObserver::class);
        OrderedProductDiscount::observe(OrderedProductDiscountObserver::class);
        OrderedProductTax::observe(OrderedProductTaxObserver::class);
        Employee::observe(EmployeeObserver::class);
        //Role::observe(RoleObserver::class);
        PurchaseProduct::observe(PurchaseProductObserver::class);
        PurchaseProductList::observe(PurchaseProductListObserver::class);
        Payment::observe(PaymentObserver::class);
        Client::observe(ClientObserver::class);
        Menu::observe(MenuObserver::class);
        DuePayment::observe(DuePaymentObserver::class);
        Deposit::observe(DepositObserver::class);
        Expense::observe(ExpenseObserver::class);
        Price::observe(PriceObserver::class);
        ProductTransferHistory::observe(ProductTransferHistoryObserver::class);
        ProductInHistory::observe(ProductInHistoryObserver::class);
        Batch::observe(BatchObserver::class);
        Leaf::observe(LeafObserver::class);
        ProductUnit::observe(ProductUnitObserver::class);
        SalesReturn::observe(SalesReturnObserver::class);
        ExpenseCategory::observe(ExpenseCategoryObserver::class);
        HoldSale::observe(HoldSaleObserver::class);
        EmployeeLeave::observe(EmployeeLeaveObserver::class);

    }
}
