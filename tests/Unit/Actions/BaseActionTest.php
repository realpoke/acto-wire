<?php

namespace Tests\Unit\Actions;

use App\Actions\BaseAction;
use App\Exceptions\ActionFailedException;
use App\Exceptions\InvalidActionException;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SucceedAction extends BaseAction
{
    protected function execute(...$payload): static
    {
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
        return $this; // Neither success nor failure
    }
}

class NormalExceptionAction extends BaseAction
{
    protected function execute(...$payload): static
    {
        throw new Exception('generic error');
    }
}

class SucceedWithTransactionAction extends SucceedAction
{
    protected bool $useTransaction = true;
}

class FailWithTransactionAction extends FailAction
{
    protected bool $useTransaction = true;
}

class ExceptionWithTransactionAction extends ExceptionAction
{
    protected bool $useTransaction = true;
}

class NoResultWithTransactionAction extends NoResultAction
{
    protected bool $useTransaction = true;
}

class NormalExceptionWithTransactionAction extends NormalExceptionAction
{
    protected bool $useTransaction = true;
}

class BaseActionTest extends TestCase
{
    public function test_successful_action_does_not_use_transaction_by_default(): void
    {
        DB::shouldReceive('beginTransaction')->never();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->never();

        $result = SucceedAction::run();

        $this->assertTrue($result->successful());
    }

    public function test_failed_action_does_not_use_transaction_by_default(): void
    {
        DB::shouldReceive('beginTransaction')->never();
        DB::shouldReceive('rollBack')->never();

        $result = FailAction::run();

        $this->assertTrue($result->failed());
        $this->assertSame('fail', $result->getErrorMessage());
    }

    public function test_exception_action_does_not_use_transaction_by_default(): void
    {
        DB::shouldReceive('beginTransaction')->never();
        DB::shouldReceive('rollBack')->never();

        $result = ExceptionAction::run();

        $this->assertTrue($result->failed());
        $this->assertSame('exception fail', $result->getErrorMessage());
    }

    public function test_successful_action_with_transaction_commits(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $result = SucceedWithTransactionAction::run();

        $this->assertTrue($result->successful());
    }

    public function test_failed_action_with_transaction_rolls_back(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('transactionLevel')->andReturn(2); // Indicate a transaction is active
        DB::shouldReceive('rollBack')->once();
        DB::shouldReceive('commit')->never();

        $result = FailWithTransactionAction::run();

        $this->assertTrue($result->failed());
        $this->assertSame('fail', $result->getErrorMessage());
    }

    public function test_exception_action_with_transaction_rolls_back(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('transactionLevel')->andReturn(2);
        DB::shouldReceive('rollBack')->once();

        $result = ExceptionWithTransactionAction::run();

        $this->assertTrue($result->failed());
        $this->assertSame('exception fail', $result->getErrorMessage());
    }

    public function test_no_result_action_with_transaction_rolls_back_and_throws(): void
    {
        $this->expectException(InvalidActionException::class);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('transactionLevel')->andReturn(2);
        DB::shouldReceive('rollBack')->once();

        NoResultWithTransactionAction::run();
    }

    public function test_generic_exception_with_transaction_rolls_back_and_re_throws(): void
    {
        $this->expectException(Exception::class);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('transactionLevel')->andReturn(2);
        DB::shouldReceive('rollBack')->once();

        NormalExceptionWithTransactionAction::run();
    }
}
