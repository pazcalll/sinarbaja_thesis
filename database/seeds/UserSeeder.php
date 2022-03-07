<?php

use App\Agent;
use App\Customer;
use App\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Barokah Lancar Jaya',
                'address' => 'Jl Soehat, Jakarta',
                'no_handphone' => '0812345678910',
                'email' => 'barokah@gmail.com',
                'password' => '$2b$10$BXLVQH7sPvyh8UzaRuTL/OqMEVzeBylM110FL1yi8R02w.eXUzX1.',
                'group_id' => 'ADMINISTRATOR'
            ],[
                'name' => 'Seru Lancar Jaya',
                'address' => 'Jl Soehat, Kediri',
                'no_handphone' => '0812345678910',
                'email' => 'lancar@gmail.com',
                'password' => '$2b$10$BXLVQH7sPvyh8UzaRuTL/OqMEVzeBylM110FL1yi8R02w.eXUzX1.',
                'group_id' => 'AGENT'
            ],[
                'name' => 'Barokjaya',
                'address' => 'Jl Soehat, Malang',
                'no_handphone' => '0812345678910',
                'email' => 'barok@gmail.com',
                'password' => '$2b$10$BXLVQH7sPvyh8UzaRuTL/OqMEVzeBylM110FL1yi8R02w.eXUzX1.',
                'group_id' => 'CUSTOMER'
            ],[
                'name' => 'Mulyono Santosa',
                'address' => 'Jl Soehat, Bengkulu',
                'no_handphone' => '0812345678910',
                'email' => 'mulyono@gmail.com',
                'password' => '$2b$10$BXLVQH7sPvyh8UzaRuTL/OqMEVzeBylM110FL1yi8R02w.eXUzX1.',
                'group_id' => 'DRIVER'
            ],[
                'name' => 'Dodit Santoso',
                'address' => 'Jl Soehat, Sidoarjo',
                'no_handphone' => '0812345678910',
                'email' => 'dodit@gmail.com',
                'password' => '$2b$10$BXLVQH7sPvyh8UzaRuTL/OqMEVzeBylM110FL1yi8R02w.eXUzX1.',
                'group_id' => 'DRIVER'
            ],
        ]);

        Agent::create([
            'id' => 2,
            'limit' => 1000000000
        ]);

        Customer::create([
            'id' => 3
        ]);

        Driver::create([
            'id' => 4,
            'status' => 'ACTIVE'
        ]);

        Driver::create([
            'id' => 5,
            'status' => 'NON-ACTIVE'
        ]);
    }
}
