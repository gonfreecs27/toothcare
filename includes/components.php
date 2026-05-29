<?php

function featureCard($icon, $title, $desc) {
    echo "
    <div class='feature-card'>
        <div class='feature-icon'>
            <i class='bi $icon'></i>
        </div>
        <h5 class='fw-semibold'>$title</h5>
        <p class='text-muted'>$desc</p>
    </div>";
}

function statBox($value, $label) {
    echo "
    <div class='stat-box'>
        <h3 class='text-primary fw-bold'>$value</h3>
        <p class='mb-0'>$label</p>
    </div>";
}

function alert($type, $message) {
    return "<div class='alert alert-$type py-2 small'>$message</div>";
}

function inputIcon($icon, $type, $name, $placeholder) {
    return "
    <div class='input-group mb-2'>
        <span class='input-group-text'>
            <i class='bi bi-$icon'></i>
        </span>
        <input type='$type' name='$name' class='form-control' placeholder='$placeholder' required>
    </div>
    ";
}
