<?php

namespace MarkWalet\EnvironmentManager\Tests\Changes;

use MarkWalet\EnvironmentManager\Changes\Move;
use PHPUnit\Framework\TestCase;

class MoveTest extends TestCase
{
    /** @test */
    public function can_set_key_through_constructor()
    {
        $change = new Move('EXISTING_KEY');

        $this->assertEquals('EXISTING_KEY', $change->getKey());
    }

    /** @test */
    public function can_move_key_at_start_of_string()
    {
        $change = new Move('EXISTING_KEY');
        $change->after('TEST_VALUE2');
        $original = "EXISTING_KEY=value".PHP_EOL."TEST_VALUE1=example1".PHP_EOL."TEST_VALUE2=example2";

        $new = $change->apply($original);

        $this->assertEquals("TEST_VALUE1=example1".PHP_EOL."TEST_VALUE2=example2".PHP_EOL."EXISTING_KEY=value", $new);
    }

    /** @test */
    public function can_move_key_at_end_of_string()
    {
        $change = new Move('EXISTING_KEY');
        $change->before('TEST_VALUE1');
        $original = "TEST_VALUE1=example1".PHP_EOL."TEST_VALUE2=example2".PHP_EOL."EXISTING_KEY=value";

        $new = $change->apply($original);

        $this->assertEquals("EXISTING_KEY=value".PHP_EOL."TEST_VALUE1=example1".PHP_EOL."TEST_VALUE2=example2", $new);
    }

    /** @test */
    public function can_move_key_in_middle_of_string()
    {
        $change = new Move('EXISTING_KEY');
        $change->after('TEST_VALUE2');
        $original = "TEST_VALUE1=example1".PHP_EOL."EXISTING_KEY=value".PHP_EOL."TEST_VALUE2=example2";

        $new = $change->apply($original);

        $this->assertEquals("TEST_VALUE1=example1".PHP_EOL."TEST_VALUE2=example2".PHP_EOL."EXISTING_KEY=value", $new);
    }

}