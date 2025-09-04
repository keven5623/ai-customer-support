import axios from 'axios';

const api = axios.create({
  baseURL: "http://localhost:8000/api"
});

export const sendMessage = async (message) => {
  const res = await api.post("/chat", { message });
  return res.data.reply;
};
