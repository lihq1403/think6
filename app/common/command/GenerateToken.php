<?php
/**
 * Created by PhpStorm.
 * User: author lihq1403 <lihaiqing1994@163.com>
 * Date: 2018/10/12
 * Time: 11:51
 */
namespace app\common\command;

use app\common\repositories\AdminUserRepository;
use app\common\repositories\UserRepository;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class GenerateToken extends Command
{
    protected function configure()
    {
        $this->setName('generate_token')
            ->addOption('scene', null, Option::VALUE_REQUIRED, '选择场景 home admin')
            ->addOption('uid', null, Option::VALUE_REQUIRED, '用户id')
            ->setDescription('生成登录token');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $scene = trim($input->getOption('scene'));
        if (empty($scene)|| !in_array($scene, ['home', 'admin'])) {
            $output->writeln('请输入正确的scene');
            return ;
        }
        switch ($scene) {
            case 'home':
                $scene = AdminUserRepository::instance()->getLoginScene();
                break;
            case 'admin':
                $scene = UserRepository::instance()->getLoginScene();
                break;
        }

        $uid = trim($input->getOption('uid'));
        if (empty($uid)) {
            $output->writeln('请输入正确的uid');
            return ;
        }

        //生成token
        $token = app('jwt_tool')->setScene($scene)->IssueToken($uid, 365*24*60*60);

        $output->writeln($token);
    }
}