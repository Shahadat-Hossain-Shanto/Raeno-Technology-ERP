<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //crateing Roles
        // $roleAdmin = Role::create([
        //     'name' => 'admin12',
        //     'subscriber_id' => 1121
        // ]);

        // $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        // $roleEditor = Role::create(['name' => 'editor']);

        // $roleManager = Role::create([
        //     'name' => 'manager',
        //     'subscriber_id' => 1
        // ]);

        //permission 
        $permissions = [

            //store permission
            'stores.create.view',
            'stores.create',
            'stores.list',
            'stores.list.view',
            'stores.edit.view',
            'stores.edit',
            'stores.destroy',

            //pos permission
            'pos.create.view',
            'pos.create',
            'pos.list',
            'pos.list.view',
            'pos.edit.view',
            'pos.edit',
            'pos.destroy',

            //category permission
            'category.create.view',
            'category.create',
            'category.list.view',
            'category.list',
            'category.edit.view',
            'category.edit',
            'categories.destroy',

            //sub-category permission
            'subcategory.create.view',
            'subcategory.create',
            'subcategory.list.view',
            'subcategory.list',
            'subcategory.edit.view',
            'subcategory.edit',
            'subcategories.destroy',

            //supplier permission
            'supplier.create.view',
            'supplier.create',
            'supplier.list.view',
            'supplier.list',
            'supplier.edit.view',
            'supplier.edit',
            'suppliers.destroy',

            //brand permission
            'brand.create.view',
            'brand.create',
            'brand.list.view',
            'brand.list',
            'brand.edit.view',
            'brand.edit',
            'brands.destroy',

            //product
            'product.create.view',
            'product.create.showSubcategory',
            'product.create.isTaxExcluded',
            'product.create',
            'product.image.store',
            'product.list.view',
            'product.list',
            'product.edit.view',
            'product.edit.subcategory',
            'image.update',
            'product.update',
            'products.destroy',
          

            //admin permission
            'admin.create',
            'admin.view',
            'admin.edit',
            'admin.delete',

            //role permission
            'admin.roles.create.view',
            'admin.roles.create',
            'admin.roles',
            'admin.roles.edit.view',
            'admin.roles.update',
            'admin.roles.destroy',

            //user
            'user.create.view',
            'user.create',

            //profile permission
            'profile.view',
            'profile.edit',

            //Discount Permission
            'discount.create.view',
            'discount.create',
            'discount.list.view',
            'discount.list',
            'discount.edit.view',
            'discount.edit',
            'discounts.destroy',
            'discount.list.json',
            

            //Vat Permission
            'vat.create.view',
            'vat.create',
            'vat.list.view',
            'vat.list',
            'vat.edit.view',
            'vat.edit',
            'vats.destroy',

            //order Permission
            'order.create',
            'order.list.view',
            'order.list',
            'order.product.list.view',
            'order.product.list',

            //purchase Permission
            'purchase.create.view',
            'purchase.create',
            'purchase.list.view',
            'purchase.list',
            'purchase.product.list.view',
            'purchase.product.list',
            'purchase.edit.view',
            'purchase.edit',
            'purchase.details',
            'purchase.receive',

            //reports Permission
            'reports.create.view',
            'report.create.view',
            'reports.create',
            'employee.reports.create.view',
            'employee.report.create.view',
            'employee.reports.create',
            'due.reports.create.view',
            'due.reports.create',
            'due.reports.details',

            //customer permission
            'customer.create.view',
            'customer.create',
            'customer.list.view',
            'customer.list',
            'customer.edit.view',
            'customer.edit',
            'customer.details',
            'customers.destroy',

            // pos permisiion
            'pos.login.view',
            'pos.view',
            'pos.product.search',
            'pos.product.categories',
            'pos.category-search',
            'pos.product-add',
            'pos.customer-search',
            'pos.discount-search',
            'pos.tax-search',
            'pos.pos-search',
            'pos.pos-login',
            'pos.pos-logout',

            //product-in
            'product.in.view',
            'product.in',


            //product-transfer
            'product.transfer.view',
            'product.transfer',

            //product-in-reports
            'product.in.reports.view',
            'product.in.report.view',
            'product.in.reports',
            'product.in.reports.details',

            //dashboard permission
            'dashboard',

            //batch permission
            'batch.create.view',
            'batch.create',
            'batch.list.view',
            'batch.list',
            'batch.edit.view',
            'batch.edit',
            'batches.destroy',

            //leaf permission
            'leaf.create.view',
            'leaf.create',
            'leaf.list.view',
            'leaf.list',
            'leaf.edit.view',
            'leaf.edit',
            'leaves.destroy',

            //expense-report permission
            'expense.reports.view',
            'expense.reports',
            'expense.report.view',

            //deposit-report permission
            'deposit.reports.view',
            'deposit.reports',
            'deposit.report.view',
            'deposit.reports.details',




           
            
            
            




        ];


        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {

            // Create Permission
            $permission = Permission::create(['name' => $permissions[$i]]);
            // $roleAdmin->givePermissionTo($permission);
            // $permission->assignRole($roleAdmin);
        }


        // Do same for the admin guard for tutorial purposes

    }
}
