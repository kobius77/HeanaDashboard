use Illuminate\Database\Seeder; class CreateUserSeeder extends Seeder { public function run() { $this->call(User::create(["email" => 'markus@photing.com', "password" => bcrypt('asdfasdf')])); } }
