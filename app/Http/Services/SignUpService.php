<?php

namespace App\Http\Services;

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

class SignUpService
{
    public function store(Request $request)
    {
        log::info($request);
        $userRequest = SignUpRequest::where([
            ['mobile', $request->contactnumber],
            ['email', $request->email],
        ])->get();
        if ($userRequest->isEmpty()) {
            $signupRequest = new SignUpRequest;
            $signupRequest->name        = $request->ownerName;
            $signupRequest->store_name  = $request->storeName;
            $signupRequest->mobile      = $request->mobile;
            $signupRequest->email       = $request->email;
            $signupRequest->address     = $request->address;
            $signupRequest->registration_type = $request->registrationType;
            $signupRequest->package     = $request->packageName;
            $signupRequest->branch      = $request->branchType;
            $signupRequest->password    = Hash::make($request->password);
            $signupRequest->save();

            $subscriber = new Subscriber;
            $subscriber->org_name           = $request->storeName;
            $subscriber->org_address        = $request->address;
            $subscriber->owner_name         = $request->ownerName;
            $subscriber->contact_number     = $request->mobile;
            $subscriber->email              = $request->email;
            $subscriber->registration_type  = $request->registrationType;
            $subscriber->package_name       = $request->packageName;
            $subscriber->pos_type           = $request->branchType;
            $subscriber->status             = 'Active';
            $subscriber->save();

            // {
            // $store = new Store;
            // $store->store_name      = $request->storeName;
            // $store->store_address   = $request->address;
            // $store->contact_number  = $request->mobile;
            // $store->subscriber_id   = $subscriber->id;
            // $store->save();

            // $user = new User;
            // $user->name             = $request->ownerName;
            // $user->email            = $request->email;
            // $user->subscriber_id    = $subscriber->id;
            // $user->password         = Hash::make($request->password);
            // $user->contact_number   = $request->mobile;
            // $user->store_id         = $store->id;
            // $user->save();

            // $pos = new Pos;
            // $pos->pos_name = 'Default-POS';
            // $pos->pos_status = 'Active';
            // $pos->pos_pin = '1234';
            // $pos->store_id = $store->id;
            // $pos->subscriber_id = $subscriber->id;
            // $pos->created_by = $subscriber->org_name;
            // $pos->save();
            // }
            {
                $subscriberid =  DB::table("subscribers")->select('*')->where('contact_number', $request->mobile)->first()->id;
                $data = SignUpRequest::where('mobile', $request->mobile)->first();
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
                'message' => 'Success'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Account Exist'
            ]);
        }
    }
}
