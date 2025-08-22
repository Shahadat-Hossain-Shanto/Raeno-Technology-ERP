<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Pos;
use App\Models\Vat;
use App\Models\Bank;
use App\Models\Leaf;
use App\Models\User;
use App\Models\Batch;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Price;
use App\Models\Shift;
use App\Models\Store;
use App\Models\Client;
use App\Models\Salary;
use App\Models\Benefit;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Holiday;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Weekend;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\HoldSale;
use App\Models\StoreVat;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\LeaveType;
use App\Models\Attendance;
use App\Models\DuePayment;
use App\Models\PayBenefit;
use App\Models\Subscriber;
use App\Models\BenefitList;
use App\Models\Designation;
use App\Models\ProductUnit;
use App\Models\SalaryGrade;
use App\Models\SalesReturn;
use App\Models\Subcategory;
use App\Models\Transaction;
use App\Models\ExpenseImage;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Models\EmployeeTable;
use App\Models\PaymentMethod;
use App\Models\SignUpRequest;
use App\Models\StoreDiscount;
use App\Models\ChartOfAccount;
use App\Models\OrderedProduct;
use App\Models\PurchaseReturn;
use App\Models\SalaryEmployee;
use App\Models\StoreInventory;
use App\Models\ExpenseCategory;
use App\Models\PurchaseProduct;
use App\Models\ShiftAllocation;
use App\Models\ProductInHistory;
use App\Models\OrderedProductTax;
use App\Models\EmployeeDepartment;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\PurchaseProductList;
use App\Models\WeeklySalaryEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\OrderedProductDiscount;
use App\Models\ProductTransferHistory;

class SubscriberController extends Controller
{
    public function create()
    {
        return view('subscriber/subscriber-add');
    }

    public function store(Request $request)
    {
        $userRequest = SignUpRequest::where([
            ['mobile', $request->mobile],
            ['email', $request->email],
        ])->get();
        if ($userRequest->isEmpty()) {
            $signupRequest = new SignUpRequest;
            $signupRequest->name        = $request->ownername;
            $signupRequest->store_name  = $request->orgname;
            $signupRequest->mobile      = $request->contactnumber;
            $signupRequest->email       = $request->email;
            $signupRequest->address     = $request->orgaddress;
            $signupRequest->registration_type = $request->registrationType;
            $signupRequest->package     = $request->packageName;
            $signupRequest->branch      = $request->postype;
            $signupRequest->password    = Hash::make($request->password);
            $signupRequest->save();

            $subscriber = new Subscriber;
            $subscriber->org_name           = $request->orgname;
            $subscriber->org_address        = $request->orgaddress;
            $subscriber->owner_name         = $request->ownername;
            $subscriber->bin_number         = $request->binnumber;
            $subscriber->contact_number     = $request->contactnumber;
            $subscriber->email              = $request->email;
            $subscriber->registration_type  = $request->registrationType;
            $subscriber->package_name       = $request->packageName;
            $subscriber->pos_type           = $request->postype;
            $subscriber->status             = 'Active';
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/subscriber/logo/', $filename);
                $subscriber->logo = $filename;
            }
            $subscriber->save(); {
                $subscriberid =  DB::table("subscribers")->select('*')->where('contact_number', $request->contactnumber)->first()->id;
                $data = SignUpRequest::where('mobile', $request->contactnumber)->first();
                session()->put('subscriberId', $subscriberid);

                //Create Store
                $store = new Store;
                $store->store_name      = $data->store_name;
                $store->store_address   = $data->address;
                $store->contact_number  = $data->mobile;
                $store->subscriber_id   = $subscriberid;
                $store->save();

                //Create Role
                $role = new Role();
                $role->name = 'Admin';
                $role->subscriber_id = $subscriberid;
                $role->save();

                //Create Admin
                $user = new User;
                $user->name             = 'Admin';
                $user->email            = 'admin.' . strtolower(strtok($data->mobile, " ")) . '@gmail.com';
                $user->subscriber_id    = $subscriberid;
                $user->password         = Hash::make('123456bd');
                do {
                    $number = random_int(1000000, 9999999);
                } while (User::where("contact_number", "=", $number)->first());
                $user->contact_number   = $number;
                $user->store_id         = $store->id;
                $user->assignRole('Admin');
                $user->save();

                //Create User
                $user = new User;
                $user->name             = $data->name;
                $user->email            = $data->email;
                $user->subscriber_id    = $subscriberid;
                $user->password         = $data->password;
                $user->contact_number   = $data->mobile;
                $user->store_id         = $store->id;
                $user->save();

                //Create POS
                $pos = new Pos;
                $pos->pos_name = 'Default-POS';
                $pos->pos_status = 'Active';
                $pos->pos_pin = '1234';
                $pos->store_id = $store->id;
                $pos->subscriber_id = $subscriberid;
                $pos->created_by = $data->store_name;
                $pos->save();

                //Create Chart Of Account
                DB::statement(
                    " INSERT INTO `chart_of_accounts` (`head_code`, `head_name`, `parent_head`, `parent_head_level`, `head_type`, `is_transaction`, `is_active`, `is_general_ledger`, `subscriber_id`) VALUES
                    ('0', 'COA', '#', '#', '#', '0', '0', '0', $subscriberid),
                    ('1', 'Asset', 'COA', '0', 'A', '1', '1', '1', $subscriberid),
                    ('2', 'Equity', 'COA', '0', 'Q', '0', '1', '0', $subscriberid),
                    ('3', 'Expense', 'COA', '0', 'E', '1', '1', '0', $subscriberid),
                    ('4', 'Income', 'COA', '0', 'I', '0', '1', '0', $subscriberid),
                    ('5', 'Liabilities', 'COA', '0', 'L', '0', '1', '0', $subscriberid),
                    ('101', 'Current Asset', 'Asset', '1', 'A', '1', '1', '1', $subscriberid),
                    ('102', 'Non Current Asset', 'Asset', '1', 'A', '1', '1', '1', $subscriberid),
                    ('103', 'Tassets', 'Asset', '1', 'A', '1', '1', '1', $subscriberid),
                    ('401', 'Product Sale', 'Income', '4', 'I', '0', '1', '0', $subscriberid),
                    ('402', 'Service Income', 'Income', '4', 'I', '0', '1', '0', $subscriberid),
                    ('403', 'Store Income', 'Income', '4', 'I', '0', '1', '0', $subscriberid),
                    ('501', 'Current Liabilities', 'Liabilities', '5', 'L', '0', '1', '0', $subscriberid),
                    ('502', 'Non Current Liabilities', 'Liabilities', '5', 'L', '0', '1', '0', $subscriberid),
                    ('10101', 'Account Receivable', 'Current Asset', '101', 'A', '1', '1', '1', $subscriberid),
                    ('10102', 'Cash & Cash Equivalent', 'Current Asset', '101', 'A', '1', '1', '1', $subscriberid),
                    ('10201', 'Inventory', 'Non Current Asset', '102', 'A', '1', '1', '1', $subscriberid),
                    ('50101', 'Account Payable', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
                    ('50102', 'Employee Payable', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
                    ('50103', 'Supplier Payable', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
                    ('50104', 'Tax', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
                    ('1010101', 'Customer Receivable', 'Account Receivable', '10101', 'A', '1', '1', '1', $subscriberid),
                    ('1010102', 'Loan Receivable', 'Account Receivable', '10101', 'A', '1', '1', '1', $subscriberid),
                    ('1010103', 'Service Receivable', 'Account Receivable', '10101', 'A', '1', '1', '1', $subscriberid),
                    ('1010201', 'Cash At Bank', 'Cash & Cash Equivalent', '10102', 'A', '1', '1', '1', $subscriberid),
                    ('1010202', 'Cash in Hand', 'Cash & Cash Equivalent', '10102', 'A', '1', '1', '1', $subscriberid),
                    ('1010203', 'Petty Cash', 'Cash & Cash Equivalent', '10102', 'A', '1', '1', '1', $subscriberid);
                "
                );

                //Create Expense Category
                $expenseCategories = ['Salary', 'Purchase', 'Bill', 'Rent', 'Others'];
                for ($x = 0; $x < count($expenseCategories); $x++) {
                    $expense = new ExpenseCategory;
                    $expense->expense_type_name = $expenseCategories[$x];
                    $expense->subscriber_id = $subscriberid;
                    $expense->user_id = $user->id;
                    $expense->save();

                    $coa = new ChartOfAccount;
                    $coa->head_code             = '300' + ($x + 1);
                    $coa->head_name             = $expenseCategories[$x];
                    $coa->parent_head           = 'Expense';
                    $coa->parent_head_level     = 3;
                    $coa->head_type             = 'E';
                    $coa->is_transaction        = 1;
                    $coa->is_active             = 1;
                    $coa->is_general_ledger     = 0;
                    $coa->subscriber_id         = $subscriberid;
                    $coa->save();
                }

                //Create Units
                $units = ['mg', 'gm', 'kg', 'piece'];
                for ($x = 0; $x < count($units); $x++) {
                    $unit = new ProductUnit;
                    $unit->name = $units[$x];
                    $unit->subscriber_id = $subscriberid;
                    $unit->user_id = $user->id;
                    $unit->save();
                }

                //Create Payment Method
                $paymentMethods = ['bKash', 'Nagad', 'NexusPay', 'Upay', 'Rocket', 'SureCash'];
                for ($x = 0; $x < count($paymentMethods); $x++) {
                    $paymentMethod = new PaymentMethod;
                    $paymentMethod->paymentType = $paymentMethods[$x];
                    $paymentMethod->subscriber_id = $subscriberid;
                    $paymentMethod->save();
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Subscription Successful'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Account Exist'
            ]);
        }
    }

    // public function store(Request $req)
    // {

    //     $subscriber = new Subscriber;
    //     $subscriber->org_name                 = $req->orgname;
    //     $subscriber->org_address              = $req->orgaddress;
    //     $subscriber->owner_name               = $req->ownername;
    //     $subscriber->bin_number               = $req->binnumber;
    //     $subscriber->contact_number           = $req->contactnumber;
    //     $subscriber->email                    = $req->email;
    //     // $subscriber->password                 = Hash::make($req->password);
    //     $subscriber->pos_type                 = $req->postype;
    //     $subscriber->status                   = $req->status;
    //     if ($req->hasFile('logo')) {
    //         $file = $req->file('logo');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extension;
    //         $file->move('uploads/subscriber/logo/', $filename);
    //         $subscriber->logo = $filename;
    //     }
    //     $subscriber->save();

    //     $store = new Store;
    //     $store->store_name                    = $req->orgname;
    //     $store->store_address                 = $req->orgaddress;
    //     $store->contact_number                = $req->contactnumber;
    //     $store->subscriber_id                 = $subscriber->id;
    //     $store->save();

    //     $role = new Role();
    //     $role->name = 'Admin';
    //     $role->subscriber_id = $subscriber->id;
    //     $role->save();

    //     $user = new User();
    //     $user->name                           = $req->ownername;
    //     $user->email                          = $req->email;
    //     $user->password                       =  Hash::make($req->password);
    //     $user->subscriber_id                  = $subscriber->id;
    //     $user->contact_number                 = $req->contactnumber;
    //     $user->store_id                       = $store->id;
    //     $user->assignRole('Admin');
    //     $user->save();



    //     $pos = new Pos;
    //     $pos->pos_name      = 'Default-POS';
    //     $pos->pos_status    = 'Active';
    //     $pos->pos_pin       = '1234';
    //     $pos->store_id      =  $store->id;
    //     $pos->subscriber_id =  $subscriber->id;
    //     $pos->created_by     =  $subscriber->id;
    //     $pos->save();

    //     $expenseCategories = ['Salary', 'Purchase', 'Bill', 'Rent', 'Others'];



    //     for ($x = 0; $x < count($expenseCategories); $x++){
    //         $expense = new ExpenseCategory;
    //         $expense->expense_type_name = $expenseCategories[$x];
    //         $expense->created_by     =  $subscriber->id;
    //         $expense->subscriber_id = $subscriber->id;
    //         $expense->user_id = $user->id;
    //         $expense->save();

    //     }

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Subscription Successful!'
    //     ]);
    // }

    public function ui_create()
    {
        return view('subscriber/subscriber-active');
    }

    public function show_data(Request $request)
    {
        $data = SignUpRequest::join("subscribers", "subscribers.contact_number", "sign_up_requests.mobile")
            ->select('subscribers.*', 'sign_up_requests.*', 'sign_up_requests.created_at AS time')
            ->get();
        if ($request->ajax()) {
            return response()->json([
                'data' => $data,
                'message' => 'Success'
            ]);
        }
    }

    public function subscriberData($id)
    {
        $data = SignUpRequest::join("subscribers", "subscribers.contact_number", "sign_up_requests.mobile")
            ->select('subscribers.*', 'sign_up_requests.*', 'sign_up_requests.created_at AS time')
            ->where('subscribers.contact_number', $id)
            ->first();
        return response()->json($data);
    }

    public function update($id)
    {
        $subscriberid =  DB::table("subscribers")->select('*')->where('contact_number', $id)->first()->id;
        //log::info($subscriber);
        // foreach($subscriber as $subscriber)
        // {
        //     $subscriberid= $subscriber->id;
        // }
        $admin = User::where([['name', 'Admin'], ['subscriber_id', $subscriberid]])->get();

        if ($admin->isEmpty()) {
            $data = SignUpRequest::where('mobile', $id)->first();

            //Create Store
            $store = new Store;
            $store->store_name      = $data->store_name;
            $store->store_address   = $data->address;
            $store->contact_number  = $data->mobile;
            $store->subscriber_id   = $subscriberid;
            $store->save();

            //Create Role
            $role = new Role();
            $role->name = 'Admin';
            $role->subscriber_id = $subscriberid;
            $role->save();

            //Create Admin
            $user = new User;
            $user->name             = 'Admin';
            $user->email            = 'admin.' . strtolower(strtok($data->store_name, " ")) . '@gmail.com';
            $user->subscriber_id    = $subscriberid;
            $user->password         = Hash::make('123456bd');
            do {
                $number = random_int(1000000, 9999999);
            } while (User::where("contact_number", "=", $number)->first());
            $user->contact_number   = $number;
            $user->store_id         = $store->id;
            $user->assignRole('Admin');
            $user->save();

            //Create User
            $user = new User;
            $user->name             = $data->name;
            $user->email            = $data->email;
            $user->subscriber_id    = $subscriberid;
            $user->password         = $data->password;
            $user->contact_number   = $data->mobile;
            $user->store_id         = $store->id;
            $user->save();

            //Create POS
            $pos = new Pos;
            $pos->pos_name = 'Default-POS';
            $pos->pos_status = 'Active';
            $pos->pos_pin = '1234';
            $pos->store_id = $store->id;
            $pos->subscriber_id = $subscriberid;
            $pos->created_by = $data->store_name;
            $pos->save();

            //Create Chart Of Account
            DB::statement(
                " INSERT INTO `chart_of_accounts` (`head_code`, `head_name`, `parent_head`, `parent_head_level`, `head_type`, `is_transaction`, `is_active`, `is_general_ledger`, `subscriber_id`) VALUES
            ('0', 'COA', '#', '#', '#', '0', '0', '0', $subscriberid),
            ('1', 'Asset', 'COA', '0', 'A', '1', '1', '1', $subscriberid),
            ('2', 'Equity', 'COA', '0', 'Q', '0', '1', '0', $subscriberid),
            ('3', 'Expense', 'COA', '0', 'E', '1', '1', '0', $subscriberid),
            ('4', 'Income', 'COA', '0', 'I', '0', '1', '0', $subscriberid),
            ('5', 'Liabilities', 'COA', '0', 'L', '0', '1', '0', $subscriberid),
            ('101', 'Current Asset', 'Asset', '1', 'A', '1', '1', '1', $subscriberid),
            ('102', 'Non Current Asset', 'Asset', '1', 'A', '1', '1', '1', $subscriberid),
            ('103', 'Tassets', 'Asset', '1', 'A', '1', '1', '1', $subscriberid),
            ('401', 'Product Sale', 'Income', '4', 'I', '0', '1', '0', $subscriberid),
            ('402', 'Service Income', 'Income', '4', 'I', '0', '1', '0', $subscriberid),
            ('403', 'Store Income', 'Income', '4', 'I', '0', '1', '0', $subscriberid),
            ('501', 'Current Liabilities', 'Liabilities', '5', 'L', '0', '1', '0', $subscriberid),
            ('502', 'Non Current Liabilities', 'Liabilities', '5', 'L', '0', '1', '0', $subscriberid),
            ('10101', 'Account Receivable', 'Current Asset', '101', 'A', '1', '1', '1', $subscriberid),
            ('10102', 'Cash & Cash Equivalent', 'Current Asset', '101', 'A', '1', '1', '1', $subscriberid),
            ('10201', 'Inventory', 'Non Current Asset', '102', 'A', '1', '1', '1', $subscriberid),
            ('50101', 'Account Payable', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
            ('50102', 'Employee Payable', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
            ('50103', 'Supplier Payable', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
            ('50104', 'Tax', 'Current Liabilities', '501', 'L', '0', '1', '0', $subscriberid),
            ('1010101', 'Customer Receivable', 'Account Receivable', '10101', 'A', '1', '1', '1', $subscriberid),
            ('1010102', 'Loan Receivable', 'Account Receivable', '10101', 'A', '1', '1', '1', $subscriberid),
            ('1010103', 'Service Receivable', 'Account Receivable', '10101', 'A', '1', '1', '1', $subscriberid),
            ('1010201', 'Cash At Bank', 'Cash & Cash Equivalent', '10102', 'A', '1', '1', '1', $subscriberid),
            ('1010202', 'Cash in Hand', 'Cash & Cash Equivalent', '10102', 'A', '1', '1', '1', $subscriberid),
            ('1010203', 'Petty Cash', 'Cash & Cash Equivalent', '10102', 'A', '1', '1', '1', $subscriberid);
        "
            );

            //Create Expense Category
            $expenseCategories = ['Salary', 'Purchase', 'Bill', 'Rent', 'Others'];
            for ($x = 0; $x < count($expenseCategories); $x++) {
                $expense = new ExpenseCategory;
                $expense->expense_type_name = $expenseCategories[$x];
                $expense->subscriber_id = $subscriberid;
                $expense->user_id = $user->id;
                $expense->save();

                $coa = new ChartOfAccount;
                $coa->head_code             = '300' + ($x + 1);
                $coa->head_name             = $expenseCategories[$x];
                $coa->parent_head           = 'Expense';
                $coa->parent_head_level     = 3;
                $coa->head_type             = 'E';
                $coa->is_transaction        = 1;
                $coa->is_active             = 1;
                $coa->is_general_ledger     = 0;
                $coa->subscriber_id         = $subscriberid;
                $coa->save();
            }

            //Create Units
            $units = ['mg', 'gm', 'kg', 'piece'];
            for ($x = 0; $x < count($units); $x++) {
                $unit = new ProductUnit;
                $unit->name = $units[$x];
                $unit->subscriber_id = $subscriberid;
                $unit->user_id = $user->id;
                $unit->save();
            }

            //Create Payment Method
            $paymentMethods = ['bKash', 'Nagad', 'NexusPay', 'Upay', 'Rocket', 'SureCash'];
            for ($x = 0; $x < count($paymentMethods); $x++) {
                $paymentMethod = new PaymentMethod;
                $paymentMethod->paymentType = $paymentMethods[$x];
                $paymentMethod->subscriber_id = $subscriberid;
                $paymentMethod->save();
            }
        }

        $subscriber = Subscriber::find($subscriberid);
        if ($subscriber->status == "Active")
            $subscriber->status = "Inactive";
        elseif ($subscriber->status == "Inactive")
            $subscriber->status = "Active";

        $subscriber->save();
    }

    public function delete($id)
    {
        $subscriberid =  DB::table("subscribers")->select('*')->where('contact_number', $id)->first()->id;

        //Delete Brand
        $brand = Brand::where('subscriber_id', $subscriberid)->get(['id']);
        Brand::destroy($brand->toArray());

        //Delete Category
        $category = Category::where('subscriber_id', $subscriberid)->get(['id']);
        Category::destroy($category->toArray());

        //Delete SubCategory
        $subCategory = Subcategory::where('created_by', $subscriberid)->get(['id']);
        Subcategory::destroy($subCategory->toArray());

        //Delete Batch
        $batch = Batch::where('subscriber_id', $subscriberid)->get(['id']);
        Batch::destroy($batch->toArray());

        //Delete Leaf
        $leaf = Leaf::where('subscriber_id', $subscriberid)->get(['id']);
        Leaf::destroy($leaf->toArray());

        //Delete ProductUnit
        $productUnit = ProductUnit::where('subscriber_id', $subscriberid)->get(['id']);
        ProductUnit::destroy($productUnit->toArray());

        //Delete Vat
        $vat = Vat::where('subscriber_id', $subscriberid)->get(['id']);
        Vat::destroy($vat->toArray());

        //Delete Discount
        $discount = Discount::where('subscriber_id', $subscriberid)->get(['id']);
        Discount::destroy($discount->toArray());
        $storeDiscount = StoreDiscount::where('subscriber_id', $subscriberid)->get(['id']);
        StoreDiscount::destroy($storeDiscount->toArray());

        //Delete Product
        $product = Product::where('subscriber_id', $subscriberid)->get(['id']);
        Product::destroy($product->toArray());

        //Delete ProductInHistory
        $productInHistory = ProductInHistory::where('subscriber_id', $subscriberid)->get(['id']);
        ProductInHistory::destroy($productInHistory->toArray());

        //Delete ProductTransferHistory
        $productTransferHistory = ProductTransferHistory::where('subscriber_id', $subscriberid)->get(['id']);
        ProductTransferHistory::destroy($productTransferHistory->toArray());

        //Delete Supplier
        $supplier = Supplier::where('subscriber_id', $subscriberid)->get(['id']);
        Supplier::destroy($supplier->toArray());

        //Delete Client
        $client = Client::where('subscriber_id', $subscriberid)->get(['id']);
        Client::destroy($client->toArray());

        //Delete Order
        $order = Order::where('subscriber_id', $subscriberid)->get(['id']);
        Order::destroy($order->toArray());

        //Delete OrderedProduct
        $orderedProduct = OrderedProduct::where('subscriber_id', $subscriberid)->get(['id']);
        OrderedProduct::destroy($orderedProduct->toArray());

        //Delete OrderedProductDiscount
        $orderedProductDiscount = OrderedProductDiscount::where('created_by', $subscriberid)->get(['id']);
        OrderedProductDiscount::destroy($orderedProductDiscount->toArray());

        //Delete OrderedProductTax
        $orderedProductTax = OrderedProductTax::where('created_by', $subscriberid)->get(['id']);
        OrderedProductTax::destroy($orderedProductTax->toArray());

        //Delete Payment
        $payment = Payment::where('subscriber_id', $subscriberid)->get(['id']);
        Payment::destroy($payment->toArray());

        //Delete DuePayment
        $duePayment = DuePayment::where('subscriber_id', $subscriberid)->get(['id']);
        DuePayment::destroy($duePayment->toArray());

        //Delete Deposit
        $deposit = Deposit::where('subscriber_id', $subscriberid)->get(['id']);
        Deposit::destroy($deposit->toArray());

        //Delete HoldSale
        $holdSale = HoldSale::where('subscriber_id', $subscriberid)->get(['id']);
        HoldSale::destroy($holdSale->toArray());

        //Delete SalesReturn
        $salesReturn = SalesReturn::where('subscriber_id', $subscriberid)->get(['id']);
        SalesReturn::destroy($salesReturn->toArray());

        //Delete PurchaseProduct
        $purchaseProduct = PurchaseProduct::where('subscriber_id', $subscriberid)->get(['id']);
        PurchaseProduct::destroy($purchaseProduct->toArray());

        //Delete PurchaseProductList
        $purchaseProductList = PurchaseProductList::where('created_by', $subscriberid)->get(['id']);
        PurchaseProductList::destroy($purchaseProductList->toArray());

        //Delete PurchaseReturn
        $purchaseReturn = PurchaseReturn::where('subscriber_id', $subscriberid)->get(['id']);
        PurchaseReturn::destroy($purchaseReturn->toArray());

        //Delete Inventory
        $inventory = Inventory::where('subscriber_id', $subscriberid)->get(['id']);
        Inventory::destroy($inventory->toArray());

        //Delete StoreInventory
        $storeInventory = StoreInventory::where('created_by', $subscriberid)->get(['id']);
        StoreInventory::destroy($storeInventory->toArray());

        //Delete PaymentMethod
        $paymentMethod = PaymentMethod::where('subscriber_id', $subscriberid)->get(['id']);
        PaymentMethod::destroy($paymentMethod->toArray());

        //Delete Expense
        $expense = Expense::where('subscriber_id', $subscriberid)->get(['id']);
        Expense::destroy($expense->toArray());

        //Delete ExpenseCategory
        $expenseCategory = ExpenseCategory::where('subscriber_id', $subscriberid)->get(['id']);
        ExpenseCategory::destroy($expenseCategory->toArray());

        //Delete ExpenseImage
        $expense = Expense::where('subscriber_id', $subscriberid)->get(['id']);
        $expenseImage = ExpenseImage::where('expense_id', $expense->toArray())->get(['id']);
        ExpenseImage::destroy($expenseImage->toArray());

        //Delete Bank
        $bank = Bank::where('subscriber_id', $subscriberid)->get(['id']);
        Bank::destroy($bank->toArray());

        //Delete ChartOfAccount
        $chartOfAccount = ChartOfAccount::where('subscriber_id', $subscriberid)->get(['id']);
        ChartOfAccount::destroy($chartOfAccount->toArray());

        //Delete Transaction
        $transaction = Transaction::where('subscriber_id', $subscriberid)->get(['id']);
        Transaction::destroy($transaction->toArray());

        //Delete Designation
        $designation = Designation::where('subscriber_id', $subscriberid)->get(['id']);
        Designation::destroy($designation->toArray());

        //Delete EmployeeDepartment
        $employeeDepartment = EmployeeDepartment::where('subscriber_id', $subscriberid)->get(['id']);
        EmployeeDepartment::destroy($employeeDepartment->toArray());

        //Delete Employee
        $employee = Employee::where('subscriber_id', $subscriberid)->get(['id']);
        Employee::destroy($employee->toArray());

        //Delete Shift
        $shift = Shift::where('subscriber_id', $subscriberid)->get(['id']);
        Shift::destroy($shift->toArray());

        //Delete Holiday
        $holiday = Holiday::where('subscriber_id', $subscriberid)->get(['id']);
        Holiday::destroy($holiday->toArray());

        //Delete Weekend
        $weekend = Weekend::where('subscriber_id', $subscriberid)->get(['id']);
        Weekend::destroy($weekend->toArray());

        //Delete EmployeeLeave
        $employeeLeave = EmployeeLeave::where('subscriber_id', $subscriberid)->get(['id']);
        EmployeeLeave::destroy($employeeLeave->toArray());

        //Delete LeaveType
        $leaveType = LeaveType::where('subscriber_id', $subscriberid)->get(['id']);
        LeaveType::destroy($leaveType->toArray());

        //Delete Attendance
        $attendance = Attendance::where('subscriber_id', $subscriberid)->get(['id']);
        Attendance::destroy($attendance->toArray());

        //Delete Benefit
        $benefit = Benefit::where('subscriber_id', $subscriberid)->get(['id']);
        Benefit::destroy($benefit->toArray());

        //Delete BenefitList
        $benefitList = BenefitList::where('subscriber_id', $subscriberid)->get(['id']);
        BenefitList::destroy($benefitList->toArray());

        //Delete Salary
        $salary = Salary::where('subscriber_id', $subscriberid)->get(['id']);
        Salary::destroy($salary->toArray());

        //Delete SalaryEmployee
        $salaryEmployee = SalaryEmployee::where('subscriber_id', $subscriberid)->get(['id']);
        SalaryEmployee::destroy($salaryEmployee->toArray());

        //Delete SalaryGrade
        $salaryGrade = SalaryGrade::where('subscriber_id', $subscriberid)->get(['id']);
        SalaryGrade::destroy($salaryGrade->toArray());

        //Delete EmployeeTable
        $employeeTable = EmployeeTable::where('subscriber_id', $subscriberid)->get(['id']);
        EmployeeTable::destroy($employeeTable->toArray());

        //Delete PayBenefit
        $payBenefit = PayBenefit::where('subscriber_id', $subscriberid)->get(['id']);
        PayBenefit::destroy($payBenefit->toArray());

        //Delete Price
        $price = Price::where('subscriber_id', $subscriberid)->get(['id']);
        Price::destroy($price->toArray());

        //Delete ShiftAllocation
        $shiftAllocation = ShiftAllocation::where('subscriber_id', $subscriberid)->get(['id']);
        ShiftAllocation::destroy($shiftAllocation->toArray());

        //Delete StoreVat
        $storeVat = StoreVat::where('created_by', $subscriberid)->get(['id']);
        StoreVat::destroy($storeVat->toArray());

        //Delete WeeklySalaryEmployee
        $seeklySalaryEmployee = WeeklySalaryEmployee::where('subscriber_id', $subscriberid)->get(['id']);
        WeeklySalaryEmployee::destroy($seeklySalaryEmployee->toArray());

        //Delete POS
        $pos = POS::where('subscriber_id', $subscriberid)->get(['id']);
        POS::destroy($pos->toArray());

        //Delete Store
        $store = Store::where('subscriber_id', $subscriberid)->get(['id']);
        Store::destroy($store->toArray());

        //Delete User
        $user = User::where('subscriber_id', $subscriberid)->get(['id']);
        User::destroy($user->toArray());

        //Delete Role
        $role = Role::where('subscriber_id', $subscriberid)->get(['id']);
        Role::destroy($role->toArray());

        //Delete SignUpRequest
        $subscriberContact_number =  DB::table("subscribers")->select('*')->where('contact_number', $id)->first()->contact_number;
        $SignUpRequest = SignUpRequest::where('mobile', $subscriberContact_number)->get(['id']);
        SignUpRequest::destroy($SignUpRequest->toArray());

        //Delete Subscriber
        Subscriber::destroy($subscriberid);

        return response()->json([
            'message' => 'Success'
        ]);
    }
}
