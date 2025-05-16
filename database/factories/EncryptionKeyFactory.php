<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EncryptionKey;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EncryptionKey>
 */
class EncryptionKeyFactory extends Factory
{
    protected $model = EncryptionKey::class;

    public function definition()
    {
        return [
            'file_name' => $this->faker->word . '.mp4',
            'key' => Str::random(52),
            'telegram_code' => (string)rand(100000, 999999),
        ];
    }
}
