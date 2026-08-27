<?php

test('the student list page lists every student', function () {
    $response = $this->get('/students');

    $response->assertStatus(200);
    $response->assertSee('Budi Ariyanto');
    $response->assertSee('2024001');
});

test('each student row links to that student', function () {
    $response = $this->get('/students');

    $response->assertSee(route('students.show', ['id' => 1]), false);
    $response->assertSee(route('students.edit', ['id' => 2]), false);
});
