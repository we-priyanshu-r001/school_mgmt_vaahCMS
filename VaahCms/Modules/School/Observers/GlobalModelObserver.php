<?php namespace VaahCms\Modules\School\Observers;

use VaahCms\Modules\School\Mails\SuperAdminRecordDeletedMail;
use WebReinvent\VaahCms\Libraries\VaahMail;
use WebReinvent\VaahCms\Models\User;

class GlobalModelObserver {

    public function deleted($model) :void
    {
        
    }


}
