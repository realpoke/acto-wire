<?php

namespace App\Actions;

use App\Exceptions\ActionFailedException;
use App\Exceptions\InvalidActionException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * BaseAction with transaction management, error handling, and auto-injection.
 *
 * Concrete actions implement execute() and return $this or throw ActionFailedException.
 */
abstract class BaseAction
{
    private ?bool $success = null;

    private ?string $errorMessage = null;

    /**
     * Entry point: wraps execute() in a DB transaction with error handling.
     *
     * @param  mixed  ...$payload  Parameters to pass to execute(), auto-injected by the container.
     *
     * @throws Exception
     */
    public function __invoke(...$payload): static
    {
        DB::beginTransaction();

        try {
            $this->execute(...$payload);
        } catch (ActionFailedException $e) {
            $this->setFailed($e->getMessage());
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }

        if (is_null($this->success)) {
            $this->rollBack();
            throw new InvalidActionException(
                'Action did not fail or succeed, this should never happen.'
            );
        }

        return $this;
    }

    /**
     * Child implements business logic here, invoking setSuccessful() or throwing ActionFailedException.
     *
     * @param  mixed  ...$payload
     *
     * @throws ActionFailedException
     */
    abstract protected function execute(...$payload): static;

    /**
     * Run the action via Laravel container to auto-inject __invoke() parameters.
     */
    public static function run(...$payload): static
    {
        return app()->call([app(static::class), '__invoke'], $payload);
    }

    /**
     * Was the action successful?
     */
    public function successful(): bool
    {
        return $this->success === true;
    }

    /**
     * Did the action fail?
     */
    public function failed(): bool
    {
        return ! $this->successful();
    }

    /**
     * Get the error message if the action failed.
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * Mark action as successful and commit the transaction.
     */
    protected function setSuccessful(): static
    {
        DB::commit();
        $this->success = true;

        return $this;
    }

    /**
     * Mark action as failed, rollback, and log the error.
     */
    protected function setFailed(?string $errorMessage = null): static
    {
        Log::debug(
            'Action failed in '.static::class.': '.(
                $errorMessage ?? 'No error message provided.'
            )
        );

        $this->rollBack();

        $this->success = false;
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Rollback a nested transaction if applicable.
     */
    private function rollBack(): void
    {
        if (DB::transactionLevel() > 1) {
            DB::rollBack();
        }
    }
}
