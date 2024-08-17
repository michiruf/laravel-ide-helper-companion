<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertContent('Hello Workbench!');
    $response->assertStatus(200);
});
