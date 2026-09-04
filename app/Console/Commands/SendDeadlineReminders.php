<?php

namespace App\Console\Commands;

use App\Mail\RiskAssessmentMail;
use App\Models\RiskAssessment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDeadlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:send-reminders';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Send email reminder 7 days before equipment rental deadline expires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting deadline reminder process...');

        $sevenDaysFromNow = now()->addDays(7)->startOfDay();
        $sevenDaysFromNowEnd = now()->addDays(7)->endOfDay();

        $riskAssessments = RiskAssessment::whereBetween('batas_waktu_peminjaman', [$sevenDaysFromNow, $sevenDaysFromNowEnd])
            ->where('batas_waktu_peminjaman', '>', now())
            ->whereNull('pengajuan_perpanjangan')
            ->with('user')
            ->get();

        $count = 0;

        foreach ($riskAssessments as $riskAssessment) {
            try {
                Mail::to($riskAssessment->user->Email)->send(
                    new RiskAssessmentMail($riskAssessment, 'deadline_reminder_7_days')
                );

                $riskAssessment->update([
                    'notifikasi_deadline_terkirim' => true,
                    'tanggal_notifikasi_deadline' => now(),
                ]);

                $count++;
                $this->line(" Reminder sent to {$riskAssessment->nama} ({$riskAssessment->user->Email})");
            } catch (\Exception $e) {
                $this->error(" Failed to send reminder to {$riskAssessment->nama}: ".$e->getMessage());
            }
        }

        $this->info("Reminder sending completed. Total: {$count} emails sent.");
    }
}
