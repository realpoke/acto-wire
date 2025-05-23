<?php

namespace Tests\Unit\Actions;

use App\Actions\BaseAction;
use App\Exceptions\ActionFailedException;
use App\Exceptions\InvalidActionException;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

// Dummy actions to test BaseAction behavior
class SucceedAction extends BaseAction
{
    protected function execute(...$payload): static
    {
        // No DB operations here, just mark success
        return $this->setSuccessful();
    }
}

class FailAction extends BaseAction
{
    protected function execute(...$payload): static
    {
        return $this->setFailed('fail');
    }
}

class ExceptionAction extends BaseAction
{
    protected function execute(...$payload): static
    {
        throw new ActionFailedException('exception fail');
    }
}

class NoResultAction extends BaseAction
{
    protected function execute(...$payload): static
    {
        // neither success nor failure
        return $this;
    }
}

/**
 * A dummy action that throws a generic Exception.
 */
class NormalExceptionAction extends BaseAction
{
    protected function execute(...$payload): static
    {
        throw new Exception('generic error');
    }
}

class BaseActionTest extends TestCase
{
    public function test_run_commits_and_returns_successful(): void
    {
        // No nested transaction; direct run() should commit
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $result = SucceedAction::run();

        $this->assertTrue($result->successful());
        $this->assertFalse($result->failed());
    }

    public function test_run_set_failed_and_records_error(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        // Ensure rollBack guard allows rollback
        DB::shouldReceive('transactionLevel')->andReturn(2);
        DB::shouldReceive('rollBack')->once();

        $result = FailAction::run();

        $this->assertFalse($result->successful());
        $this->assertTrue($result->failed());
        $this->assertSame('fail', $result->getErrorMessage());
    }

    public function test_exception_action_sets_failed(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('transactionLevel')->andReturn(2);
        DB::shouldReceive('rollBack')->once();

        $result = ExceptionAction::run();

        $this->assertFalse($result->successful());
        $this->assertTrue($result->failed());
        $this->assertSame('exception fail', $result->getErrorMessage());
    }

    public function test_no_result_action_throws_invalid_action_exception(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('transactionLevel')->andReturn(2);
        DB::shouldReceive('rollBack')->once();

        $this->expectException(InvalidActionException::class);

        NoResultAction::run();
    }

    public function test_generic_exception_is_re_thrown_and_rolls_back(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('transactionLevel')->andReturn(2);
        DB::shouldReceive('rollBack')->once();

        $this->expectException(Exception::class);

        NormalExceptionAction::run();
    }
}
