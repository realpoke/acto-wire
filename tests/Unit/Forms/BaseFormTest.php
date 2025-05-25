<?php

namespace Tests\Unit\Forms;

use App\Data\BaseData;
use App\Livewire\Forms\BaseForm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use LogicException;
use stdClass;
use Tests\TestCase;

/**
 * Dummy data class for testing BaseForm.
 */
class DummyData extends BaseData
{
    public function __construct(public string $name) {}

    public static function rules(): array
    {
        return ['name' => ['required', 'string', 'min:3']];
    }
}

/**
 * Dummy form extending BaseForm to return DummyData.
 */
class DummyForm extends BaseForm
{
    public string $name = '';

    protected static string $dtoClass = DummyData::class;

    // Override constructor to initialize Livewire Form internals
    public function __construct()
    {
        // Provide a minimal stub Component instance
        $this->component = new class extends Component
        {
            public function render(): void {}
        };
        // Assign a dummy propertyName
        $this->propertyName = 'form';
    }
}

/**
 * Invalid form without proper DTO class.
 */
class InvalidForm extends BaseForm
{
    public string $name = '';

    // Point to a class that does NOT extend BaseData
    protected static string $dtoClass = stdClass::class;

    public function __construct()
    {
        $this->component = new class extends Component
        {
            public function render(): void {}
        };
        $this->propertyName = 'form';
    }
}

class BaseFormTest extends TestCase
{
    public function test_rules_returns_dto_rules(): void
    {
        $form = new DummyForm();
        $this->assertSame(
            DummyData::rules(),
            $form->rules()
        );
    }

    public function test_rules_returns_empty_array_if_dto_class_invalid(): void
    {
        $form = new InvalidForm();
        $this->assertSame([], $form->rules());
    }

    public function test_dto_returns_strongly_typed_data_object(): void
    {
        $form = new DummyForm();
        $form->name = 'Alice';

        /** @var DummyData $dto */
        $dto = $form->dto();

        $this->assertInstanceOf(DummyData::class, $dto);
        $this->assertSame('Alice', $dto->name);
    }

    public function test_dto_throws_validation_exception_on_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $form = new DummyForm();
        $form->name = 'Al'; // too short

        $form->dto();
    }

    public function test_fill_from_pre_fills_properties_from_array_model_or_data(): void
    {
        $form = new DummyForm();

        // From array
        $form->fillFrom(['name' => 'Bob']);
        $this->assertSame('Bob', $form->name);

        // From Data
        $data = new DummyData('Carol');
        $form->fillFrom($data);
        $this->assertSame('Carol', $form->name);

        // From Eloquent Model
        $model = new class extends Model
        {
            protected $guarded = [];
        };
        $model->name = 'Dave';
        $form->fillFrom($model);
        $this->assertSame('Dave', $form->name);
    }

    public function test_to_array_excludes_internal_properties(): void
    {
        $form = new DummyForm();
        $form->name = 'Eve';

        $array = $form->toArray();
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('component', $array);
    }

    public function test_dto_throws_logic_exception_if_dto_class_invalid(): void
    {
        $this->expectException(LogicException::class);

        $form = new InvalidForm();
        $form->name = 'Test';
        $form->dto();
    }

    public function test_json_serialize_delegates_to_to_array(): void
    {
        $form = new DummyForm();
        $form->name = 'Frank';

        $array = $form->jsonSerialize();
        // jsonSerialize should return exactly the result of toArray()
        $this->assertSame($form->toArray(), $array);
    }
}
