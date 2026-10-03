<?php

declare(strict_types=1);

use App\Mail\SetPasswordMail;
use App\Mail\WelcomeMail;

it('never turns a user-controlled name into a link or HTML in an email', function (): void {
    $name = '[Verify your account](http://evil.test) <script>alert(1)</script>';

    $welcome = (new WelcomeMail($name, $name))->render();
    $setPassword = (new SetPasswordMail($name, 'victim@acme.test', 'token123'))->render();

    foreach ([$welcome, $setPassword] as $html) {
        expect($html)->not->toContain('href="http://evil.test"')
            ->not->toContain('<script>')
            ->toContain('[Verify your account](http://evil.test)');
    }
});
