<?php namespace VaahCms\Modules\School\Observers;

use App\Mail\BatchAssignmentMail;
use VaahCms\Modules\School\Models\Teacher;
use WebReinvent\VaahCms\Libraries\VaahMail;

class TeacherObserverObserver {

    /**
     * Handle the User "created" event.
     */
    public function created(Teacher $teacher): void
    {
        //
    }
 
    /**
     * Handle the User "updated" event.
     */
    public function updated(Teacher $teacher): void
    {
        // $teacher->load('batches');

        // if ($teacher->email) {
        //     VaahMail::send(new BatchAssignmentMail($teacher), $teacher->email);
        // }
    }

}
