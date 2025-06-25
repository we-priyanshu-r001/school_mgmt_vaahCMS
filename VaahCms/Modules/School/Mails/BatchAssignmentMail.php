<?php  namespace VaahCms\Modules\School\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use VaahCms\Modules\School\Models\Teacher;

class BatchAssignmentMail extends Mailable {

    use Queueable, SerializesModels;

    public $teacher;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Teacher Batch Assignment',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'mails.batch_assignment',
    //     );
    // }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function build()
    {
        return $this->view('school::emails.batchassignmentmail', [$this->teacher]);
    }

}
