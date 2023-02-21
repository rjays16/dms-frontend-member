<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('membership_types')->updateOrInsert([
            'id' => 1
        ], [
            'type_name' => 'DPBR'
        ]);

        DB::table('membership_types')->updateOrInsert([
            'id' => 2
        ], [
            'type_name' => 'FPCR'
        ]);

        DB::table('membership_types')->updateOrInsert([
            'id' => 3
        ], [
            'type_name' => 'FUSP'
        ]);

        DB::table('membership_types')->updateOrInsert([
            'id' => 4
        ], [
            'type_name' => 'FCT-MRISP'
        ]);

        DB::table('membership_types')->updateOrInsert([
            'id' => 5
        ], [
            'type_name' => 'FPSVIR'
        ]);

        DB::table('membership_types')->updateOrInsert([
            'id' => 6
        ], [
            'type_name' => 'FPROS'
        ]);
    }
}
