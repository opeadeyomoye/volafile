<?php
declare(strict_types=1);

namespace App;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\DTOCache;
use Spatie\DataTransferObject\FieldValidator;

/**
 * Adapts `spatie/data-transfer-object` to create this project's current
 * model of a domain object.
 *
 * Specifically:
 *  1. Domain object properties are `protected`. Not private so we can still
 *     have useful hierachies, not public so properties are immutable by default.
 *  2. Valid property names start without an underscore.
 *  3. Domain objects can nest other objects/collections of (domain) objects.
 *  3. Domain objects are easily deep-serializable (e.g. for aggregate roots)
 *     and vice-versa.
 */
abstract class DomainObject extends DataTransferObject
{
    /**
     * {@inheritDoc}
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->initialize($parameters);
    }

    /**
     * Initialization hook called after the constructor, so child classes
     * don't have to override the constructor itself.
     *
     * @return void
     */
    protected function initialize(array $parameters = []): void
    {
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function all(): array
    {
        $data = [];
        $class = new ReflectionClass(static::class);

        $properties = $class->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($properties as $reflectionProperty) {
            // Skip invalid properties
            if (!$this->propertyIsValid($reflectionProperty)) {
                continue;
            }

            $reflectionProperty->setAccessible(true);
            $data[$reflectionProperty->getName()] = $reflectionProperty->getValue($this);
        }

        return $data;
    }

    /**
     * Overrides its parent method to:
     *  1. Use protected properties as opposed to public ones.
     *  2. Exclude property names that are considered invalid.
     *
     * @param ReflectionClass $class
     *
     * @return \Spatie\DataTransferObject\FieldValidator[]
     */
    protected function getFieldValidators(): array
    {
        return DTOCache::resolve(static::class, function () {
            $class = new ReflectionClass($this);

            $properties = [];

            foreach ($class->getProperties(ReflectionProperty::IS_PROTECTED) as $reflectionProperty) {
                // Skip invalid properties
                if (!$this->propertyIsValid($reflectionProperty)) {
                    continue;
                }

                $field = $reflectionProperty->getName();
                $properties[$field] = FieldValidator::fromReflection($reflectionProperty);
            }

            return $properties;
        });
    }

    /**
     * Checks that a class property is considered a valid domain object property.
     *
     * @param ReflectionProperty $property
     * @return boolean
     */
    protected function propertyIsValid(ReflectionProperty $property): bool
    {
        return $property->isProtected()
            && !$property->isStatic()
            && $this->propertyNameIsValid($property->getName());
    }

    /**
     * Checks that a given string is considered a valid domain-object property name.
     *
     * Primarily, valid property names simply don't start with an underscore.
     * However, the parent `DataTransferObject` class comes with a few
     * protected properties that we'll also exclude:
     *
     * `onlyKeys`, `exceptKeys` and `ignoreMissing`.
     *
     * @param string $name Potential property name.
     *
     * @return boolean
     */
    protected function propertyNameIsValid(string $name): bool
    {
        return $name[0] !== '_'
            && $name !== 'onlyKeys'
            && $name !== 'exceptKeys'
            && $name !== 'ignoreMissing';
    }

    /**
     * Magic method to retrieve otherwise hidden properties.
     *
     * @param string $name
     *
     * @return void
     */
    public function __get(string $name)
    {
        if (!$this->propertyNameIsValid($name)) {
            throw new Exception(
                sprintf(
                    'Trying to get non-existent property "%s" of class %s.',
                    $name,
                    get_class($this)
                )
            );
        }

        return $this->{$name};
    }
}
