<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'ragib@gmail.com')->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = "ragib";
            $user->email = "ragib@gmail.com";
            $user->contact_number = "01797644440";
            $user->password = "12345678";
            $user->subscriber_id = 1;
            $user->save();
        }
    }
}
