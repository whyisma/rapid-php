<?php


namespace application\dao;


use application\model\AppUserModel;
use Exception;
use rapidPHP\library\core\app\DBDao;
use ReflectionException;

class UserDao extends DBDao
{

    public function __construct()
    {
        parent::__construct(AppUserModel::class);
    }

    /**
     * 通过telephone获取用户信息
     * @param $nickname
     * @return AppUserModel
     * @throws ReflectionException
     * @throws Exception
     */
    public function getUserInfoByNickname($nickname): ?AppUserModel
    {
        /** @var AppUserModel $userModel */
        $userModel = parent::get()->where('nickname', $nickname)
            ->where('isDelete', 0)
            ->get()->getInstance(AppUserModel::class);

        return $userModel;
    }


    /**
     * 添加用户
     * @param AppUserModel $userModel
     * @return bool
     * @throws Exception
     */
    public function addUser(AppUserModel $userModel)
    {
        return parent::add($userModel->getData());
    }

    /**
     * 添加用户
     * @param AppUserModel $userModel
     * @return bool
     * @throws Exception
     */
    public function addUserAR(AppUserModel $userModel)
    {
        return parent::add([
            'userId' => $userModel->getId(),
            'nickname' => $userModel->getNickname(),
            'psword' => md5($userModel->getPsword())
        ]);
    }

    /**
     * 更新用户信息
     * @param $userId
     * @param AppUserModel $userModel
     * @return bool
     * @throws Exception
     */
    public function setUserInfo($userId, AppUserModel $userModel)
    {
        return $this->set($userModel->getData())->where('userId', $userId)->execute();
    }

    /**
     * 更新用户信息
     * @param $userId
     * @param AppUserModel $userModel
     * @return bool
     * @throws Exception
     */
    public function setUserInfoAR($userId, AppUserModel $userModel)
    {
        return $this->set(['nickname' => $userModel->getNickname()])
            ->where('userId', $userId)->execute();
    }

    /**
     * 获取用户信息
     * @param $userId
     * @return AppUserModel|null
     * @throws ReflectionException
     * @throws Exception
     */
    public function getUserInfo($userId): ?AppUserModel
    {
        $RId = parent::getRId($userId);

        /** @var AppUserModel $model */
        $model = R()->isPut($RId) ? R()->get($RId) :
            parent::get()->where('isDelete', 0)->where('userId', $userId)->get()
                ->getInstance(AppUserModel::class);

        R()->put($RId, $model);

        return $model;
    }

    /**
     * 获取用户列表
     * @return array
     * @throws Exception
     */
    public function getUserList()
    {
        return parent::get()
            ->where('isDelete', 0)
            ->get()->getResult();
    }
}