<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Search;
use PHPUnit\Framework\TestCase;

/**
 * Tests the search elements for Datatables.
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Search
 * @covers ::__construct
 * @group search
 */
final class SearchTest extends TestCase
{
    // Methods :

    /**
     * Returns missing datas.
     * @return mixed[] missing datas.
     */
    public function getMissingSearchDatas(): array
    {
        return [
            'empty array' => [[]],
            'no regex key' => [['value' => '']],
            'no value key' => [['regex' => 'false']]
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are missing.
     * @param mixed[] $missingDatas missing datas.
     *
     * @dataProvider getMissingSearchDatas
     */
    public function testCanThrowAnOutOfBoundsException(array $missingDatas): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('search.key.notExist');

        new Search($missingDatas);
    }

    /**
     * Returns invalid datas.
     * @return mixed[] invalid datas.
     */
    public function getInvalidSearchDatas(): array
    {
        return [
            'value is an int' => [['value' => 0, 'regex' => 'false']],
            'value is a float' => [['value' => 0.5, 'regex' => 'false']],
            'value is an array' => [['value' => [], 'regex' => 'false']],
            'value is an object' => [['value' => new \stdClass(), 'regex' => 'false']],
            'regex is an int' => [['value' => '', 'regex' => 0]],
            'regex is a float' => [['value' => '', 'regex' => 0.5]],
            'regex is an array' => [['value' => '', 'regex' => []]],
            'regex is an object' => [['value' => '', 'regex' => new \stdClass()]]
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are invalid.
     * @param mixed[] $invalidDatas des données invalides.
     *
     * @dataProvider getInvalidSearchDatas
     */
    public function testCanThrowAnInvalidArgumentException(array $invalidDatas): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('search.key.notAString');

        new Search($invalidDatas);
    }

    /**
     * Tests that an exception is thrown
     * if the regex is neither 'true' nor 'false'.
     */
    public function testCanThrowARangeExceptionWhenRegexIsNeitherTrueNorFalse(): void
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('search.regex.notValid');

        new Search(['value' => '', 'regex' => '']);
    }


    /**
     * Tests that the value can be returned.
     *
     * @covers ::getValue
     */
    public function testCanGiveValue(): void
    {
        $searchDatas = [
            'value' => 'test-value',
            'regex' => 'true'
        ];

        $search = new Search($searchDatas);

        self::assertSame('test-value', $search->getValue());
    }


    /**
     * Tests that the regex can be returned false.
     *
     * @covers ::getRegex
     */
    public function testCanGiveAFalseRegex(): void
    {
        $searchDatas = [
            'value' => '',
            'regex' => 'false'
        ];
        $search = new Search($searchDatas);

        self::assertFalse($search->getRegex());
    }

    /**
     * Tests that the regex can be returned true.
     *
     * @covers ::getRegex
     */
    public function testCanGiveATrueRegex(): void
    {
        $searchDatas = [
            'value' => '',
            'regex' => 'true'
        ];

        $search = new Search($searchDatas);

        self::assertTrue($search->getRegex());
    }
}
