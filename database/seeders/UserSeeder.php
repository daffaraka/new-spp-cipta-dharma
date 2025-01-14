<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create('id_ID');
        $agama = ['Islam', 'Kristen', 'Katholik', 'Hindu', 'Budha'];
        $jk = ['Laki-laki', 'Perempuan'];
        $angkatan = [2019,2020,2021, 2022, 2023, 2024];
        $kelas = ['10A', '10B', '11A', '11B', '12A', '12B'];


        $admin =  User::create(
            [
                'nama' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ]
        );

        $admin->assignRole('petugas');

        $kepsek = User::create(
            [
                'nama' => 'Kepsek',
                'email' => 'kepsek@gmail.com',
                'password' => bcrypt('password')
            ]
        );

        $kepsek->assignRole('KepalaSekolah');


        $jk = ['Laki-laki', 'Perempuan'];
        for ($i = 1; $i < 50; $i++) {
            $user = User::create(
                [
                    'nama' => 'User ' . $i,
                    'email' => 'user' . $i . '@gmail.com',
                    'nis' => $faker->unique()->numerify('######'),
                    'nisn' => $faker->unique()->numerify('##########'),
                    'jenis_kelamin' => $jk[array_rand($jk)],
                    'password' => bcrypt('password'),
                    'tanggal_lahir' => $faker->date('Y-m-d'),
                    'nama_wali' => $faker->name,
                    'alamat' => $faker->address,
                    'no_telp' => $faker->phoneNumber,
                    'angkatan' => $angkatan[array_rand($angkatan)],
                    'kelas' => $kelas[array_rand($kelas)],
                    'created_at' => now(),
                ]
            );

            $user->assignRole('SiswaOrangTua');
        }
    }
}
