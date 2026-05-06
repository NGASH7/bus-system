<?php

namespace App\Console\Commands;

use App\Models\Bus;
use App\Models\User;
use App\Services\CelcomSmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily email and SMS reminders for expiring insurance, licenses, and inspections.';

    protected $smsService;

    public function __construct(CelcomSmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting expiry checks...');
        
        $warningDate = Carbon::now()->addDays(7);
        $today = Carbon::now();
        
        $admins = User::where('role', 'admin')->get();
        
        // 1. Check Bus Insurance, License, and Inspection
        $buses = Bus::with('driver')->get();
        
        foreach ($buses as $bus) {
            $this->checkBusExpiries($bus, $warningDate, $today, $admins);
        }
        
        // 2. Check Driver Licenses
        $drivers = User::where('role', 'driver')->get();
        
        foreach ($drivers as $driver) {
            $this->checkDriverLicense($driver, $warningDate, $today, $admins);
        }
        
        $this->info('Expiry checks completed.');
    }

    protected function checkBusExpiries($bus, $warningDate, $today, $admins)
    {
        $expiries = [
            'Insurance' => $bus->insurance_expiry,
            'Road License' => $bus->license_expiry,
            'Inspection' => $bus->inspection_expiry,
        ];

        foreach ($expiries as $type => $expiry) {
            if ($expiry && $expiry->isBetween($today, $warningDate)) {
                $daysLeft = $today->diffInDays($expiry, false);
                $message = "REMINDER: Bus {$bus->plate_number} {$type} expires in {$daysLeft} days ({$expiry->format('d M, Y')}). Please renew it immediately.";
                
                $this->notifyAdminsAndDriver($message, $admins, $bus->driver);
            } elseif ($expiry && $expiry->isPast()) {
                $message = "ALERT: Bus {$bus->plate_number} {$type} HAS EXPIRED on {$expiry->format('d M, Y')}. Renew NOW!";
                $this->notifyAdminsAndDriver($message, $admins, $bus->driver);
            }
        }
    }

    protected function checkDriverLicense($driver, $warningDate, $today, $admins)
    {
        $expiry = $driver->license_expiry;
        
        if ($expiry && $expiry->isBetween($today, $warningDate)) {
            $daysLeft = $today->diffInDays($expiry, false);
            $message = "REMINDER: Driver {$driver->name}'s License expires in {$daysLeft} days ({$expiry->format('d M, Y')}). Please renew it.";
            
            $this->notifyAdminsAndDriver($message, $admins, $driver);
        } elseif ($expiry && $expiry->isPast()) {
            $message = "ALERT: Driver {$driver->name}'s License HAS EXPIRED on {$expiry->format('d M, Y')}. Renew NOW!";
            $this->notifyAdminsAndDriver($message, $admins, $driver);
        }
    }

    protected function notifyAdminsAndDriver($message, $admins, $driver = null)
    {
        $subject = "Mwigito Excel: Expiry Reminder";

        // Notify Admins
        foreach ($admins as $admin) {
            if ($admin->phone_number) {
                $this->smsService->send($admin->phone_number, $message);
            }
            if ($admin->email) {
                Mail::raw($message, function ($mail) use ($admin, $subject) {
                    $mail->to($admin->email)->subject($subject);
                });
            }
            $this->info("Expiry reminder sent to Admin {$admin->name}: {$message}");
            Log::info("Expiry reminder sent to Admin {$admin->name}: {$message}");
        }

        // Notify Driver
        if ($driver) {
            if ($driver->phone_number) {
                $this->smsService->send($driver->phone_number, $message);
            }
            if ($driver->email) {
                Mail::raw($message, function ($mail) use ($driver, $subject) {
                    $mail->to($driver->email)->subject($subject);
                });
            }
            $this->info("Expiry reminder sent to Driver {$driver->name}: {$message}");
            Log::info("Expiry reminder sent to Driver {$driver->name}: {$message}");
        }
    }
}
