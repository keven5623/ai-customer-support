<template>
  <div class="chat">
    <h1>AI 客服助理</h1>
    <div class="messages">
      <div v-for="(msg, i) in messages" :key="i" :class="msg.role">
        <b>{{ msg.role === 'user' ? '你' : 'AI' }}:</b> {{ msg.text }}
      </div>
    </div>

    <!-- 文字輸入 -->
    <input v-model="input" @keyup.enter="send" placeholder="輸入訊息..." />
    <button @click="send">送出</button>

    <!-- 圖片上傳 -->
    <div style="margin-top: 10px;">
      <p>📷 上傳圖片辨識：</p>
      <input type="file" accept="image/*" @change="sendImage" />
    </div>

    <!-- 語音上傳 -->
    <div style="margin-top: 10px;">
      <p>🎤 上傳語音辨識：</p>
      <input type="file" accept="audio/*" @change="sendAudio" />
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { sendMessage } from "../api/chat";

export default {
  setup() {
    const input = ref("");
    const messages = ref([]);

    const send = async () => {
      if (!input.value) return;
      messages.value.push({ role: "user", text: input.value });

      try {
        const reply = await sendMessage(input.value);
        messages.value.push({ role: "ai", text: reply });
      } catch (e) {
        messages.value.push({ role: "ai", text: "發生錯誤" });
      }

      input.value = "";
    };

    const sendImage = async (event) => {
      const file = event.target.files[0];
      if (!file) return;

      messages.value.push({ role: "user", text: "[上傳了一張圖片]" });

      const formData = new FormData();
      formData.append("photo", file);

      try {
        const res = await fetch("http://localhost:8000/api/chat/image", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        messages.value.push({ role: "ai", text: data.reply });
      } catch (e) {
        messages.value.push({ role: "ai", text: "圖片辨識失敗" });
      }

      event.target.value = null; // 清空 input
    };

    const sendAudio = async (event) => {
      const file = event.target.files[0];
      if (!file) return;

      messages.value.push({ role: "user", text: "[上傳了一段語音]" });

      const formData = new FormData();
      formData.append("voice", file);

      try {
        const res = await fetch("http://localhost:8000/api/chat/audio", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        messages.value.push({ role: "ai", text: data.reply });
      } catch (e) {
        messages.value.push({ role: "ai", text: "語音辨識失敗" });
      }

      event.target.value = null; // 清空 input
    };

    return { input, messages, send, sendImage, sendAudio };
  }
};
</script>

<style>
.chat { max-width: 600px; margin: auto; padding: 20px; }
.messages { margin-bottom: 10px; }
.user { text-align: right; }
.ai { text-align: left; color: blue; }
</style>
