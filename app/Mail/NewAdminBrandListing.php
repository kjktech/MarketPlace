<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewAdminBrandListing extends Mailable
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
        $locale = 'en';
        return $this->markdown("emails.$locale.new_admin_directory")
            ->with([
                'name' => $this->directory->name,
                'url' => $this->directory->url
            ]);
    }
}
