<?php  namespace VaahCms\Modules\School\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuperAdminRecordDeletedMail extends Mailable {

    use Queueable, SerializesModels;

    public $super_admin;
    public $collection;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($collection, $super_admin)
    {
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
