<?php

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[
            {"name":"super_admin","guard_name":"web","permissions":["view_classroom","view_any_classroom","create_classroom","update_classroom","restore_classroom","restore_any_classroom","replicate_classroom","reorder_classroom","delete_classroom","delete_any_classroom","force_delete_classroom","force_delete_any_classroom","view_day","view_any_day","create_day","update_day","restore_day","restore_any_day","replicate_day","reorder_day","delete_day","delete_any_day","force_delete_day","force_delete_any_day","view_lesson","view_any_lesson","create_lesson","update_lesson","restore_lesson","restore_any_lesson","replicate_lesson","reorder_lesson","delete_lesson","delete_any_lesson","force_delete_lesson","force_delete_any_lesson","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_teacher","view_any_teacher","create_teacher","update_teacher","restore_teacher","restore_any_teacher","replicate_teacher","reorder_teacher","delete_teacher","delete_any_teacher","force_delete_teacher","force_delete_any_teacher","view_timeslot","view_any_timeslot","create_timeslot","update_timeslot","restore_timeslot","restore_any_timeslot","replicate_timeslot","reorder_timeslot","delete_timeslot","delete_any_timeslot","force_delete_timeslot","force_delete_any_timeslot","view_timetable","view_any_timetable","create_timetable","update_timetable","restore_timetable","restore_any_timetable","replicate_timetable","reorder_timetable","delete_timetable","delete_any_timetable","force_delete_timetable","force_delete_any_timetable","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_EditProfile","page_MyTimetable","widget_AdminWidgets","widget_TeacherWidgets","widget_ScheduleToday"]},
            {"name":"admin","guard_name":"web","permissions":["view_classroom","view_any_classroom","create_classroom","update_classroom","restore_classroom","restore_any_classroom","replicate_classroom","reorder_classroom","delete_classroom","delete_any_classroom","force_delete_classroom","force_delete_any_classroom","view_day","view_any_day","create_day","update_day","restore_day","restore_any_day","replicate_day","reorder_day","delete_day","delete_any_day","force_delete_day","force_delete_any_day","view_lesson","view_any_lesson","create_lesson","update_lesson","restore_lesson","restore_any_lesson","replicate_lesson","reorder_lesson","delete_lesson","delete_any_lesson","force_delete_lesson","force_delete_any_lesson","view_teacher","view_any_teacher","create_teacher","update_teacher","restore_teacher","restore_any_teacher","replicate_teacher","reorder_teacher","delete_teacher","delete_any_teacher","force_delete_teacher","force_delete_any_teacher","view_timeslot","view_any_timeslot","create_timeslot","update_timeslot","restore_timeslot","restore_any_timeslot","replicate_timeslot","reorder_timeslot","delete_timeslot","delete_any_timeslot","force_delete_timeslot","force_delete_any_timeslot","view_timetable","view_any_timetable","create_timetable","update_timetable","restore_timetable","restore_any_timetable","replicate_timetable","reorder_timetable","delete_timetable","delete_any_timetable","force_delete_timetable","force_delete_any_timetable","view_user","view_any_user"]},
            {"name":"teacher","guard_name":"web","permissions":["view_teacher","view_any_teacher","view_timetable","view_any_timetable","view_classroom","view_any_classroom"]},
            {"name":"student","guard_name":"web","permissions":["view_teacher","view_any_teacher","view_timetable","view_any_timetable"]}
        ]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        // Create super administrator if not exists
        $superAdministratorUser = User::firstOrCreate(
            ['email' => 'superadmin@mail.com'],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('man2makassar'),
                'email_verified_at' => now(),
            ]
        );

        // Create administrator if not exists
        $administratorUser = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('man2makassar'),
                'email_verified_at' => now(),
            ]
        );

        // Create student if not exists
        $studentUser = User::firstOrCreate(
            ['email' => 'siswa@mail.com'],
            [
                'name' => 'Siswa Man 2 Makassar',
                'password' => bcrypt('man2makassar'),
                'email_verified_at' => now(),
            ]
        );

        // Create teacher if not exists
        $teacherUser = User::firstOrCreate(
            ['email' => 'dewi@mail.com'],
            [
                'name' => 'Dewi Rahma, S.Pd',
                'password' => bcrypt('man2makassar'),
                'email_verified_at' => now(),
            ]
        );

        // Assign super administrator user to super_admin role
        $superAdministratorUser->assignRole('super_admin');

        // Assign administrator user to admin role
        $administratorUser->assignRole('admin');

        // Assign teacher user to teacher role
        $studentUser->assignRole('student');

        // Assign teacher user to teacher role
        $teacherUser->assignRole('teacher');

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
