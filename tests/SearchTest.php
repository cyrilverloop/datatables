<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Search;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Tests the search elements for Datatables.
 */
#[
    PA\CoversClass(Search::class),
    PA\Group('search')
]
final class SearchTest extends TestCase
{
    // Methods :

    /**
     * Returns missing datas.
     * @return mixed[] missing datas.
     */
    public static function getMissingSearchDatas(): array
    {
        return [
            'value' => [[]],
            'regex key' => [['value' => '']],
            'value key' => [['regex' => 'false']]
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are missing.
     * @param mixed[] $missingDatas missing datas.
     */
    #[
        PA\DataProvider('getMissingSearchDatas'),
        PA\TestDox('Can throw an OutOfBoundsException when there is no $_dataName')
    ]
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
    public static function getInvalidSearchDatas(): array
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
     */
    #[
        PA\DataProvider('getInvalidSearchDatas'),
        PA\TestDox('Can throw an InvalidArgumentException when $_dataName')
    ]
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
