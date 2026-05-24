<?php

namespace Database\Seeders;

use App\Models\ClinicVisit;
use App\Models\DentalRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@studenthealth.test'],
            [
                'name' => 'Admin User',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('password'),
            ],
        );

        $clinicStaff = User::updateOrCreate(
            ['email' => 'clinic@studenthealth.test'],
            [
                'name' => 'Clinic Staff',
                'role' => User::ROLE_CLINIC_STAFF,
                'password' => Hash::make('password'),
            ],
        );

        $parent = User::updateOrCreate(
            ['email' => 'parent@studenthealth.test'],
            [
                'name' => 'Parent User',
                'role' => User::ROLE_PARENT,
                'password' => Hash::make('password'),
            ],
        );

        $students = [
            ['SHS-2026-001', 'Maria', 'Santos', '7', 'A', 'F', 'O+', ['Peanuts'], ['Asthma'], ['Inhaler'], ['MMR', 'Hepatitis B']],
            ['SHS-2026-002', 'Jose', 'Reyes', '7', 'B', 'M', 'A+', [], [], [], ['MMR']],
            ['SHS-2026-003', 'Ana', 'Cruz', '8', 'A', 'F', 'B+', ['Seafood'], [], ['Cetirizine'], ['MMR', 'Varicella']],
            ['SHS-2026-004', 'Miguel', 'Garcia', '8', 'C', 'M', 'AB+', [], ['Eczema'], [], ['MMR']],
            ['SHS-2026-005', 'Lara', 'Dela Cruz', '9', 'A', 'F', 'O-', [], [], [], ['MMR', 'Hepatitis B']],
            ['SHS-2026-006', 'Carlo', 'Mendoza', '9', 'B', 'M', 'A-', ['Dust'], ['Allergic rhinitis'], ['Loratadine'], ['MMR']],
            ['SHS-2026-007', 'Sofia', 'Flores', '10', 'A', 'F', 'B-', [], [], [], ['MMR', 'HPV']],
            ['SHS-2026-008', 'Nico', 'Ramos', '10', 'B', 'M', 'O+', [], [], [], ['MMR']],
            ['SHS-2026-009', 'Bianca', 'Torres', '11', 'STEM', 'F', 'A+', ['Ibuprofen'], [], [], ['MMR']],
            ['SHS-2026-010', 'Gabriel', 'Villanueva', '12', 'ABM', 'M', 'B+', [], [], [], ['MMR', 'Hepatitis B']],
        ];

        DB::transaction(function () use ($students, $parent, $clinicStaff): void {
            foreach ($students as $index => [$number, $first, $last, $grade, $section, $sex, $bloodType, $allergies, $conditions, $medications, $immunizations]) {
                $student = Student::updateOrCreate(
                    ['student_number' => $number],
                    [
                        'first_name' => $first,
                        'last_name' => $last,
                        'birth_date' => now()->subYears(12 + ($index % 6))->subMonths($index)->toDateString(),
                        'sex' => $sex,
                        'grade_level' => $grade,
                        'section' => $section,
                        'guardian_name' => 'Parent User',
                        'guardian_contact' => '09171234567',
                        'emergency_contact_name' => 'Emergency Contact '.$first,
                        'emergency_contact_phone' => '099900000'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                        'status' => 'active',
                    ],
                );

                $student->healthProfile()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'blood_type' => $bloodType,
                        'allergies' => $allergies,
                        'chronic_conditions' => $conditions,
                        'medications' => $medications,
                        'immunizations' => $immunizations,
                        'physician_name' => 'Dr. Health Demo',
                        'physician_contact' => '028880000',
                        'notes' => 'Demo health profile for local review.',
                    ],
                );

                $height = 138 + ($index * 4);
                $weight = 32 + ($index * 3);
                $bmi = round($weight / (($height / 100) ** 2), 2);

                $student->bmiRecords()->updateOrCreate(
                    ['school_year' => 2026],
                    [
                        'recorded_by' => $clinicStaff->id,
                        'height_cm' => $height,
                        'weight_kg' => $weight,
                        'bmi' => $bmi,
                        'category' => $this->bmiCategory($bmi),
                        'checked_at' => now()->subDays($index + 1)->toDateString(),
                    ],
                );

                $student->clinicVisits()->updateOrCreate(
                    ['visited_at' => now()->subDays($index + 2)->setTime(9 + ($index % 4), 0)->toDateTimeString()],
                    [
                        'complaint' => $index % 3 === 0 ? 'Fever and headache' : 'Minor stomach pain',
                        'assessment' => 'Vitals checked and student observed.',
                        'treatment' => 'Rest, hydration, and parent notification if symptoms persist.',
                        'temperature' => $index % 3 === 0 ? 38.2 : 36.8,
                        'blood_pressure' => '110/70',
                        'pulse_rate' => 78 + $index,
                        'nurse_name' => $clinicStaff->name,
                        'disposition' => $index % 3 === 0 ? ClinicVisit::DISPOSITION_SENT_HOME : 'returned_to_class',
                        'follow_up_at' => now()->addDays(3)->toDateTimeString(),
                    ],
                );

                foreach ([11, 16, 26] as $toothOffset => $toothCode) {
                    $student->dentalRecords()->updateOrCreate(
                        [
                            'tooth_code' => (string) ($toothCode + ($index % 2)),
                            'recorded_at' => now()->subDays($toothOffset + $index)->toDateString(),
                        ],
                        [
                            'recorded_by' => $clinicStaff->id,
                            'condition' => DentalRecord::CONDITIONS[($index + $toothOffset) % count(DentalRecord::CONDITIONS)],
                            'notes' => 'Demo dental finding.',
                        ],
                    );
                }

                if ($index < 4) {
                    $parent->children()->syncWithoutDetaching([
                        $student->id => ['relationship' => 'Parent'],
                    ]);
                }
            }
        });

        $admin->children()->detach();
        $clinicStaff->children()->detach();
    }

    private function bmiCategory(float $bmi): string
    {
        return match (true) {
            $bmi < 18.5 => 'Underweight',
            $bmi < 25 => 'Normal',
            $bmi < 30 => 'Overweight',
            default => 'Obese',
        };
    }
}
