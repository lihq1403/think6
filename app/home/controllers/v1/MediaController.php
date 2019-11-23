<?php

namespace app\home\controllers\v1;

use app\common\controllers\HomeBaseController;
use app\common\exceptions\DataValidateException;
use app\common\repositories\MediaRepository;
use think\Request;

class MediaController extends HomeBaseController
{
    /**
     * @param Request $request
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidateException
     * @throws \app\common\exceptions\SystemErrorException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function localMediaUpload(Request $request)
    {
        $files = $request->file();

        if (empty($files)) {
            throw new DataValidateException('files require');
        }

        $save_name = [];
        foreach ($files as $name => $file) {
            if (empty($file)) {
                $save_name[$name] = [];
                continue;
            }

            // 如果历史记录有相同文件，则直接返回就好了
            if ($history_file = MediaRepository::instance()->historyFileHash($file)) {
                $save_name[$name] = $history_file;
                continue;
            }

            // 验证文件规则
            MediaRepository::instance()->fileValidate($file);

            // 上传并保存记录
            $file_info = MediaRepository::instance()->mediaUpload($file, '', 'public');

            $save_name[$name] = $file_info;
        }

        return $this->successResponse('upload success', $save_name);
    }
}