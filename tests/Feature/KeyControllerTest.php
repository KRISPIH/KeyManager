<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\EncryptionKey;
use Tests\TestCase;

class KeyControllerTest extends TestCase
{
    use RefreshDatabase; // Каждый тест будет запускаться в чистой базе данных

    /**
     * Тестируем успешное создание ключа
     *
     * @return void
     */
    public function test_generate_key()
    {
        // Используем фабрику для создания тестовых данных
        $data = [
            'telegram_code' => '1234567890',
            'file_name' => 'video.mp4',
        ];

        // Отправляем POST-запрос к API
        $response = $this->postJson('/api/v1/key/generate', $data);

        // Проверяем, что ответ был успешным
        $response->assertStatus(201);

        // Проверяем, что в базе данных появилась запись
        $this->assertDatabaseHas('encryption_keys', [
            'telegram_code' => '1234567890',
            'file_name' => 'video.mp4',
        ]);
    }

    /**
     * Тестируем ошибку при неправильных данных
     *
     * @return void
     */
    public function test_invalid_data_generate_key()
    {
        // Отправляем пустой запрос
        $response = $this->postJson('/api/v1/key/generate', []);

        // Проверяем, что ответ - это ошибка 400
        $response->assertStatus(400)
            ->assertJson([
                'code' => 400,
                'message' => 'Incorrect input data',
            ]);
    }

    /**
     * Тестируем поиск ключа
     *
     * @return void
     */
    public function test_get_key()
    {
        // Создаем тестовую запись
        $key = EncryptionKey::factory()->create();

        // Отправляем запрос на поиск по ID и Telegram ID
        $response = $this->postJson('/api/v1/key/get_key', [
            'id' => $key->id,
            'telegram_code' => $key->telegram_code,
        ]);

        // Проверяем, что запрос был успешным
        $response->assertStatus(200)
            ->assertJson([
                'key' => $key->key,
            ]);
    }

    /**
     * Тестируем ошибку при отсутствии ключа
     *
     * @return void
     */
    public function test_key_not_found()
    {
        // Отправляем запрос на получение несуществующего ключа
        $response = $this->postJson('/api/v1/key/get_key', [
            'id' => 999,
            'telegram_code' => '12345679',
        ]);

        // Проверяем, что ответ - это ошибка 404
        $response->assertStatus(404)
            ->assertJson([
                'code' => 404,
                'message' => 'Key not found or access denied',
            ]);
    }
}