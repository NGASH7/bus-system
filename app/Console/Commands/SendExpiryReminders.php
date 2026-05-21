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
                $daysLeft = (int) $today->diffInDays($expiry, false);
                $title = "Expiry Warning: {$type}";
                $messageText = "REMINDER: Bus {$bus->plate_number} {$type} expires in {$daysLeft} days ({$expiry->format('d M, Y')}). Please renew it immediately.";
                
                $this->notifyAdminsAndDriver($messageText, $admins, $bus->driver, $title, "Bus {$bus->plate_number}", $type, $expiry, $daysLeft, false);
            } elseif ($expiry && $expiry->isPast()) {
                $daysLeft = (int) $today->diffInDays($expiry, false);
                $title = "⚠️ Expiry Alert: {$type} Expired";
                $messageText = "ALERT: Bus {$bus->plate_number} {$type} HAS EXPIRED on {$expiry->format('d M, Y')}. Renew NOW!";
                
                $this->notifyAdminsAndDriver($messageText, $admins, $bus->driver, $title, "Bus {$bus->plate_number}", $type, $expiry, $daysLeft, true);
            }
        }
    }

    protected function checkDriverLicense($driver, $warningDate, $today, $admins)
    {
        $expiry = $driver->license_expiry;
        
        if ($expiry && $expiry->isBetween($today, $warningDate)) {
            $daysLeft = (int) $today->diffInDays($expiry, false);
            $title = "Expiry Warning: Driver License";
            $messageText = "REMINDER: Driver {$driver->name}'s License expires in {$daysLeft} days ({$expiry->format('d M, Y')}). Please renew it.";
            
            $this->notifyAdminsAndDriver($messageText, $admins, $driver, $title, "Driver {$driver->name}", "Driver License", $expiry, $daysLeft, false);
        } elseif ($expiry && $expiry->isPast()) {
            $daysLeft = (int) $today->diffInDays($expiry, false);
            $title = "⚠️ Expiry Alert: License Expired";
            $messageText = "ALERT: Driver {$driver->name}'s License HAS EXPIRED on {$expiry->format('d M, Y')}. Renew NOW!";
            
            $this->notifyAdminsAndDriver($messageText, $admins, $driver, $title, "Driver {$driver->name}", "Driver License", $expiry, $daysLeft, true);
        }
    }

    protected function notifyAdminsAndDriver(
        string $messageText,
        $admins,
        $driver,
        string $title,
        string $entityName,
        string $itemType,
        $expiryDate,
        int $daysLeft,
        bool $isExpired
    ) {
        // Notify Admins
        foreach ($admins as $admin) {
            if ($admin->phone_number) {
                $this->smsService->send($admin->phone_number, $messageText);
            }
            if ($admin->email) {
                try {
                    Mail::to($admin->email)->send(new \App\Mail\ExpiryReminder(
                        $title,
                        $messageText,
                        $entityName,
                        $itemType,
                        $expiryDate,
                        $daysLeft,
                        $isExpired,
                        '/admin/dashboard'
                    ));
                } catch (\Exception $e) {
                    Log::error("Failed to send expiry mail to admin {$admin->email}: " . $e->getMessage());
                }
            }
            $this->info("Expiry reminder sent to Admin {$admin->name}: {$messageText}");
            Log::info("Expiry reminder sent to Admin {$admin->name}: {$messageText}");
        }

        // Notify Driver
        if ($driver) {
            if ($driver->phone_number) {
                $this->smsService->send($driver->phone_number, $messageText);
            }
            if ($driver->email) {
                try {
                    Mail::to($driver->email)->send(new \App\Mail\ExpiryReminder(
                        $title,
                        $messageText,
                        $entityName,
                        $itemType,
                        $expiryDate,
                        $daysLeft,
                        $isExpired,
                        '/driver/dashboard'
                    ));
                } catch (\Exception $e) {
                    Log::error("Failed to send expiry mail to driver {$driver->email}: " . $e->getMessage());
                }
            }
            $this->info("Expiry reminder sent to Driver {$driver->name}: {$messageText}");
            Log::info("Expiry reminder sent to Driver {$driver->name}: {$messageText}");
        }
    }
}
