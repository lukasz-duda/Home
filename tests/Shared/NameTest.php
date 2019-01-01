<?php
namespace Assistant\Shared;

require __DIR__ . '/../../vendor/autoload.php';

class NameTest extends Test {
    
    public function testNullIsNotAName() {
        $result = Name::create(null);
        
        $this->assertFailure($result);
    }

    public function testEmptyStringIsNotAName() {
        $result = Name::create('');
        
        $this->assertFailure($result);
    }
    
    public function testWhiteSpaceIsNotAName() {
        $result = Name::create('\t\r\n ');
        
        $this->assertFailure($result);
    }
    
    public function testWordIsAName() {
        $expectedName = 'Word';
        $result = Name::create($expectedName);
        
        $this->assertSuccess($result);
        $this->assertEquals($expectedName, $result->value()->value());
    }
    
    public function testDashAndSpaceAreAllowedInAName() {
        $expectedName = 'Word-word word';
        $result = Name::create($expectedName);
        
        $this->assertSuccess($result);
        $this->assertEquals($expectedName, $result->value()->value());
    }
    
    public function testDashNotAllowedAtTheBeginning() {
        $result = Name::create('-a');
        
        $this->assertFailure($result);
    }
    
    public function testTwoSeparatorsNotAllowed() {
        $result = Name::create('Word -word');
        
        $this->assertFailure($result);
    }
}