<?php
namespace Service;

use app\admin\model\AccountRecordModel;
/**
* 账户记录
*/
class AccountRecord{
    public $accountRecord;
    public function __construct()
    {
        $this->accountRecord = new AccountRecordModel();
    }

    /**
     * 设置账户记录
     * @return bool
     */
    public function setAccountRecord($user_id,$record_info,$type,$status,$money)
    {
        $accountRecord = $this->accountRecord->create([
            'user_id' => $user_id,
            'record_info' => $record_info,
            'type' => $type,
            'status' => $status,
            'money' => $money,
        ]);

        if(count($accountRecord) == 0)
            return false;

        return true;
    }
}