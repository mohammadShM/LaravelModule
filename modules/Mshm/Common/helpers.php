<?php

namespace Mshm\Common;

function newFeedback($title, $body, $type)
{
    /** @noinspection MissingService */
    $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];
    $session[] = [
        'title' => $title,
        'body' => $body,
        'type' => $type
    ];
    session()->flash('feedbacks', $session);
}
