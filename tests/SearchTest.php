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
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Search the search elements.
     */
    protected Search $search;


    // Methods :

    /**
     * Initialises tests.
     */
    public function setUp(): void
    {
        $searchDatas = [
            'value' => '',
            'regex' => 'false'
        ];

        $this->search = new Search($searchDatas);
    }


    /**
     * Returns missing datas.
     * @return mixed[] missing datas.
     */
    public function getMissingSearchDatas(): array
    {
        return [
            'the array is empty' => [[]],
            'the "regex" key does not exist' => [['value' => '']],
            'the "value" key does not exist' => [['regex' => 'false']]
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are missing.
     * @param mixed[] $missingDatas missing datas.
     *
     * @dataProvider getMissingSearchDatas
     */
    public function testCanThrownAnOutOfBoundsExceptionWhenConstructingIf(array $missingDatas): void
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
            'the "value" key is not a string' => [['value' => 0, 'regex' => 'false']],
            'the "regex" key is not a string' => [['value' => '', 'regex' => 0]],
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are invalid.
     * @param mixed[] $invalidDatas des données invalides.
     *
     * @dataProvider getInvalidSearchDatas
     */
    public function testCanThrownAnInvalidArgumentExceptionWhenConstructingIf(array $invalidDatas): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('search.key.notAString');

        new Search($invalidDatas);
    }

    /**
     * Tests that an exception is thrown
     * if the regex is unexpected.
     */
    public function testCanThrownARangeExceptionWhenConstructingIfRegexIsNotValid(): void
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
    public function testCanGetValue(): void
    {
        self::assertSame('', $this->search->getValue(), 'The values must be the same.');
    }


    /**
     * Tests that the regex can be returned false.
     *
     * @covers ::getRegex
     */
    public function testCanGetRegexIfFalse(): void
    {
        self::assertFalse($this->search->getRegex(), 'The values must be the same.');
    }


    /**
     * Tests that the regex can be returned true.
     *
     * @covers ::getRegex
     */
    public function testCanGetRegexIfTrue(): void
    {
        $searchDatas = [
            'value' => '',
            'regex' => 'true'
        ];

        $this->search = new Search($searchDatas);

        self::assertTrue($this->search->getRegex(), 'The values must be the same.');
    }
}
