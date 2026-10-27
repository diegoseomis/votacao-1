<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Hash, Validator};
class CreateAdmin extends Command {
    protected $signature = 'gallery:admin {email}';
    protected $description = 'Cria um administrador da galeria';
    public function handle(): int {
        $data = ['email' => mb_strtolower(trim($this->argument('email'))), 'name' => $this->ask('Nome'), 'password' => $this->secret('Senha (mínimo de 12 caracteres)')];
        $validator = Validator::make($data, ['email' => 'required|email|unique:users,email', 'name' => 'required|string|max:255', 'password' => 'required|string|min:12']);
        if ($validator->fails()) { $this->error($validator->errors()->first()); return self::FAILURE; }
        $user = new User($data); $user->password = Hash::make($data['password']); $user->is_admin = true; $user->save();
        $this->info('Administrador criado. Acesse /login.'); return self::SUCCESS;
    }
}
