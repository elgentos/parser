<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 22:55.
 */

namespace Elgentos\Parser\Matcher;

class IsTypeTest extends MatcherAbstract
{
    public function testConstruct()
    {
        // Success
        new IsType(IsType::IS_STRING);
        new IsType(IsType::IS_BOOL);
        new IsType(IsType::IS_ARRAY);
        new IsType(IsType::IS_INT);
        new IsType(IsType::IS_NUMERIC);
        new IsType(IsType::IS_OBJECT);
        new IsType(IsType::IS_NULL);
        new IsType(IsType::IS_FLOAT);

        // Fail
        $this->expectException(\InvalidArgumentException::class);
        new IsType('non-existant');
    }

    public function testValidateIndex()
    {
        $context = $this->context;

        $context->setIndex('test');

        // Test index
        $matcher = new IsType(IsType::IS_STRING, 'getIndex');
        $this->assertTrue($matcher->validate($context));
    }

    /**
     * @dataProvider dataValidationTests
     *
     * @param string $type
     * @param mixed  $test
     * @param bool   $result
     */
    public function testValidate(string $type, $test, bool $result)
    {
        $context = $this->context;

        $context->setIndex('test');
        $current = &$context->getCurrent();

        $matcher = new IsType($type);
        $current = $test;
        $this->assertSame(
                $result,
                $matcher->validate($context),
                sprintf('Test type "%s" with value "%s"', $type, var_export($test, true))
        );
    }

    public function dataValidationTests()
    {
        return [
                [IsType::IS_STRING,     'string',   true],
                [IsType::IS_STRING,     10,         false],
                [IsType::IS_STRING,     true,       false],

                [IsType::IS_BOOL,       false,      true],
                [IsType::IS_BOOL,       true,       true],
                [IsType::IS_BOOL,       1,          false],
                [IsType::IS_BOOL,       'true',     false],

                [IsType::IS_ARRAY,      ['array'],  true],
                [IsType::IS_ARRAY,      'array',    false],
                [IsType::IS_ARRAY,      [],         true],

                [IsType::IS_INT,        10,         true],
                [IsType::IS_INT,        10.2,       false],
                [IsType::IS_INT,        '10',       false],

                [IsType::IS_NUMERIC,    11,        true],
                [IsType::IS_NUMERIC,    '11',      true],
                [IsType::IS_NUMERIC,    'string',  false],
                [IsType::IS_NUMERIC,    10.2,      true],

                [IsType::IS_OBJECT,     new IsType(IsType::IS_STRING),
                                                    true, ],
                [IsType::IS_OBJECT,     'false',    false],

                [IsType::IS_NULL,       null,       true],
                [IsType::IS_NULL,       'null',     false],
                [IsType::IS_NULL,       "\0",       false],
                [IsType::IS_NULL,       '',         false],

                [IsType::IS_FLOAT,      10.0,       true],
                [IsType::IS_FLOAT,      10.1,       true],
                [IsType::IS_FLOAT,      '10.1',     false],
                [IsType::IS_FLOAT,      '0',        false],
                [IsType::IS_FLOAT,      10,         false],
        ];
    }

    public function testFactory()
    {
        $matcher = IsType::factory(IsType::IS_STRING);
        $this->assertInstanceOf(IsType::class, $matcher);
    }

    public function testShorthands()
    {
        $isString = new IsString();
        $isBool = new IsBool();
        $isArray = new IsArray();
        $isInt = new IsInt();
        $isNumeric = new IsNumeric();
        $isObject = new IsObject();
        $isNull = new IsNull();
        $isFloat = new IsFloat();

        $context = $this->context;
        $context->setIndex('test');
        $current = &$context->getCurrent();

        $current = 'test';
        $this->assertTrue($isString->validate($context));

        $current = true;
        $this->assertTrue($isBool->validate($context));

        $current = ['array'];
        $this->assertTrue($isArray->validate($context));

        $current = 10;
        $this->assertTrue($isInt->validate($context));

        $current = '10.0';
        $this->assertTrue($isNumeric->validate($context));

        $current = $isString;
        $this->assertTrue($isObject->validate($context));

        $current = null;
        $this->assertTrue($isNull->validate($context));

        $current = 10.3;
        $this->assertTrue($isFloat->validate($context));
    }
}
