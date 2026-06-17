<?php

namespace Database\Seeders;

use App\Models\RiskAssessment;
use Illuminate\Database\Seeder;

class GenerateRaIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate ID RA untuk semua risk assessment yang sudah disetujui tapi belum punya ID RA
        $riskAssessments = RiskAssessment::where('status', 'disetujui')
            ->whereNull('id_ra')
            ->get();

        foreach ($riskAssessments as $ra) {
            $ra->generateIdRa();
            $this->command->info("Generated ID RA: {$ra->id_ra} for RA #{$ra->id}");
        }

        $this->command->info('Selesai! Total: '.$riskAssessments->count().' Risk Assessment');
    }
}
