<?php

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Services\GroupAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('assign returns G1 when no acceptances exist', function () {
    $app = Application::factory()->create(['status' => ApplicationStatus::Accepted]);
    $service = new GroupAssignmentService;

    expect($service->assign($app))->toBe('G1');
});

test('assign rotates to G2 once G1 is full', function () {
    $service = new GroupAssignmentService;

    Application::factory()->count(60)->create([
        'status' => ApplicationStatus::Accepted->value,
        'group_label' => 'G1',
    ]);

    $app = Application::factory()->create(['status' => ApplicationStatus::Accepted]);
    expect($service->assign($app))->toBe('G2');
});

test('assign rotates to G3 once G1 and G2 are full', function () {
    $service = new GroupAssignmentService;

    Application::factory()->count(60)->create([
        'status' => ApplicationStatus::Accepted->value,
        'group_label' => 'G1',
    ]);
    Application::factory()->count(60)->create([
        'status' => ApplicationStatus::Accepted->value,
        'group_label' => 'G2',
    ]);

    $app = Application::factory()->create(['status' => ApplicationStatus::Accepted]);
    expect($service->assign($app))->toBe('G3');
});

test('assign returns only valid group labels', function () {
    $service = new GroupAssignmentService;
    $app = Application::factory()->create(['status' => ApplicationStatus::Accepted]);

    expect($service->assign($app))->toBeIn(['G1', 'G2', 'G3']);
});
