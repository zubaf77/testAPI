<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Request;

class RequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $type;

    public function __construct(Request $request, $type)
    {
        $this->request = $request;
        $this->type = $type;
    }

    public function build()
    {
        if ($this->type === 'created') {
            return $this->view('emails.request_created')
                ->with([
                    'name' => $this->request->name,
                    'message' => $this->request->message,
                ]);
        }

        return $this->view('emails.request_answered')
            ->with([
                'name' => $this->request->name,
                'comment' => $this->request->comment,
            ]);
    }
}
