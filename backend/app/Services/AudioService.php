<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class AudioService
{
    public function transcribeAudio($file, $maxRetries = 3)
    {
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $response = OpenAI::audio()->transcribe([
                    'model' => 'whisper-1',
                    'file' => fopen($file->getRealPath(), 'rb'),
                ]);

                return $response->text ?? '無法辨識語音';

            } catch (\OpenAI\Exceptions\RateLimitException $e) {
                $attempt++;
                sleep(1); // 等 1 秒再重試
            } catch (\Exception $e) {
                return '無法辨識語音: ' . $e->getMessage();
            }
        }

        return '無法辨識語音: 請稍後再試';
    }
}
