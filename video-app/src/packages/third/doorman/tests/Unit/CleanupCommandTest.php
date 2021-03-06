<?php

namespace Third\Doorman\Test\Unit;

use Third\Doorman\Test\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Third\Doorman\Models\Invite;
use PHPUnit\Framework\Assert;

class CleanupCommandTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_deletes_wasted_invites()
    {
      Assert::assertCount(0, Invite::all());

        Invite::forceCreate([
          'code' => 'ABCDE',
          'max' => 2,
          'uses' => 2,
        ]);

        Invite::forceCreate([
          'code' => 'ABCDF',
          'max' => 2,
          'uses' => 1,
        ]);

        Assert::assertCount(2, Invite::all());

        Artisan::call('doorman:cleanup');

        Assert::assertCount(1, Invite::all());
    }

    public function it_deletes_expired_invites()
    {
      Assert::assertCount(0, Invite::all());

      Invite::forceCreate([
          'code' => 'ABCDE',
          'valid_until' => Carbon::now()->subDay(),
      ]);

      Invite::forceCreate([
          'code' => 'ABCDF',
          'valid_until' => Carbon::now()->addWeeks(2),
      ]);

        Assert::assertCount(2, Invite::all());

        Artisan::call('doorman:cleanup');

        Assert::assertCount(1, Invite::all());
    }
}
