<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Semester;

class DatabaseSeeder extends Seeder
{
 public function run(): void {
  $kelas=Classroom::firstOrCreate(['name'=>'X RPL 1'],['level'=>'X','school_level'=>'SD']);
  User::firstOrCreate(['username'=>'admin'],['name'=>'Administrator','password'=>'password','role'=>'admin','school_level'=>'SD']);
  User::firstOrCreate(['username'=>'guru'],['name'=>'Guru Demo','password'=>'password','role'=>'guru','school_level'=>'SD','nip'=>'19800001']);
  if(env('SUPER_ADMIN_USERNAME') && env('SUPER_ADMIN_PASSWORD')) User::firstOrCreate(['username'=>env('SUPER_ADMIN_USERNAME')],['name'=>env('SUPER_ADMIN_NAME','Ketua Yayasan'),'password'=>env('SUPER_ADMIN_PASSWORD'),'role'=>'super_admin','school_level'=>null]);
  User::firstOrCreate(['username'=>'siswa'],['name'=>'Siswa Demo','password'=>'password','role'=>'siswa','school_level'=>'SD','nis'=>'20260001','classroom_id'=>$kelas->id]);
  foreach(['Matematika','Bahasa Indonesia','Bahasa Inggris','Informatika'] as $i=>$name) Subject::firstOrCreate(['name'=>$name],['code'=>'MP'.($i+1)]);
  $year=AcademicYear::firstOrCreate(['name'=>'2026/2027'],['is_active'=>true]);
  Semester::firstOrCreate(['name'=>'Ganjil','academic_year_id'=>$year->id],['is_active'=>true]);
  Semester::firstOrCreate(['name'=>'Genap','academic_year_id'=>$year->id],['is_active'=>false]);
 }
}