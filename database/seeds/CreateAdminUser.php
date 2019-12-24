<?php

use think\migration\Seeder;

class CreateAdminUser extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            'username' => 'admin',
            'password' => password_encrypt('admin'),
            'status' => 1
        ];
        \app\common\models\AdminUser::create($data);
    }
}