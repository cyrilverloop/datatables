<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests the response for Datatables.
 * @package \Bundles\CrudBundle\Tests\Datatables
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Response
 * @covers ::__construct
 */
class ResponseTest extends TestCase
{
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Response the response.
     */
    protected Response $response;

    /**
     * @var mixed[] the datas.
     */
    protected array $data;


    // Methods :

    /**
     * Initialises tests.
     * @return void
     */
    public function setUp(): void
    {
        $this->data = [
            'data1',
            'data2'
        ];

        $this->response = new Response(1, $this->data, 2, 2);
    }


    /**
     * Returns datas to test the constructor's RangeExceptions.
     * @return mixed[] datas to test the constructor's RangeExceptions.
     */
    public function getParametersForConstructorRangeExceptions(): array
    {
        $datas = ['data1','data2'];

        return [
            'when draw is less than one' => [0, $datas, 0, 0],
            'when records total is negative' => [1, $datas, -1, 0],
            'when length is less than minus one' => [1, $datas, 0, -1]
        ];
    }

    /**
     * Test that a \RangeException is thrown when the object is constructed.
     * @param int $draw the number of time the array is drawn.
     * @param mixed[] $data the datas.
     * @param int $recordsTotal the number of records.
     * @param int $recordsFiltered the number of filtered records.
     * @return void
     *
     * @dataProvider getParametersForConstructorRangeExceptions
     */
    public function testConstructorCanThrowARangeException(
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
     * Returns datas to test the constructor's LogicExceptions.
     * @return mixed[] datas to test the constructor's LogicExceptions.
     */
    public function getParametersForConstructorLogicExceptions(): array
    {
        $datas = ['data1','data2'];

        return [
            'when records total is less than records filtered' => [1, $datas, 1, 2],
            'when records total is less than datas count' => [1, $datas, 1, 1],
            'when records filtered is less than datas count' => [1, $datas, 2, 1]
        ];
    }

    /**
     * Test that a \LogicException is thrown when the object is constructed.
     * @param int $draw the number of time the array is drawn.
     * @param mixed[] $data the datas.
     * @param int $recordsTotal the number of records.
     * @param int $recordsFiltered the number of filtered records.
     * @return void
     *
     * @dataProvider getParametersForConstructorLogicExceptions
     */
    public function testConstructorCanThrowALogicException(
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
     * Test that the object can be serialized.
     * @return void
     *
     * @covers ::jsonSerialize
     */
    public function testCanJsonSerialize(): void
    {
        $serialized = $this->response->jsonSerialize();

        self::assertArrayHasKey('draw', $serialized, 'The array must have a draw key.');
        self::assertArrayHasKey('data', $serialized, 'The array must have a data key.');
        self::assertArrayHasKey('recordsTotal', $serialized, 'The array must have a recordsTotal key.');
        self::assertArrayHasKey('recordsFiltered', $serialized, 'The array must have a recordsFiltered key.');

        self::assertIsInt($serialized['draw'], 'Draw must be an integer.');
        self::assertIsArray($serialized['data'], 'Data must be an array.');
        self::assertIsInt($serialized['recordsTotal'], 'RecordsTotal must be an integer.');
        self::assertIsInt($serialized['recordsFiltered'], 'RecordsFiltered must be an integer.');
    }
}
