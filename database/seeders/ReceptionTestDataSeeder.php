<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceptionTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Add courses if not exist (avoid duplicate)
        if (!DB::table("recp_courses")->where("title", "Advanced Computer")->exists()) {
            DB::table("recp_courses")->insert([
                ["title" => "Advanced Computer", "description" => "Advanced skills", "fee_standard" => 10000.00, "duration_days" => 45, "is_active" => 1, "created_at" => now(), "updated_at" => now()],
                ["title" => "Mobile App Development", "description" => "Learn app dev", "fee_standard" => 20000.00, "duration_days" => 90, "is_active" => 1, "created_at" => now(), "updated_at" => now()],
            ]);
        }

        // Add batches if not exist
        if (!DB::table("recp_batches")->where("name", "Oct-2025 A")->exists()) {
            $course1_id = DB::table("recp_courses")->where("title", "Advanced Computer")->first()->id ?? 1;
            $course2_id = DB::table("recp_courses")->where("title", "Mobile App Development")->first()->id ?? 2;
            DB::table("recp_batches")->insert([
                ["course_id" => $course1_id, "name" => "Oct-2025 A", "start_date" => "2025-10-01", "end_date" => "2025-10-31", "is_active" => 1, "created_at" => now(), "updated_at" => now()],
                ["course_id" => $course2_id, "name" => "Oct-2025 B", "start_date" => "2025-10-01", "end_date" => "2025-11-30", "is_active" => 1, "created_at" => now(), "updated_at" => now()],
            ]);
        }

        // Get existing student and batch IDs for test
        $student_id = DB::table("recp_students")->first()->id ?? 1;
        $batch_id = DB::table("recp_batches")->first()->id ?? 1;

        // Add enrollment if not exist
        if (!DB::table("recp_enrollments")->where("student_id", $student_id)->exists()) {
            DB::table("recp_enrollments")->insert([
                ["student_id" => $student_id, "batch_id" => $batch_id, "enroll_date" => "2025-08-30", "fee_agreed" => 5000.00, "discount" => 500.00, "status" => "enrolled", "created_at" => now(), "updated_at" => now()],
            ]);
        }

        // Add payment if not exist
        $enrollment_id = DB::table("recp_enrollments")->first()->id;
        if (!DB::table("recp_payments")->where("enrollment_id", $enrollment_id)->exists()) {
            DB::table("recp_payments")->insert([
                ["enrollment_id" => $enrollment_id, "amount" => 2000.00, "method" => "cash", "paid_at" => "2025-08-30 11:00:00", "received_by_admin_id" => 1, "created_at" => now(), "updated_at" => now()],
            ]);
        }

        // Add document if not exist
        if (!DB::table("recp_documents")->where("student_id", $student_id)->exists()) {
            DB::table("recp_documents")->insert([
                ["student_id" => $student_id, "doc_type" => "ID Card", "fee" => 100.00, "issued_at" => "2025-08-30", "handled_by_admin_id" => 1, "created_at" => now(), "updated_at" => now()],
            ]);
        }
    }
};
