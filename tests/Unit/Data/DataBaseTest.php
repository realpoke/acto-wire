<?php

namespace Tests\Unit\Data;

use App\Data\BaseData;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Tests\TestCase;

class DummyData extends BaseData
{
    public function __construct(
        public int $foo,
        public string $bar = 'default'
    ) {}

    public static function rules(): array
    {
        return [
            'foo' => ['required', 'integer', 'min:1'],
            'bar' => ['required', 'string'],
        ];
    }
}

class DataBaseTest extends TestCase
{
    public function test_it_constructs_from_array_and_applies_defaults(): void
    {
        $dto = DummyData::from([
            'foo' => 5,
            // 'bar' omitted, should use default
        ]);

        $this->assertInstanceOf(DummyData::class, $dto);
        $this->assertSame(5, $dto->foo);
        $this->assertSame('default', $dto->bar);
    }

    public function test_it_throws_on_missing_required_fields(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // missing required 'foo'
        DummyData::from(['bar' => 'hello']);
    }

    public function test_it_throws_validation_exception_on_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        // foo must be >=1
        DummyData::from(['foo' => 0, 'bar' => 'x']);
    }

    public function test_it_converts_to_array_and_json_serializable(): void
    {
        $dto = DummyData::from(['foo' => 2, 'bar' => 'baz']);

        $array = $dto->toArray();
        $this->assertSame(['foo' => 2, 'bar' => 'baz'], $array);

        $json = json_encode($dto);
        $this->assertSame('{"foo":2,"bar":"baz"}', $json);
    }

    public function test_rules_returns_empty_array(): void
    {
        $this->assertSame([], BaseData::rules());
    }
}
