<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class ImageService
{
    public function analyzeImage($file)
    {
        $path = $file->getRealPath();

        // 將圖片轉 base64
        $base64 = base64_encode(file_get_contents($path));

        // 將 base64 當成文字描述給 GPT，告訴它要分析圖片
        $prompt = "請幫我分析這張圖片，內容如下(base64): " . $base64;

        $response = OpenAI::responses()->create([
            'model' => 'gpt-4.1-mini',
            'input' => $prompt, // 注意這裡是字串
        ]);

        // 回傳文字描述
        return $response->outputText ?? '無法辨識圖片內容';
    }
}
