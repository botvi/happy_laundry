<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\auth\ForgotPasswordController;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan OTP yang sudah kadaluarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new ForgotPasswordController();
        $deletedCount = $controller->cleanupExpiredOtps();
        
        $this->info("Berhasil membersihkan {$deletedCount} OTP yang sudah kadaluarsa");
        
        return 0;
    }
}
