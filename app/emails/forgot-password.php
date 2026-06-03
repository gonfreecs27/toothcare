<?php

return "
    <h2>Password Reset Request</h2>

    <p>Hello {$username},</p>

    <p>
        We received a request to reset your password.
    </p>

    <p>
        Click the button below to continue:
    </p>

    <p>
        <a href='{$resetLink}'
           style='
                background:#0d6efd;
                color:#fff;
                padding:10px 20px;
                text-decoration:none;
                border-radius:5px;
           '>
            Reset Password
        </a>
    </p>

    <p>
        This link will expire in 1 hour.
    </p>

    <p>
        If you did not request a password reset,
        you may safely ignore this email.
    </p>

    <hr>

    <small>
        " . BRAND_NAME . " Dental Clinic System
    </small>
";