<?php

namespace Tests\Feature;

use App\Models\BmiRecord;
use App\Models\SmsNotification;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_access_health_record_search(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/health-records')->assertForbidden();
    }

    public function test_clinic_staff_can_access_health_record_search(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_CLINIC_STAFF]);

        $this->actingAs($staff)->get('/health-records')->assertOk();
    }

    public function test_clinic_staff_health_records_are_empty_until_search(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_CLINIC_STAFF]);
        $student = Student::create([
            'student_number' => 'S-SEARCH',
            'first_name' => 'Searchable',
            'last_name' => 'Student',
            'status' => 'active',
        ]);

        $this->actingAs($staff)
            ->get('/health-records')
            ->assertOk()
            ->assertSee('Search a student first')
            ->assertDontSee($student->student_number);

        $this->actingAs($staff)
            ->get('/health-records?search=S-SEARCH')
            ->assertOk()
            ->assertSee($student->student_number);
    }

    public function test_admin_profile_view_hides_health_records(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $student = Student::create([
            'student_number' => 'S-ADMIN',
            'first_name' => 'Admin',
            'last_name' => 'Visible',
            'status' => 'active',
        ]);
        $student->healthProfile()->create([
            'blood_type' => 'O+',
            'allergies' => ['Peanuts'],
        ]);

        $this->actingAs($admin)
            ->get(route('profiles.show', $student))
            ->assertOk()
            ->assertSee('Basic student information')
            ->assertDontSee('Health profile')
            ->assertDontSee('Peanuts');
    }

    public function test_parent_can_only_view_assigned_child_profile(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $assigned = Student::create([
            'student_number' => 'S-100',
            'first_name' => 'Assigned',
            'last_name' => 'Student',
            'status' => 'active',
        ]);
        $other = Student::create([
            'student_number' => 'S-200',
            'first_name' => 'Other',
            'last_name' => 'Student',
            'status' => 'active',
        ]);

        $parent->children()->attach($assigned);

        $this->actingAs($parent)->get(route('profiles.show', $assigned))->assertOk();
        $this->actingAs($parent)->get(route('profiles.show', $other))->assertForbidden();
    }

    public function test_bmi_record_calculates_and_stores_category(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_CLINIC_STAFF]);
        $student = Student::create([
            'student_number' => 'S-300',
            'first_name' => 'Bmi',
            'last_name' => 'Student',
            'status' => 'active',
        ]);

        $this->actingAs($staff)->post(route('bmi-records.store', $student), [
            'school_year' => 2026,
            'height_cm' => 170,
            'weight_kg' => 65,
            'checked_at' => '2026-05-24',
        ])->assertRedirect(route('profiles.show', $student));

        $this->assertDatabaseHas(BmiRecord::class, [
            'student_id' => $student->id,
            'school_year' => 2026,
            'category' => 'Normal',
        ]);
    }

    public function test_sent_home_clinic_visit_queues_sms_notification(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_CLINIC_STAFF]);
        $student = Student::create([
            'student_number' => 'S-400',
            'first_name' => 'Sick',
            'last_name' => 'Student',
            'guardian_name' => 'Parent User',
            'guardian_contact' => '09171234567',
            'status' => 'active',
        ]);

        $this->actingAs($staff)->post(route('clinic-visits.store', $student), [
            'visited_at' => '2026-05-24 09:00:00',
            'complaint' => 'Fever',
            'disposition' => 'sent_home',
            'notify_parent' => '1',
        ])->assertRedirect(route('profiles.show', $student));

        $this->assertDatabaseHas(SmsNotification::class, [
            'student_id' => $student->id,
            'recipient_phone' => '09171234567',
            'status' => 'queued',
        ]);
    }
}
