# AI 客服助理專案

這是一個使用 **Laravel + Vue 3** 開發的 AI 客服助理專案，支援文字聊天、圖片辨識、語音辨識，以及整合天氣 API 查詢功能。

## 功能

1. **文字聊天**
   - 使用 OpenAI GPT-4.1-mini 回應使用者訊息。
2. **圖片辨識**
   - 使用 OpenAI GPT 圖片分析模型 (`gpt-image-1`) 分析上傳圖片，回傳描述與標籤。
3. **語音辨識**
   - 使用 OpenAI Whisper (`whisper-1`) 將語音轉文字。
   - 自動處理速率限制，遇到 `rate limit` 會自動重試。
4. **天氣查詢**
   - 整合 OpenWeatherMap API，即時查詢指定城市的天氣資訊。
5. **前端即時互動**
   - Vue 3 + Composition API，支援文字輸入、圖片與語音上傳。

---

## 技術架構

- **後端**：Laravel 12
  - Controller / Service / Provider 分層
  - OpenAI API 整合
  - 天氣 API 整合
  - 音訊與圖片上傳處理
- **前端**：Vue 3 + Composition API
  - 即時訊息顯示
  - 上傳圖片 / 語音
  - 顯示 AI 回覆
- **資料庫**：可選 MySQL 或 SQLite（專案目前不強依賴 DB）
- **開發工具**：Postman、VS Code
