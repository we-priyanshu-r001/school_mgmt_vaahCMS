<?php namespace VaahCms\Modules\School\Traits;

use Illuminate\Database\Eloquent\Model;
use VaahCms\Modules\School\Mails\SuperAdminRecordDeletedMail;
use WebReinvent\VaahCms\Libraries\VaahMail;
use WebReinvent\VaahCms\Models\User;

trait DeleteMailTrait {

    public static function sendDeleteMail($collection){
        $super_admin = User::whereHas('roles', function($role) {
        $role->where('name', 'Super Administrator');
        })->first();
        VaahMail::addInQueue(new SuperAdminRecordDeletedMail($collection, $super_admin), $super_admin->email);
    }

}
