<?php  namespace VaahCms\Modules\School\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuperAdminRecordDeletedMail extends Mailable {

    use Queueable, SerializesModels;

    // public $model_name;
    // public $record_id;
    // public $record_name;
    // public $deleted_by;
    // public $timestamp;
    public $super_admin;
    public $collection;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($collection, $super_admin)
    {
        // $this->model_name = class_basename($model);
        // $this->record_id = $model->id;
        // $this->record_name = $model->name;
        // $this->deleted_by = $model->deletedByUser->name;
        // $this->timestamp = $model->deleted_at;
        $this->super_admin = $super_admin->name;

        $this->collection = $collection;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Record Deleted',
            from: 'support@schoolmgmt.org'
        );
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function build()
    {
        return $this->view('school::emails.super-admin-record-deleted');
    }

}
