<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Response;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Tests the response for Datatables.
 */
#[
    PA\CoversClass(Response::class),
    PA\Group('response')
]
final class ResponseTest extends TestCase
{
    /**
     * Returns datas to test the RangeExceptions.
     * @return mixed[] datas to test the RangeExceptions.
     */
    public static function getParametersForRangeExceptions(): array
    {
        $datas = ['data1','data2'];

        return [
            'draw is less than one' => [0, $datas, 0, 0],
            'records total is negative' => [1, $datas, -1, 0],
            'length is negative' => [1, $datas, 0, -1]
        ];
    }

    /**
     * Tests that a \RangeException is thrown.
     * @param int $draw the number of time the array is drawn.
     * @param mixed[] $data the datas.
     * @param int $recordsTotal the number of records.
     * @param int $recordsFiltered the number of filtered records.
     */
    #[
        PA\DataProvider('getParametersForRangeExceptions'),
        PA\TestDox('Can throw a RangeException when $_dataName')
    ]
    public function testCanThrowARangeException(
        int $draw,
        array $data,
        int $recordsTotal,
        int $recordsFiltered
    ): void {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('datatables.response.valueTooSmall');

        new Response($draw, $data, $recordsTotal, $recordsFiltered);
    }


    /**
     * Returns datas to test the LogicExceptions.
     * @return mixed[] datas to test the LogicExceptions.
     */
    public static function getParametersForLogicExceptions(): array
    {
        $datas = ['data1','data2'];

        return [
            'records total is less than records filtered' => [1, $datas, 1, 2],
            'records total is less than datas count' => [1, $datas, 1, 1],
            'records filtered is less than datas count' => [1, $datas, 2, 1]
        ];
    }

    /**
     * Tests that a \LogicException is thrown.
     * @param int $draw the number of time the array is drawn.
     * @param mixed[] $data the datas.
     * @param int $recordsTotal the number of records.
     * @param int $recordsFiltered the number of filtered records.
     */
    #[
        PA\DataProvider('getParametersForLogicExceptions'),
        PA\TestDox('Can throw a LogicException when $_dataName')
    ]
    public function testCanThrowALogicException(
        int $draw,
        array $data,
        int $recordsTotal,
        int $recordsFiltered
    ): void {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('datatables.response.recordsCountError');

        new Response($draw, $data, $recordsTotal, $recordsFiltered);
    }


    /**
     * Returns datas to test jsonSerialize.
     * @return mixed[] datas to test jsonSerialize.
     */
    public static function getParametersForJsonSerialize(): array
    {
        $data = [
            'data1',
            'data2'
        ];

        return [
            'non filtered datas' => [1, $data, 10, 10],
            'filtered datas' => [1, $data, 10, 5],
            'empty non filtered datas' => [1, [], 0, 0],
            'empty filtered datas' => [1, [], 10, 0]
        ];
    }

    /**
     * Tests that the object can be json serialized.
     * @param int $draw the number of time the array is drawn.
     * @param mixed[] $data the datas.
     * @param int $recordsTotal the number of records.
     * @param int $recordsFiltered the number of filtered records.
     */
    #[PA\DataProvider('getParametersForJsonSerialize')]
    public function testCanJsonSerialize(
        int $draw,
        array $data,
        int $recordsTotal,
        int $recordsFiltered
    ): void {
        $response = new Response($draw, $data, $recordsTotal, $recordsFiltered);
        $unserializedResponse = json_decode(json_encode($response), false);

        self::assertTrue(property_exists($unserializedResponse, 'draw'));
        self::assertTrue(property_exists($unserializedResponse, 'data'));
        self::assertTrue(property_exists($unserializedResponse, 'recordsTotal'));
        self::assertTrue(property_exists($unserializedResponse, 'recordsFiltered'));

        self::assertSame($draw, $unserializedResponse->draw);
        self::assertSame($data, $unserializedResponse->data);
        self::assertSame($recordsTotal, $unserializedResponse->recordsTotal);
        self::assertSame($recordsFiltered, $unserializedResponse->recordsFiltered);
    }
}
