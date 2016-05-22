<?php
namespace Alt3\ValidationExposer\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class AuthorsFixture extends TestFixture
{
    public $table = 'authors';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'name' => ['type' => 'string', 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['name' => 'admad'],
        ['name' => 'jadb'],
    ];
}
