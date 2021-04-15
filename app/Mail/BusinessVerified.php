<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelLocalization;

class BusinessVerified extends Mailable
{
    use Queueable, SerializesModels;

    protected $directory;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($directory)
    {
        //
        $this->directory = $directory;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = LaravelLocalization::getCurrentLocale();
        return $this->markdown("emails.$locale.business_verified")
            ->with([
                'name' => $this->directory->user->display_name,
                'title' => $this->directory->name,
                'url' => route('branding', [$this->directory, $this->directory->slug]),
            ]);
    }
}
